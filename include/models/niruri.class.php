<?php
class Niruri extends AbstractDB
{
	var $useTable="niruri";
	var $primaryKey="nir_id";
	var $form = array(
		"nir_id" => array(
			"input" => array("type" => "hidden"),
			"label" => false
		),
		"furnizor_id" => array(
			"input" => array("type" => "select"),
			"data_source" => "SELECT furnizor_id, nume FROM furnizori order by nume asc",
			"label" => "Furnizor"
		),
		"numar_factura" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"data_factura" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"data_scadenta" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"total_fara_tva" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"total_tva" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"numar_nir" => array(),
		"total_factura" => array(),
		"data_adaugare" => array(),
		"user_id" => array(),
		"tip_nir" => array()	
	);
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function verificare($frmNir, &$message)
		{
		if(empty($frmNir['numar_factura']))
			{
			$message = "Introduceti Numar Factura/Aviz";
			return false;
			} 
		if(empty($frmNir['data_factura']))	
			{
			$message = "Introduceti Data Factura";
			return false;
			} 
		if(empty($frmNir['total_fara_tva']))	
			{
			$message = "Introduceti Totalul facturii";
			return false;
			} 
		if(empty($frmNir['total_tva']))	
			{
			$message = "Introduceti Tva pentru factura";
			return false;
			} 
		return true;
		}	
		
	function inchideNir($frmNir, &$message)
		{
		if(!$this -> verificare($frmNir, $message))
			{
			return false;
			}
		$frmNir['data_adaugare'] = date("Y-m-d H:i:s");
		$frmNir['total_factura'] = $frmNir['total_fara_tva'] + $frmNir['total_tva'];
		$frmNir['user_id'] = $_SESSION['USERID'];
		if(empty($frmNir['nir_id']))
		{
		$row = $this -> mysql -> getRow("SELECT MAX(numar_nir) as max_numar_nir FROM ". $this -> useTable ."");
		$frmNir['numar_nir'] = $row['max_numar_nir']+1;
		}
		$this -> saveForm($frmNir);
		return true;	
		}
	
	function saveEditNir($frmNir, &$message)
		{
		if(!$this -> verificare($frmNir, $message))
			{
			return false;
			}
		if(empty($this -> obj -> numar_nir))
			{
			$row = $this -> mysql -> getRow("SELECT MAX(numar_nir) as max_numar_nir FROM ". $this -> useTable ."");
			$frmNir['numar_nir'] = $row['max_numar_nir']+1;
			}	
		$frmNir['data_adaugare'] = date("Y-m-d H:i:s");
		$frmNir['total_factura'] = $frmNir['total_fara_tva'] + $frmNir['total_tva'];
		$frmNir['user_id'] = $_SESSION['USERID'];
		$this -> saveForm($frmNir);
		return true;	
		}
	
	function adaugaComponente($frmComponente)
		{
		$i = 0;
		$NirComponenta = new NiruriComponente($this -> mysql);
		$NirComponenta -> tableToForm();
		$Produs = new Produse($this -> mysql);
		$UnitateMasura = new UnitatiMasura($this -> mysql);
		foreach($frmComponente['data'] as $frmComponenta)
			{
			$frmComponenta['nir_id'] = $this -> obj -> nir_id;
			$Produs -> findBy('denumire', $frmComponenta['denumire']);
			$frmComponenta['produs_id'] = $Produs -> obj -> produs_id;
			$UnitateMasura -> findBy('unitate_masura', $frmComponenta['um']);
			$frmComponenta['unitate_masura_id'] = $UnitateMasura -> obj -> unitate_masura_id;
			$NirComponenta -> adaugaComponenta($frmComponenta);
			}
		}
	
	function calculeazaTotalComponente($frmComponente, $mod)
		{
		$total = 0;
		foreach($frmComponente['data'] as $frmComponenta)
			{
			$total += $frmComponenta[$mod];
			}
		return $total;
		}	
	
