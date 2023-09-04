<?php
class Comenzi extends AbstractDB
{
	var $useTable = "comenzi";
	var $primaryKey = "comanda_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function amHappyHour() {
		global $cfgMarcaj;
		$intervale = $cfgMarcaj['intervale'];
		$gasit = 0;
		$ora = date("1Hi");
			for($i=0;$i< count($intervale) && $gasit == 0;$i++) {
				$int = $intervale[$i];
				if($ora > $int['start'] && $ora < $int['stop']) {
				$gasit = 1;
				$discount = $int['procent'];
				}
				
			}
		if($gasit) return $discount;
		else return 0;	
	}
	
	function adaugaProdus($produs_id, $cantitate)
		{
		global $cfgMarcaj;
		$Produs = new Produse($this -> mysql, $produs_id);		
		$ComandaContinut = new ComenziContinut($this -> mysql);
		$nr_r = $ComandaContinut -> find(array("WHERE", "produs_id" => " = '$produs_id'", "AND", "comanda_id" => " = '". $this -> obj -> comanda_id ."'"));		
		
		$discount = $this -> amHappyHour();
		
		if(in_array($Produs -> obj -> categorie_id, $cfgMarcaj['h_categorii_excluse'])) {
			$discount = '0.00';
		}
		
		$day = date("D");
		//if($day == "Sun" or $day == "Sat") {
			//$discount = '0.00';
		//}
		
		if(!$nr_r)
		{
		$ComandaContinut -> setObjId(0);
		$ComandaContinut -> setObjValue("comanda_id", $this -> obj -> comanda_id);
		$ComandaContinut -> setObjValue("produs_id", $produs_id);
		$ComandaContinut -> setObjValue("cantitate", $cantitate);
		$ComandaContinut -> setObjValue("valoare", $Produs -> obj -> pret);
		$ComandaContinut -> setObjValue("discount", $discount);
		$ComandaContinut -> save();
		}
		else
		{
		$ComandaContinut -> obj = $ComandaContinut -> objects[0];
		$ComandaContinut -> obj -> cantitate += $cantitate;
		$ComandaContinut -> save();
		}
		}
	
	function calculeazaTotal()
		{
		$total = $this -> mysql -> getRow("SELECT SUM(cantitate*valoare - cantitate*valoare*discount/100) as total FROM comenzi_continut WHERE comanda_id =  '". $this -> obj -> comanda_id ."'");
		return number_format($total['total'], 2, '.', '');
		}	
		
	
	function verificareStocuri()
		{
		$ComandaContinut = new ComenziContinut($this -> mysql);
		$ComandaContinut -> findAllBy("comanda_id", $this -> obj -> comanda_id);
		if(isset($ComandaContinut -> objects))
			{
			$prod = new Produse($this -> mysql);
			foreach($ComandaContinut -> objects as $objContinut)
				{
				$prod -> get($objContinut -> produs_id);
				if($prod -> verificareStoc($objContinut -> cantitate) == FALSE) return FALSE;
				}
			}
		return TRUE;		
		}	
		
	function verificareStocuriPlus()
		{
		$ComandaContinut = new ComenziContinut($this -> mysql);
		$ComandaContinut -> findAllBy("comanda_id", $this -> obj -> comanda_id);
		$array = array();
		$prod = new Produse($this -> mysql);
		if(isset($ComandaContinut -> objects))
			{		
			foreach($ComandaContinut -> objects as $objContinut)
				{
				$prod -> get($objContinut -> produs_id);
				$prod -> getComponente($array, $objContinut -> cantitate);
				}
			}
		if($array)
			{
			foreach($array as $ar)
				{
				$prod -> get($ar['produs_id']);
				if($prod -> verificareStoc($ar['cant']) == FALSE) return FALSE;
				}
			}
		return TRUE;		
		}
	
	function comandaContinut($buttons=TRUE)
		{
		$ComandaContinut = new ViewComenziContinut($this -> mysql);
		$ComandaContinut -> findAllBy("comanda_id", $this -> obj -> comanda_id);
		$txt = "";
		if(isset($ComandaContinut -> objects))
			{
			$txt .= $this -> comandaContinutHead($buttons);
			$i = 1;
			foreach($ComandaContinut -> objects as $objContinut)
				{
				if($i % 2 == 0 ) $rowClass = "roweven";
				else $rowClass = "rowodd";
				$txt .= $this -> comandaContinutRow($objContinut,$rowClass, $buttons);
				if($i % 20 == 0)
					{
					$txt .= $this -> comandaContinutFooter();
					$txt .= $this -> comandaContinutHead($buttons);
					}
				$i++;
				}
			$txt .= $this -> comandaContinutFooter();
			}
		return $txt;	
		}
	
	function countProduse()
		{
		$nr_produse = $this -> mysql -> getRow("SELECT COUNT(*) as nr FROM comenzi_continut WHERE comanda_id = '". $this -> obj -> comanda_id ."'");
		return $nr_produse['nr'];
		}
	
	function golesteComanda()
		{
		$this -> mysql -> query("DELETE FROM comenzi_continut WHERE comanda_id = '". $this -> obj -> comanda_id ."'");
		}	
	
	function comandaContinutRow($objContinut, $rowClass, $buttons=TRUE)
		{
	//$Produs = new Produse($this -> mysql, $objContinut -> produs_id);	
	$txt = '
	<tr class="'. $rowClass .'" onMouseOver="$(this).addClass(\'rowhover\')" onMouseOut="$(this).removeClass(\'rowhover\')" onClick="xajax_clickProdusComanda('. $objContinut -> comanda_continut_id .');">';
      if($buttons) $txt .= '<td><div align="center"><input name="chkContinutComanda[]" type="checkbox" value="'. $objContinut -> comanda_continut_id .'" checked></div></td>';
	 
	 $txt .= '<td><div align="center">'. $objContinut -> cantitate .'</div></td>
      <td>'. $objContinut -> denumire .'</td>
      <td>'. number_format($objContinut->valoare, 2, '.', '') .'</td>
	   <td>'. number_format($objContinut->valoare - ($objContinut->valoare*$objContinut->discount)/100, 2, '.', '') .'</td>
      <td>'. number_format($objContinut -> cantitate*$objContinut->valoare - $objContinut -> cantitate*$objContinut->valoare*$objContinut->discount/100, 2, '.', '') .'</td>';
     
	 if($buttons) $txt .=
	  '<td><div align="center"><a href="#" onClick="xajax_btnPlus('. $objContinut -> comanda_continut_id .');return false;"><img src="files/img/gtk-add.png" width="24" height="24"></a></div></td>
      <td><div align="center"><a href="#" onClick="xajax_btnMinus('. $objContinut -> comanda_continut_id .');return false;"><img src="files/img/gtk-remove.png" width="24" height="24"></a></div></td>
      <td><div align="center"><a href="#" onClick="xajax_btnSterge('. $objContinut -> comanda_continut_id .');return false;"><img src="files/img/gtk-close.png" width="24" height="24"></a></div></td>';
    
	$txt .= '</tr>';
	
	return $txt;
		}
	
	function comandaContinutHead($buttons=TRUE)
		{
		$txt = '
		  <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="rowhead">';
	  if($buttons) $txt .= '<td width="2%"><div align="center"><strong></strong></div></td>';
 	$txt .='<td width="9%"><div align="center"><strong>Cantitate</strong></div></td>
      <td width="30%"><div align="center"><strong>Produs</strong></div></td>
      <td width="16%"><div align="center"><strong>Pret</strong></div></td>
	  <td width="16%"><div align="center"><strong>Pret Redus</strong></div></td>
      <td width="16%"><div align="center"><strong>Subtotal</strong></div></td>';
       if($buttons) $txt .='<td width="9%">&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="9%">&nbsp;</td>';
	$txt .= '</tr>
		';
		return $txt;
		}
	
	function comandaContinutFooter()
		{
		return '</table>';
		}					
}
?>
