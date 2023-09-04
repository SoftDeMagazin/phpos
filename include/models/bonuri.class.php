<?php
class Bonuri extends AbstractDB
{
	var $useTable="bonuri";
	var $primaryKey="bon_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function adaugaBon($comanda_id, $cui = null)
		{
		$comanda = new Comenzi($this -> mysql, $comanda_id);
		$nr_bon = $this -> mysql -> getRow("SELECT * FROM rpt_bonuri_emise where zi_economica_id = '". $comanda -> obj -> zi_economica_id ."' and casa_id = '". $comanda -> obj -> casa_id ."'");		
		$bon_curent = $nr_bon['bonuri_emise'] + 1;

		$this -> setObjId(0);
		$this -> setObjValue("comanda_id", $comanda -> obj -> comanda_id);
		$this -> setObjValue("user_id", $comanda -> obj -> user_id);
		$this -> setObjValue("numar_bon", $bon_curent);
		$this -> setObjValue("casa_id", $comanda -> obj -> casa_id);
		$this -> setObjValue("data_ora", date("Y-m-d H:i:s"));
		$this -> setObjValue("data", date("Y-m-d"));
		$this -> setObjValue("total", $comanda -> calculeazaTotal());
		$this -> setObjValue("zi_economica_id", $comanda -> obj -> zi_economica_id);
		$this -> setObjValue("cui", $cui);
		$this -> save();
		
		$sql = "
		INSERT INTO bonuri_continut (bon_id, produs_id, cantitate, valoare, discount)
		(SELECT '". $this -> obj -> bon_id ."' as bon_id, produs_id, cantitate, valoare, discount FROM comenzi_continut WHERE comanda_id = '$comanda_id') 
		";
		$this -> mysql -> query($sql);
		}
	
	function adaugaModPlata($suma, $mod_plata_id)
		{
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> setObjId(0);
		$BonPlata -> setObjValue("bon_id", $this -> obj -> bon_id);
		$BonPlata -> setObjValue("mod_plata_id", $mod_plata_id);
		$BonPlata -> setObjValue("suma", $suma);
		$BonPlata -> save();
		}
		
	function inchideBon()
		{
		global $cfgStocuri;
		$this -> setObjValue("inchis", "DA");
		$this -> save();
		if($cfgStocuri['activ'])
			{
			$this -> scadereStocuri();
			}
		}
	
	function bonContinutFactura()
		{
		$out = '
		<table width="100%"  border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10%"><strong>NR.<br> 
    CRT. </strong></td>
    <td width="30%"><strong>DENUMIRE PRODUS SAU SERVICIU </strong></td>
    <td width="10%"><strong>UM</strong></td>
    <td width="10%"><strong>CANT.</strong></td>
    <td width="15%"><strong>PRET UNIT </strong></td>
    <td width="15%"><strong>VALOARE</strong></td>
    <td width="15%"><strong>VALOARE TVA </strong></td>
  </tr>';
  		$continut = new ViewBonuriContinut($this -> mysql);
		$continut -> findAllBy("bon_id", $this -> obj -> bon_id);
		if(isset($continut -> objects))
		{
		$i=0;
		foreach($continut -> objects as $obj)
			{
			$i++;
			$out .= '
  <tr>
    <td>'. $i .'</td>
    <td>'. $obj -> denumire .'</td>
    <td>buc</td>
    <td>'. number_format($obj -> cantitate,2,'.','') .'</td>
    <td>'. number_format(($obj -> valoare*100/124),2,'.','') .'</td>
    <td>'. number_format(($obj -> valoare*100/124)*$obj -> cantitate,2,'.','') .'</td>
    <td>'. number_format(($obj -> valoare*24/124)*$obj -> cantitate,2,'.','') .'</td>
  </tr>
			';
			}
		}
	$out .= '</table>';
	return $out;
		}
	
	function emiteBonFiscal()
		{
		global $cfgFiscal;
		$fiscal = new $cfgFiscal['CasaFiscala']($this -> mysql);
		$fiscal -> bon_id = $this -> obj -> bon_id;
		$fiscal -> FilePath = $cfgFiscal['FilePath'];
		$fiscal -> genereazaBon();
		$fiscal -> executBon();
		}	
	
	function plataRapida($comanda_id, $mod_plata_id, $cui = null)
		{
		$this -> adaugaBon($comanda_id, $cui);
		$this -> adaugaModPlata($this -> obj -> total, $mod_plata_id);
		$this -> emiteBonFiscal();
		$this -> inchideBon();
		}
	
	function verificareStocuri()
		{
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> obj -> bon_id);
		if(isset($BonContinut -> objects))
			{
			$Produs = new Produse($this -> mysql);
			foreach($BonContinut -> objects as $objBonContinut)
				{
				$Produs -> get($objBonContinut -> produs_id);
				if(!$Produs -> verificareStoc($cantitate)) return FALSE;
				}
			}
		return TRUE;		
		}
	
	function scadereStocuri()
		{
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> obj -> bon_id);
		if(isset($BonContinut -> objects))
			{
			$Produs = new Produse($this -> mysql);
			foreach($BonContinut -> objects as $objBonContinut)
				{
				$Produs -> get($objBonContinut -> produs_id);
				if($Produs -> obj -> tip_produs == "reteta")
				$Produs -> scadereStoc($objBonContinut -> cantitate, $objBonContinut -> bon_continut_id, "vanzare_reteta");
				else
				$Produs -> scadereStoc($objBonContinut -> cantitate, $objBonContinut -> bon_continut_id, "vanzare");
				}
			}
		}	
	function plata($frmPlata, $comanda_id, $cui = null)
		{
		$nr_r = count($frmPlata['mod_plata_id']);
		$moduri_fiscale = FALSE;
		$moduri_nefiscale = FALSE;
		for($i=0; $i<$nr_r;$i++)
			{
			$ModPlata = new ModuriPlata($this -> mysql, $frmPlata['mod_plata_id'][$i]);
			if($ModPlata -> obj -> fiscal == 'DA') $moduri_fiscale = TRUE;
			else $moduri_nefiscale = TRUE;
			}
			
		if(($moduri_fiscale == TRUE && $moduri_nefiscale == TRUE))	
			{
			return FALSE;
			}
		else
		{
		$this -> adaugaBon($comanda_id, $cui);
		for($i=0; $i<$nr_r;$i++)
			{
			$this -> adaugaModPlata($frmPlata['suma'][$i], $frmPlata['mod_plata_id'][$i]);
			}
	
		$this -> emiteBonFiscal();
		$this -> inchideBon();
		return TRUE;
		}

		}				
}

class ViewBonuriModuri extends AbstractDB
{
	var $useTable="view_bonuri_moduri";
	var $primaryKey="bon_id";
	function ViewBonuriModuri($mysql,$id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
}

class ViewVanzari extends AbstractDB
{
	var $useTable="view_vanzari";
	var $primaryKey="";
	function ViewVanzari($mysql,$id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
}			
?>