	function preview()
		{
		$furnizor = new Furnizori($this -> mysql, $this -> obj -> furnizor_id);
		$NirComponente = new NiruriComponente($this -> mysql);
		$NirComponente -> findAllBy("nir_id", $this -> obj -> nir_id);
		$out = '';
		$out .= '<table width="100%" height="700"  border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000;">
  <tr>
    <td scope="col" valign="top">
	<div id="titlu"><h2 align="center">Nota de intrare receptie nr. '. $this -> obj -> numar_nir .'</h2>
	<div align="center">Data: '. date("d/m/Y", strtotime($this -> obj -> data_factura)) .' </div>
	</div>
	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25%"><strong>FURNIZOR</strong></td>
        <td width="25%">'. $furnizor -> obj -> nume .'</td>
        <td width="25%">&nbsp;</td>
        <td width="25%">&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Factura/Aviz Nr. </strong></td>
        <td>'. $this -> obj -> numar_factura .'</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>';
	$out .= '
	<table width="98%" id="componente"  border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px; ">
      <tr>
        <th scope="col">&nbsp;</th>
        <th scope="col">&nbsp;</th>
        <th colspan="6" scope="col">FURNIZOR</th>';
		if($this -> obj -> tip_nir == "marfa")
		{
     $out .=   '<th colspan="5" scope="col">VANZARE</th>
        <th scope="col">&nbsp;</th>';
		}
	$out .='	
      </tr>
      <tr>
        <th scope="col"><div align="center">Nr.<br>
        crt.</div></th>
        <th scope="col">DENUMIRE PRODUS </th>
        <th scope="col"><div align="center">UM</div></th>
        <th scope="col"><div align="center">CANT</div></th>
        <th scope="col">PRET<br>
      UNIT</th>
        <th scope="col">VAL.</th>
        <th scope="col">TVA</th>
        <th scope="col">TOTAL <br>
      TVA</th>';
	  
		if($this -> obj -> tip_nir == "marfa")
		{
		$out .= '  
        <th scope="col">ADAOS</th>
        <th scope="col">TOTAL<br>
      ADAOS</th>
        <th scope="col">TVA<br>
      UNITAR</th>
        <th scope="col">TOTAL<br>
      TVA</th>
        <th scope="col">PRET <br>
      UNITAR</th>
        <th scope="col">VALOARE <br>
      TOTAL</th>';
	  }
      $out .= '</tr>';
	  if(isset($NirComponente -> objects))
	  {
	  $i = 1;
$totalAch = 0;
$totalTva = 0;
$totalVal = 0;
$totalAdaos = 0;
$totalTvaVanzare = 0;

	  	foreach($NirComponente -> objects as $objComp)
			{
			$um = new UnitatiMasura($this -> mysql, $objComp -> unitate_masura_id);
			$out .= '
			      <tr>
        <td><div align="center">'. $i .'</div></td>
        <td>'. $objComp -> denumire .'</td>
        <td><div align="center">'. $um -> obj -> unitate_masura .'</div></td>
        <td><div align="center">'. $objComp -> cant .'</div></td>
        <td><div align="center">'. $objComp -> pret_ach .'</div></td>
        <td><div align="center">'. $objComp -> val_ach .'</div></td>
        <td><div align="center">'. $objComp -> tva_ach .'</div></td>
        <td><div align="center">'. $objComp -> total_tva_ach .'</div></td>';
			if($this -> obj -> tip_nir == "marfa")
		{
        $out .= '<td><div align="center">'. $objComp -> adaos_unit .'</div></td>
        <td><div align="center">'. $objComp -> total_adaos .'</div></td>
        <td><div align="center">'. $objComp -> tva_vanzare .'</div></td>
        <td><div align="center">'. $objComp -> total_tva_vanzare .'</div></td>
        <td><div align="center">'. $objComp -> pret_vanzare .'</div></td>
        <td><div align="center">'. $objComp -> val_total .'</div></td>';
     
	  }
	   $out .= '</tr>';
$totalAch += $objComp -> val_ach;
$totalTva += $objComp -> total_tva_ach;
$totalVal += $objComp -> val_total;
$totalAdaos += $objComp -> adaos_unit*$objComp -> cant;
$totalTvaVanzare += $objComp -> total_tva_vanzare;
			$i++;
			}
	  }
	$out .= '
			      <tr>
        <td><div align="center">&nbsp;</div></td>
        <td>Total</td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">'. number_format($totalAch,2,'.','') .'</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">'. number_format($totalTva,2,'.','') .'</div></td>';
        	if($this -> obj -> tip_nir == "marfa")
		{
		$out .= '
		<td><div align="center">&nbsp;</div></td>
        <td><div align="center">'. number_format($totalAdaos,2,'.','')  .'</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">'. number_format($totalTvaVanzare,2,'.','')  .'</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">'. number_format($totalVal,2,'.','') .'</div></td>';
		}
      $out .= '</tr>';
	  
$totalFactura = $totalAch + $totalTva;
$out .= '
			      <tr>
        <td><div align="center">&nbsp;</div></td>
        <td>Total Factura</td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">'. number_format($totalFactura,2,'.','') .'</div></td>
        <td><div align="center">&nbsp;</div></td>
		  <td><div align="center">&nbsp;</div></td>';
			if($this -> obj -> tip_nir == "marfa")
		{
		$out .='
      
        <td><div align="center">'. number_format(($totalAdaos/$totalAch)*100,2,'.','') .'%</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">&nbsp;</div></td>
        <td><div align="center">&nbsp;</div></td>
<td><div align="center">&nbsp;</div></td>
<td><div align="center">&nbsp;</div></td>';
}
      $out .= '</tr>';
	  $out .='
    </table></td>
  </tr>
</table>';

		return $out;
		}	
		
