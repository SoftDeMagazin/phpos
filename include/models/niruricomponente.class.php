<?php
class NiruriComponente extends AbstractDB
{
	var $useTable="niruri_componente";
	var $primaryKey="nir_componenta_id";
	var $form = array(
		"nir_componenta_id" => array(
			"input" => array("type" => "hidden"),
			"label" => false
		),
	);
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
		
	function adaugaComponenta($frmValues, $nir_id = NULL)
		{
		if(!isset($frmValues['nir_componenta_id'])) $frmValues['nir_componenta_id'] = 0;
		if($nir_id == NULL) $nir_id = $this -> obj -> nir_id;
		$frmValues['nir_id'] = $nir_id;
		$this -> tableToForm();
		$this -> saveForm($frmValues);
		}
		
	function frmComponenta($edit = FALSE)
		{
			$time = $this -> obj -> nir_componenta_id;
			$frmValues['txtTva'] = $this -> obj -> tva_ach;
			$val_ach = $this -> obj -> val_ach;
			$total_tva_ach = $this -> obj -> total_tva_ach;
			$pret_vanzare = $this -> obj -> pret_vanzare;
			$tva_vanzare = $this -> obj -> tva_vanzare;
			$total_tva_vanzare = $this -> obj -> total_tva_vanzare;
			$val_total = $this -> obj -> val_total;
			$adaos_unit = $this -> obj -> adaos_unit;
			$total_adaos = $this -> obj -> total_adaos; 
			$txt = '';
			$um = new UnitatiMasura($this -> mysql, $this -> obj -> unitate_masura_id);
			if($edit == FALSE) $txt .= '<div id="comp_'. $time .'">';
			$txt .= '
	<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:10px; ">	
    <tr>
      <td width="129" nowrap><input type="text" name="data['.$time.'][denumire]" style="border:1px solid #000;border-right: 0px solid #000; width:129px; font-size:10px; text-align:center; " readonly=""  value="'. $this -> obj -> denumire .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="20" nowrap><input type="text" name="data['.$time.'][um]" style="border:1px solid #000;border-right: 0px solid #000; width:20px; font-size:10px;text-align:center; " readonly="" value="'. $um -> obj -> unitate_masura .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="36" nowrap><input type="text" name="data['.$time.'][cant]" style="border:1px solid #000;border-right: 0px solid #000; width:36px; font-size:10px;text-align:center; " value="'. $this -> obj -> cant .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][pret_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center; " value="'. $this -> obj -> pret_ach .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="68" nowrap><input type="text" name="data['.$time.'][val_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:68px; font-size:10px;text-align:center; " readonly="" value="'. $val_ach .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center;" value="'. $frmValues['txtTva'] .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="'. $total_tva_ach .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="86" nowrap><input type="text" name="data['.$time.'][adaos_unit]" style="border:1px solid #000;border-right: 0px solid #000; width:86px; font-size:10px;text-align:center; " readonly="" value="'. $adaos_unit .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="100" nowrap><input type="text" name="data['.$time.'][total_adaos]" style="border:1px solid #000;border-right: 0px solid #000; width:100px; font-size:10px;text-align:center; " readonly="" value="'. $total_adaos .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="61" nowrap><input type="text" name="data['.$time.'][tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:61px; font-size:10px;text-align:center; " readonly="" value="'. $tva_vanzare .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="'. $total_tva_vanzare .'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="104" nowrap><input type="text" name="data['.$time.'][pret_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:104px; font-size:10px;text-align:center; " readonly="" value="'.$pret_vanzare.'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
      <td width="90" nowrap><input type="text" name="data['.$time.'][val_total]" style="border:1px solid #000;border-right: 0px solid #000; width:90px; font-size:10px;text-align:center; " readonly="" value="'.$val_total.'" onClick="xajax_editComponenta('. $this -> obj -> nir_componenta_id .')"></td>
    </tr>
  	</table>
	';
		if($edit == FALSE) $txt .= '</div>';
	return $txt;
		}
	
}
?>