		function frmAddComponenta($frmValues)
		{
		if(!empty($frmValues['txtProdusId']) && !empty($frmValues['txtPretAchizitie']) && !empty($frmValues['txtProdusId']))
			{
			$time = time().'_'.rand(0,50000);
			$frmValues['txtTva'] = number_format(($frmValues['txtPretAchizitie']*24)/100,2,'.','');
			$produs = new Produse($this -> mysql, $frmValues['txtProdusId']);
			$um = new UnitatiMasura($this -> mysql, $frmValues['unitate_masura_id']);
			$val_ach = number_format($frmValues['txtCantitate']*$frmValues['txtPretAchizitie'],2,'.','');
			$total_tva_ach = number_format(($val_ach*24)/100,2,'.','');
			$pret_vanzare = $produs -> obj -> pret;
			$tva_vanzare = number_format(($pret_vanzare*24)/124, 2, '.', '');
			$total_tva_vanzare = $tva_vanzare*$frmValues['txtCantitate'];
			$val_total = $pret_vanzare*$frmValues['txtCantitate'];
			$adaos_unit = $pret_vanzare - $tva_vanzare - $frmValues['txtPretAchizitie'];
			$total_adaos = $adaos_unit*$frmValues['txtCantitate']; 
			$txt = '
			<div id="'. $time .'">	
	<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:10px; ">	
    <tr>
      <td width="129" nowrap><input type="text" name="data['.$time.'][denumire]" style="border:1px solid #000;border-right: 0px solid #000; width:129px; font-size:10px; text-align:center; " readonly=""  value="'. $produs -> obj -> denumire .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="20" nowrap><input type="text" name="data['.$time.'][um]" style="border:1px solid #000;border-right: 0px solid #000; width:20px; font-size:10px;text-align:center; " readonly="" value="'. $um -> obj -> unitate_masura .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="36" nowrap><input type="text" name="data['.$time.'][cant]" style="border:1px solid #000;border-right: 0px solid #000; width:36px; font-size:10px;text-align:center; " value="'. $frmValues['txtCantitate'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][pret_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center; " value="'. $frmValues['txtPretAchizitie'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="68" nowrap><input type="text" name="data['.$time.'][val_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:68px; font-size:10px;text-align:center; " readonly="" value="'. $val_ach .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center;" value="'. $frmValues['txtTva'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="'. $total_tva_ach .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="86" nowrap><input type="text" name="data['.$time.'][adaos_unit]" style="border:1px solid #000;border-right: 0px solid #000; width:86px; font-size:10px;text-align:center; " readonly="" value="'. $adaos_unit .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="100" nowrap><input type="text" name="data['.$time.'][total_adaos]" style="border:1px solid #000;border-right: 0px solid #000; width:100px; font-size:10px;text-align:center; " readonly="" value="'. $total_adaos .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="61" nowrap><input type="text" name="data['.$time.'][tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:61px; font-size:10px;text-align:center; " readonly="" value="'. $tva_vanzare .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="'. $total_tva_vanzare .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="104" nowrap><input type="text" name="data['.$time.'][pret_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:104px; font-size:10px;text-align:center; " readonly="" value="'.$pret_vanzare.'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="90" nowrap><input type="text" name="data['.$time.'][val_total]" style="border:1px solid #000;border-right: 0px solid #000; width:90px; font-size:10px;text-align:center; " readonly="" value="'.$val_total.'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
    </tr>
  </table>
</div>  
';
	return $txt;
	}
	else return false;
	
		}
	function frmAddComponentaRapid($frmValues)
		{
		if(!empty($frmValues['txtProdusId']) && !empty($frmValues['txtPretAchizitie']) && !empty($frmValues['txtProdusId']))
			{
			$time = time().'_'.rand(0,50000);
			$frmValues['txtTva'] = number_format(($frmValues['txtPretAchizitie']*24)/100,2,'.','');
			$produs = new Produse($this -> mysql, $frmValues['txtProdusId']);
			$um = new UnitatiMasura($this -> mysql, $frmValues['unitate_masura_id']);
			$val_ach = number_format($frmValues['txtCantitate']*$frmValues['txtPretAchizitie'],2,'.','');
			$total_tva_ach = number_format(($val_ach*24)/100,2,'.','');
			$pret_vanzare = $produs -> obj -> pret;
			$tva_vanzare = number_format(($pret_vanzare*24)/124, 2, '.', '');
			$total_tva_vanzare = $tva_vanzare*$frmValues['txtCantitate'];
			$val_total = $pret_vanzare*$frmValues['txtCantitate'];
			$adaos_unit = $pret_vanzare - $tva_vanzare - $frmValues['txtPretAchizitie'];
			$total_adaos = $adaos_unit*$frmValues['txtCantitate']; 
			$txt = '
			<div id="'. $time .'">	
	<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:10px; ">	
    <tr>
      <td width="129" nowrap><input type="text" name="data['.$time.'][denumire]" style="border:1px solid #000;border-right: 0px solid #000; width:129px; font-size:10px; text-align:center; " readonly=""  value="'. $frmValues['denumire'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="20" nowrap><input type="text" name="data['.$time.'][um]" style="border:1px solid #000;border-right: 0px solid #000; width:20px; font-size:10px;text-align:center; " readonly="" value="'. $frmValues['um'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="36" nowrap><input type="text" name="data['.$time.'][cant]" style="border:1px solid #000;border-right: 0px solid #000; width:36px; font-size:10px;text-align:center; " value="'. $frmValues['txtCantitate'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][pret_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center; " value="'. $frmValues['txtPretAchizitie'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="68" nowrap><input type="text" name="data['.$time.'][val_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:68px; font-size:10px;text-align:center; " readonly="" value="'. $val_ach .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center;" value="'. $frmValues['txtTva'] .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="'. $total_tva_ach .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="86" nowrap><input type="text" name="data['.$time.'][adaos_unit]" style="border:1px solid #000;border-right: 0px solid #000; width:86px; font-size:10px;text-align:center; " readonly="" value="'. $adaos_unit .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="100" nowrap><input type="text" name="data['.$time.'][total_adaos]" style="border:1px solid #000;border-right: 0px solid #000; width:100px; font-size:10px;text-align:center; " readonly="" value="'. $total_adaos .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="61" nowrap><input type="text" name="data['.$time.'][tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:61px; font-size:10px;text-align:center; " readonly="" value="'. $tva_vanzare .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="'. $total_tva_vanzare .'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="104" nowrap><input type="text" name="data['.$time.'][pret_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:104px; font-size:10px;text-align:center; " readonly="" value="'.$pret_vanzare.'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
      <td width="90" nowrap><input type="text" name="data['.$time.'][val_total]" style="border:1px solid #000;border-right: 0px solid #000; width:90px; font-size:10px;text-align:center; " readonly="" value="'.$val_total.'" onClick="xajax_editComponenta(\''. $time .'\', xajax.getFormValues(\'frmComponente\'));"></td>
    </tr>
  </table>
</div>  
';
	return $txt; 
	}
	else return false;
	
		}

	function stergeContinut()	
		{
		$this -> mysql -> query("DELETE FROM intrari_continut WHERE nir_id = '". $this -> obj -> nir_id ."' and tip='nir'");
		}
	
	function genereazaLoturi()
		{
		$sql = "INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, data, pret_vanzare, adaos_unit ,activ)
		(SELECT '". $this -> obj -> nir_id ."' as nir_id, nir_componenta_id, produs_id, cant, cant, pret_ach,'". $this -> obj -> data_factura ."' as data, pret_vanzare , adaos_unit, 1 as activ FROM niruri_componente where nir_id = '". $this -> obj -> nir_id ."')
		";
		$this -> mysql -> query($sql);
		}
	
	function salveazaPreturiVanzare()
		{
		global $cfgStocuri;
		$componente = new NiruriComponente($this -> mysql);
		$componente -> findAllBy("nir_id", $this -> obj -> nir_id);
		if(isset($componente -> objects))
			{
			$i = 0;
			$produs = new Produse($this -> mysql);
			foreach($componente -> objects as $obj)
				{
				$produs -> get($obj -> produs_id);
				
				if($obj -> pret_vanzare != $produs -> obj -> pret)	
					{
					if($cfgStocuri['modificari_pret']) {
						$this -> mysql -> query("delete from modificari_pret where produs_id = '". $frmValues['produs_id'] ."' and activat = 'NU'");
						$mp = new ModificariPret($this -> mysql);
						$mp -> obj -> produs_id = $produs -> obj -> produs_id;
						$mp -> obj -> pret_vechi = $produs -> obj -> pret;
						$mp -> obj -> pret_nou = $frmValues['pret'];
						$mp -> obj -> data_modificare = date("Y-m-d H:i:s");
						$mp -> obj -> user_id = $_SESSION['USERID'];
						$mp -> obj -> stoc = $produs -> getStoc();
						$mp -> save();
						$i++;
					}
					else {
						$produs -> obj -> pret = $obj -> pret_vanzare;
						$produs -> save();
					}
					}
				}
			}
		return $i;
		}
			
	function verifica()
		{
		$row = $this -> mysql -> getRow("
		SELECT count(*) as nr
		FROM intrari_continut
		WHERE nir_id = '". $this -> obj -> nir_id ."' and tip = 'nir' and (cantitate<> cantitate_ramasa or cantitate = 0);
		");
		if($row['nr'] == 0) return true;
		else return false;
		}
	
	function exportXls()
		{
		$furnizor = new Furnizori($this -> mysql, $this -> obj -> furnizor_id);
		$NirComponente = new NiruriComponente($this -> mysql);
		$NirComponente -> findAllBy("nir_id", $this -> obj -> nir_id);
		$out = '';
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("xls/nir.xls");
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('C6', $furnizor -> obj -> nume);
		$objPHPExcel->getActiveSheet()->setCellValue('C7', $this -> obj -> numar_factura);
		$objPHPExcel->getActiveSheet()->setCellValue('C3', $this -> obj -> numar_nir);
		$objPHPExcel->getActiveSheet()->setCellValue('C5', date("d/m/Y", strtotime($this -> obj -> data_factura)));
		
	  if(isset($NirComponente -> objects))
	  {
	  $i = 1;
	  $linie = 11;
	  	foreach($NirComponente -> objects as $objComp)
			{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$linie, $i);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$linie,$objComp -> denumire);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$linie,$objComp -> um);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$linie,$objComp -> cant);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$linie,$objComp -> pret_ach);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$linie,$objComp -> val_ach);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$linie,$objComp -> tva_ach);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$linie,$objComp -> total_tva_ach);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$linie,$objComp -> adaos_unit);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$linie,$objComp -> total_adaos);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$linie,$objComp -> tva_vanzare);
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$linie,$objComp -> total_tva_vanzare);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$linie,$objComp -> pret_vanzare);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$linie,$objComp -> val_total);	
			$i++;
			$linie++;
			}
	  	}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$time = time();
		$objWriter -> save("temp/".$time.".xls");
		return $time;
		}		
		
		function facturaTotalAchitat()
			{
			$row = $this -> mysql -> getRow("SELECT sum(suma) as achitat FROM registru_casa where tip='plata' and tip_inregistrare='factura_furnizor' and document_id = '". $this -> obj -> nir_id ."'");
			return number_format($row['achitat'],2,'.','');
			}
			
		function facturaRestPlata()
			{
			return number_format($this -> obj -> total_factura - $this -> facturaTotalAchitat(),2,'.','');
			}
		
		function facturaDetalierePlati($click="")
			{
			global $html;
	$registru = new RegistruCasa($this -> mysql);
	$nr_r = $registru -> find(array("WHERE",
	"document_id" => $this -> mysql -> equal($this -> obj -> nir_id),
	"and",
	"tip" => $this -> mysql -> equal("plata"),
	"and",
	"tip_inregistrare" => $this -> mysql -> equal("factura_furnizor"),
	));
	$out = '';
	if($nr_r)
		{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "90%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"center");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Numar Document", "Data" , "Tip document", "Suma", "Explicatie");
					$gv -> tableOptions['ColWidth'] = array();
					for($i=0; $i<$nr_r;$i++)
						{
						$ck = $click;
						$obj = $registru -> objects[$i];
						$ck = $this -> stringReplace($ck, $obj);
						$gv -> dataTable[$i]['data'] = array($obj -> numar_document, $obj -> data_document, $obj -> tip_document, $obj -> suma, $obj -> explicatie_document);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#divForm tr').removeClass('rowclick');$(this).addClass('rowclick');$ck",
						);
						}
						$html -> append($out, ''.$gv -> getTable().'');
		}
	else
		{
		$html -> append($out, 'NU SUNT PLATI INREGISTRATE');
		}
	return $out;
	}
}

class ViewNiruriDetalii extends AbstractDB
{
	var $useTable="view_niruri_detalii";
	var $primaryKey="nir_id";
	
	function ViewNiruriDetalii($mysql, $id = NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
}
?>