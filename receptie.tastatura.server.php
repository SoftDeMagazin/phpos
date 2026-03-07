<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("receptie.tastatura.common.php");

$mysql = new MySQL();
$html = new Html;

function onLoad($nir_id)
{
    global $mysql;
    global $html;
    $row = $mysql -> getRow("SELECT COUNT(*)as nr FROM modificari_pret where activat = 'NU'");
    if ($row['nr'] != 0) {
        $objResponse = new xajaxResponse();
        $objResponse -> alert('Exista modificari de pret neactivate!');
        $objResponse -> script("window.location.href = 'modificaripret.php'");
        return $objResponse;
    }
    $intrare = new Niruri($mysql, $nir_id);
    if ($intrare -> verifica()) {
        $objResponse = new xajaxResponse();
        $NirComponente = new NiruriComponente($mysql);
        $NirComponente -> findAllBy("nir_id", $nir_id);
        $componente = '';
        if (isset($NirComponente -> objects)) {
            foreach ($NirComponente -> objects as $objComp) {
                $NirComponente -> obj = $objComp;
                $componente .= $NirComponente -> frmComponenta();
            }
        }
        $objResponse -> assign("nir_id", "value", $intrare -> obj -> nir_id);
        $objResponse -> assign("div_numar_nir", "innerHTML", $intrare -> obj -> numar_nir);
        $furn ='<select name="furnizor_id"  id="furnizor_id" style="width:350px" tabindex="1" onKeyUp="if(event.keyCode==13) document.getElementById(\'numar_factura\').focus();">';
        $furnizori = new Furnizori($mysql);
        $furnizori -> find(array("ORDER BY", "nume", "ASC"));
        if (isset($furnizori -> objects)) {
            foreach ($furnizori -> objects as $obj) {
                if ($obj -> furnizor_id == $intrare -> obj -> furnizor_id) {
                    $furn .= '<option value="'. $obj -> furnizor_id .'" selected>'.$obj -> nume.'</option>';
                } else {
                    $furn .= '<option value="'. $obj -> furnizor_id .'">'.$obj -> nume.'</option>';
                }
            }
        }
        $furn .= '</select>';
        $objResponse -> assign("div_furnizor_id", "innerHTML", $furn);
        $objResponse -> assign("numar_factura", "value", $intrare -> obj -> numar_factura);
        $objResponse -> assign("data_factura", "value", $intrare -> obj -> data_factura);
        $objResponse -> assign("total_fara_tva", "value", $intrare -> obj -> total_fara_tva);
        $objResponse -> assign("total_tva", "value", $intrare -> obj -> total_tva);
        $objResponse -> assign("intrare_componente", "innerHTML", $componente);
    } else {
        $objResponse = new xajaxResponse();
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu puteti edita nirul nr'. $intrare -> obj -> numar_nir .'<h3>
		<div>Loturile generate sunt incepute.</div>
		<div align="center"><input type="button" value="OK" class="btnTouch" onClick="window.location.href = \'evidenta.niruri.php\'"></div>', "400px", "250px", "350px", "250px", false);
    }
    return $objResponse;
}

function salveazaAntet($frmNir)
{
    global $mysql;
    global $html;
    $nir = new Niruri($mysql);
    if ($nir -> inchideNir($frmNir, $message)) {
        $objResponse = new xajaxResponse;
        $objResponse -> assign("nir_id", "value", $nir -> obj -> nir_id);
        $objResponse -> assign("div_numar_nir", "innerHTML", $nir -> obj -> numar_nir);
    } else {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">'. $message .'<h3>', "400px", "150px", "350px", "250px", "OK");
    }
    return $objResponse;
}

function adaugaComponenta($frmValues, $nir_id, $window)
{
    global $mysql;
    if (empty($nir_id)) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Salvati antet nir!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    if (empty($frmValues['produs_id'])) {
        $produs = new Produse($mysql);
        $produs -> obj -> denumire = $frmValues['denumire'];
        $produs -> obj -> cod_bare = $frmValues['cod_bare'];
        $produs -> obj -> categorie_id = $frmValues['categorie_id'];
        $Produs = new Produse($mysql);
        $nr_r = $Produs -> findAllBy("cod_bare", $frmValues['cod_bare']);
        if ($nr_r != 0) {
            if (!$frmValues['produs_id'] && $nr_r==1) {
                $objResponse = new xajaxResponse();
                $objResponse -> alert('Cod bare existent la produsul:'.$Produs -> objects[0] -> denumire);
                return $objResponse;
            }
        }
        $produs -> save();
        $frmValues['produs_id'] = $produs -> obj -> produs_id;
    }
    if (!$frmValues['cant']) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Introduceti cantitate!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    if ($frmValues['pret_ach']<0) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Introduceti pret achizitie!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    $componenta = new NiruriComponente($mysql);
    $componenta -> adaugaComponenta($frmValues, $nir_id);
    $objResponse = new xajaxResponse();
    $objResponse = close_window($window);
    $objResponse -> append("intrare_componente", "innerHTML", $componenta -> frmComponenta());
    $objResponse -> script("xajax.$('btnComponente').focus()");
    return $objResponse;
}

function salveazaComponenta($frmValues, $nir_id, $window)
{
    global $mysql;
    $nir = new Niruri($mysql, $nir_id);
    if (empty($nir_id)) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Salvati antet nir!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    if (empty($frmValues['produs_id'])) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Selectati produs!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    if (!$frmValues['cant']) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Introduceti cantitate!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    if (!$frmValues['pret_ach']) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Introduceti pret achizitie!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
            
    if ($frmValues['adaos_unit'] < 0 && $nir -> obj -> tip_nir = "marfa") {
        $objResponse = new xajaxResponse();
        $objResponse -> alert('Adaos unitar negativ');
        return $objResponse;
    }
    $componenta = new NiruriComponente($mysql);
    $componenta -> adaugaComponenta($frmValues, $nir_id);
    $objResponse = new xajaxResponse();
    $objResponse = close_window($window);
    $objResponse -> assign("comp_".$componenta -> obj -> nir_componenta_id, "innerHTML", $componenta -> frmComponenta(true));
    $objResponse -> script("xajax.$('btnComponente').focus()");
    return $objResponse;
}

function stergeComponenta($nir_componenta_id, $window)
{
    global $mysql;
    $componenta = new NiruriComponente($mysql, $nir_componenta_id);
    $objResponse = close_window($window);
    $objResponse -> assign("comp_".$componenta -> obj -> nir_componenta_id, "innerHTML", "");
    $objResponse -> assign("comp_".$componenta -> obj -> nir_componenta_id, "style.display", "none");
    $componenta -> delete();
    $objResponse -> script("xajax.$('btnComponente').focus()");
    return $objResponse;
}

function editComponenta($nir_componenta_id)
{
    global $mysql;
    $comp = new NiruriComponente($mysql, $nir_componenta_id);
    $windowName = "componenta".time();
    $out = '
	<form action="" method="post" name="frmCautaProdus" id="frmCautaProdus" onSubmit="return false;">
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="15%"><strong>PRODUS</strong></td>
    <td colspan="3"><input name="denumire" type="text" id="denumire" size="50" readonly value="'. $comp -> obj -> denumire .'">
    <input name="produs_id" type="hidden" id="produs_id" value="'. $comp -> obj -> produs_id .'">
	<input name="nir_componenta_id" type="hidden" id="nir_componenta_id" value="'. $comp -> obj -> nir_componenta_id .'">	</td>
  </tr>
  <tr>
    <td><strong>Pret unitar</strong></td>
    <td width="29%"><input name="pret_ach" type="text" id="pret_ach" value="'. $comp -> obj -> pret_ach .'" onKeyUp="if(event.keyCode == 13) {xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_ach\'); fn_focus(\'cant\');}" onFocus="this.select();" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_ach\');">
	<input name="Button" type="button" class="tn_btnCalc" value=" " onClick="xajax_calculatorPachete();">
	</td>
    <td width="22%"><strong>Pret vanzare</strong></td>
    <td width="34%"><input name="pret_vanzare" type="text" id="pret_vanzare" value="'. $comp -> obj -> pret_vanzare .'" onKeyUp="if(event.keyCode == 13) xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_vanzare\');" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_vanzare\');"></td>
  </tr>
  <tr>
    <td><strong>Cantitate</strong></td>
    <td><input name="cant" type="text" id="cant" value="'. $comp -> obj -> cant .'" onFocus="this.select();" onKeyUp="if(event.keyCode == 13) {xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'cant\'); fn_focus(\'btnAdd\');}" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'cant\');"></td>
    <td><strong>Adaos unitar</strong></td>
    <td><input name="adaos_unit" type="text" id="adaos_unit" value="'. $comp -> obj -> adaos_unit .'" onKeyUp="if(event.keyCode == 13) xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_unit\');" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_unit\');"></td>
  </tr>
  <tr>
    <td><strong>UM</strong></td>
    <td>';
    $frm = new Forms();
                $UnitatiMasura = new UnitatiMasura($mysql);
            $UnitatiMasura -> find(array("ORDER BY unitate_masura ASC"));
    if (isset($UnitatiMasura-> objects)) {
        foreach ($UnitatiMasura -> objects as $objUm) {
            $options[$objUm -> unitate_masura_id]= $objUm -> unitate_masura;
        }
    }
    $out .= $frm -> input("unitate_masura_id", array("options" => $options, "selected" => $comp -> obj -> unitate_masura_id));
    $out .= '</td>
    <td><strong>Adaos %</strong></td>
    <td><input name="adaos_proc" type="text" id="adaos_proc" onKeyUp="if(event.keyCode == 13) xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_proc\');" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_proc\');"></td>
 	 </tr>
  <tr>
    <td><strong>Tva Achizitie</strong></td>
    <td><input name="tva_ach" type="text" id="tva_ach" value="'. $comp -> obj -> tva_ach .'"></td>
    <td><strong>Tva Vanzare</strong></td>
    <td><input name="tva_vanzare" type="text" id="tva_vanzare" value="'. $comp -> obj -> tva_vanzare .'"></td>
  </tr>
  <tr>
    <td><strong>Total Tva Ach.</strong></td>
    <td><input name="total_tva_ach" type="text" id="total_tva_ach" value="'. $comp -> obj -> total_tva_ach .'"></td>
    <td><strong>Total Tva Vanz.</strong></td>
    <td><input name="total_tva_vanzare" type="text" id="total_tva_vanzare" value="'. $comp -> obj -> total_tva_vanzare .'"></td>
  </tr>
  <tr>
    <td><strong>Total Adaos</strong></td>
    <td><input name="total_adaos" type="text" id="total_adaos" value="'. $comp -> obj -> total_adaos .'"></td>
    <td><strong>Total valoare</strong></td>
    <td><input name="val_total" type="text" id="val_total" value="'. $comp -> obj -> val_total .'"></td>
  </tr>
  <tr>
    <td><strong>Val Ach.</strong></td>
    <td><input name="val_ach" type="text" id="val_ach" value="'. $comp -> obj -> val_ach .'"></td>
    <td></td>
    <td><input name="btnAdd" type="button" id="btnAdd" value="SALVEAZA" onClick="xajax_salveazaComponenta(xajax.getFormValues(\'frmCautaProdus\'), document.getElementById(\'nir_id\').value, \''. $windowName .'\')"></td>
  </tr>
    <tr>
    <td><input name="btnSterge" type="button" id="btnSterge" value="Sterge" onClick="xajax_stergeComponenta('. $comp -> obj -> nir_componenta_id .', \''. $windowName .'\')"></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
	</table>
	</form>
	';
    $objResponse = window($windowName, $out, 700, 600);
    return $objResponse;
}

function searchCodBare($cod)
{
    global $mysql;
    global $html;
    $Produse = new Produse($mysql);
    $Produse -> findBy("cod_bare", $cod);
    if (!empty($Produse -> obj)) {
        $objResponse = selectProdus($Produse -> obj -> produs_id);
    } else {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Cod neidentificat!<h3>', "400px", "150px", "350px", "250px", "OK");
    }
    return $objResponse;
}

function componente($nir_id = null)
{
    global $mysql;
    global $html;
    $windowName = "componenta".time();
    $out = '
	<form action="" method="post" name="frmCautaProdus" id="frmCautaProdus" onSubmit="return false;">
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="15%"><strong>PRODUS</strong></td>
    <td colspan="3"><input name="denumire" type="text" id="denumire" size="50" onKeyUp="if(event.keyCode == 13) { if(this.value == \'\') {xajax_cautaProdus();} else {xajax_cautaProdus(this.value);}}" onFocus="this.value = \'\';xajax.$(\'produs_id\').value=\'\';this.style.backgroundColor=\'#CCCCCC\';this.select();" onDblClick="xajax_cautaProdus();" tabindex="1" onBlur="this.style.backgroundColor=\'#FFFFFF\'">
    <input name="produs_id" type="hidden" id="produs_id">
	<input name="addProdus" type="button" value="PRODUS NOU"  id="addProdus" onClick="xajax_frmProdus(document.getElementById(\'produs_id\').value);">
	</td>
  </tr>
  <tr>
    <td width="15%"><strong>COD BARE</strong></td>
    <td colspan="3"><input name="cod_bare" tabindex="2" type="text" id="cod_bare" size="50">
	</td>
  </tr>
   <tr>
    <td width="15%"><strong>Departament</strong></td>
    <td colspan="3">';
        $Categorii = new Categorii($mysql);
    $Categorii -> find(array("ORDER BY", "denumire_categorie", "ASC"));
    if (isset($Categorii -> objects)) {
        $cat = "";
        foreach ($Categorii -> objects as $objCategorie) {
            $html -> append(
                $cat,
                sprintf($html -> tags['option'], $html -> tagElements(array("value"=> $objCategorie -> categorie_id, $selected)), $objCategorie -> denumire_categorie)
            );
        }
    }
    
    $out .= '<select tabindex="3" id="categorie_id" name="categorie_id">'.$cat.'</select></td>
  </tr>
  <tr>
    <td><strong>Pret unitar</strong></td>
    <td width="29%"><input name="pret_ach" tabindex="4" type="text" id="pret_ach" value="0.00" onKeyUp="if(event.keyCode == 13) {xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_ach\'); fn_focus(\'cant\');}" onFocus="this.select();" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_ach\');">
	<input name="Button" type="button" class="tn_btnCalc" value=" " onClick="xajax_calculatorPachete();" onKeyUp="if(event.keyCode == 13) {xajax_calculatorPachete();return false;}">
	</td>
    <td width="22%"><strong>Pret vanzare</strong></td>
    <td width="34%"><input name="pret_vanzare" type="text" id="pret_vanzare" onKeyUp="if(event.keyCode == 13) {xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_vanzare\');fn_focus(\'btnAdd\');}" onFocus="this.select();" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'pret_vanzare\');"></td>
  </tr>
  <tr>
    <td><strong>Cantitate</strong></td>
    <td><input name="cant" type="text" id="cant" TABINDEX="5" value="0.00" onFocus="this.select();" onKeyUp="if(event.keyCode == 13) {xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'cant\'); fn_focus(\'adaos_proc\');}" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'cant\');"></td>
    <td><strong>Adaos unitar</strong></td>
    <td><input name="adaos_unit" type="text" id="adaos_unit" onKeyUp="if(event.keyCode == 13) xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_unit\');" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_unit\');"></td>
  </tr>
  <tr>
    <td><strong>UM</strong></td>
    <td>';
    $frm = new Forms();
                $UnitatiMasura = new UnitatiMasura($mysql);
            $UnitatiMasura -> find(array("ORDER BY unitate_masura ASC"));
    if (isset($UnitatiMasura-> objects)) {
        foreach ($UnitatiMasura -> objects as $objUm) {
            $options[$objUm -> unitate_masura_id]= $objUm -> unitate_masura;
        }
    }
    $out .= $frm -> input("unitate_masura_id", array("options" => $options));
    $out .= '</td>
    <td><strong>Adaos %</strong></td>
    <td><input name="adaos_proc" type="text" id="adaos_proc" onKeyUp="if(event.keyCode == 13){ xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_proc\');fn_focus(\'pret_vanzare\');}" onFocus="this.select();" onChange="xajax_calculeaza(xajax.getFormValues(\'frmCautaProdus\'), \'adaos_proc\');"></td>
 	 </tr>
  <tr>
    <td><strong>Tva Achizitie</strong></td>
    <td><input name="tva_ach" type="text" id="tva_ach" value="0.00"></td>
    <td><strong>Tva Vanzare</strong></td>
    <td><input name="tva_vanzare" type="text" id="tva_vanzare" value="0.00"></td>
  </tr>
  <tr>
    <td><strong>Total Tva Ach.</strong></td>
    <td><input name="total_tva_ach" type="text" id="total_tva_ach" value="0.00"></td>
    <td><strong>Total Tva Vanz.</strong></td>
    <td><input name="total_tva_vanzare" type="text" id="total_tva_vanzare" value="0.00"></td>
  </tr>
  <tr>
    <td><strong>Total Adaos</strong></td>
    <td><input name="total_adaos" type="text" id="total_adaos" value="0.00"></td>
    <td><strong>Total valoare</strong></td>
    <td><input name="val_total" type="text" id="val_total" value="0.00"></td>
  </tr>
  <tr>
    <td><strong>Val Ach.</strong></td>
    <td><input name="val_ach" type="text" id="val_ach" value="0.00"></td>
    <td></td>
    <td><input name="btnAdd" type="button" id="btnAdd" value="ADAUGA" onClick="xajax_adaugaComponenta(xajax.getFormValues(\'frmCautaProdus\'), document.getElementById(\'nir_id\').value, \''. $windowName .'\')"></td>
  </tr>
	</table>
	</form>
	';
    $objResponse = window($windowName, $out, 700, 600);
    $objResponse -> script("setTimeout(\"xajax.$('denumire').focus()\",100);");
    $objResponse -> script("setTimeout(\"xajax.$('denumire').focus()\",100);");
    return $objResponse;
}
    
function cautaProdus($filtre = "")
{
    global $mysql;
    global $html;
    
    $produse = new Produse($mysql);
    if ($filtre) {
        $produse -> findBy('cod_bare', $filtre);
        if ($produse -> obj) {
            $objResponse = selectProdus($produse -> obj -> produs_id);
            return $objResponse;
        }
        $f[] = "where denumire like '%$filtre%' and la_vanzare = 'DA'";
    }
    $f[] = "order by denumire asc";
    $nr_r = $produse -> find($f);
    $out = '';
    $windowName = "cautaProdus".time();
    if ($nr_r) {
        $select = '<select name="cauta_produs_id" id="cauta_produs_id"  size="20" multiple style="width:300px;" onChange="fn_loadProdus(this.options[this.selectedIndex].text, this.options[this.selectedIndex].title)"  accesskey="b" onKeyUp="if(event.keyCode == 13) {xajax_selectProdus(this.options[this.selectedIndex].value); xajax_close_window(\''. $windowName .'\')}" onDblClick="xajax_selectProdus(this.options[this.selectedIndex].value); xajax_close_window(\''. $windowName .'\')">';
        $html -> append($out, $select);
        foreach ($produse -> objects as $obj) {
            if ($obj -> la_vanzare == 'NU') {
                $class = "rowred";
            } else {
                $class = "";
            }
                
            $html -> append($out, '<option value="'. $obj -> produs_id .'" title="'. $obj -> pret .'" class="'.$class.'">'. $obj -> denumire .'</option>');
        }
        $html -> append($out, '</select>');
    }
    $innerHTML = '<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="47%" rowspan="4" valign="top">'. $out .'</td>
    <td width="23%"><strong>PRODUS</strong></td>
    <td width="30%"><div id="div_denumire"></div></td>
  </tr>
  <tr>
    <td><strong>PRET VANZARE </strong></td>
    <td><div id="div_pret"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
';
    $objResponse = window($windowName, $innerHTML, 800, 400);
    $objResponse -> script("xajax.$('cauta_produs_id').focus();");
    $objResponse -> script("xajax.$('cauta_produs_id').selectedIndex=0;");
    return $objResponse;
}

function selectProdus($produs_id)
{
    global $mysql;
    global $html;
    $produs = new Produse($mysql, $produs_id);
    $objResponse = new xajaxResponse();
    $objResponse -> assign("produs_id", "value", $produs -> obj -> produs_id);
    $objResponse -> assign("denumire", "value", $produs -> obj -> denumire);
    $objResponse -> assign("pret_vanzare", "value", $produs -> obj -> pret);
    $objResponse -> assign("tva_vanzare", "value", number_format($produs -> obj -> pret*24/124, 2, '.', ''));
    $objResponse -> script("xajax.$('pret_ach').focus();xajax.$('pret_ach').select();");
    return $objResponse;
}

function calculeaza($frmValues, $mod)
{
    global $mysql;
    $objResponse = new xajaxResponse();
    switch ($mod) {
        case "adaos_unit":
            {
            $adaos_proc = ($frmValues['adaos_unit']/$frmValues['pret_ach'])*100;
            $pret_vanzare = $frmValues['pret_ach'] + $frmValues['adaos_unit'];
            $pret_vanzare = $pret_vanzare + $pret_vanzare*0.24;
            $objResponse -> assign("pret_vanzare", "value", number_format($pret_vanzare, 2, '.', ''));
            $objResponse -> assign("adaos_proc", "value", number_format($adaos_proc, 2, '.', ''));
            $tva_vanzare = $pret_vanzare*24/124;
            $frmValues['pret_vanzare'] = $pret_vanzare;
        }break;
        case "pret_ach":
            {
            $pret_vanzare_tva = $frmValues['pret_vanzare'] - $frmValues['pret_vanzare']*24/124;
            $adaos_unit = $pret_vanzare_tva - $frmValues['pret_ach'];
            $adaos_proc =($adaos_unit/$frmValues['pret_ach'])*100;
            $objResponse -> assign("adaos_unit", "value", number_format($adaos_unit, 2, '.', ''));
            $objResponse -> assign("adaos_proc", "value", number_format($adaos_proc, 2, '.', ''));
            $tva_vanzare = $frmValues['pret_vanzare']*24/124;
            $frmValues['adaos_unit'] = $adaos_unit;
        }break;
        case "pret_vanzare":
            {
            $pret_vanzare_tva = $frmValues['pret_vanzare'] - $frmValues['pret_vanzare']*24/124;
            $adaos_unit = $pret_vanzare_tva - $frmValues['pret_ach'];
            $adaos_proc = ($adaos_unit/$frmValues['pret_ach'])*100;
            $objResponse -> assign("adaos_unit", "value", number_format($adaos_unit, 2, '.', ''));
            $objResponse -> assign("adaos_proc", "value", number_format($adaos_proc, 2, '.', ''));
            $tva_vanzare = $frmValues['pret_vanzare']*24/124;
            $frmValues['adaos_unit'] = $adaos_unit;
        }break;
        
        case "cant":
            {
            $tva_vanzare = $frmValues['pret_vanzare']*24/124;
        }break;
        case "adaos_proc":
            {
            $adaos_unit = ($frmValues['adaos_proc']*$frmValues['pret_ach'])/100;
            $pret_vanzare = $frmValues['pret_ach'] + $frmValues['pret_ach']*$frmValues['adaos_proc']/100;
            $pret_vanzare = $pret_vanzare*1.24;
            $objResponse -> assign("pret_vanzare", "value", number_format($pret_vanzare, 2, '.', ''));
            $objResponse -> assign("adaos_unit", "value", number_format($adaos_unit, 2, '.', ''));
            $tva_vanzare = $pret_vanzare*24/124;
            $frmValues['pret_vanzare'] = $pret_vanzare;
            $frmValues['adaos_unit'] = $adaos_unit;
        }break;
    }
    $tva_ach = $frmValues['pret_ach']*0.24;
    $total_tva_ach = $frmValues['cant']*$tva_ach;
    $total_tva_vanzare = $frmValues['cant']*$tva_vanzare;
    $total_adaos = $frmValues['adaos_unit']*$frmValues['cant'];
    $val_total = $frmValues['pret_vanzare']*$frmValues['cant'];
    $val_ach = $frmValues['pret_ach']*$frmValues['cant'];
    
    $objResponse -> assign("tva_ach", "value", number_format($tva_ach, 2, '.', ''));
    $objResponse -> assign("tva_vanzare", "value", number_format($tva_vanzare, 2, '.', ''));
    $objResponse -> assign("total_tva_ach", "value", number_format($total_tva_ach, 2, '.', ''));
    $objResponse -> assign("total_tva_vanzare", "value", number_format($total_tva_vanzare, 2, '.', ''));
    $objResponse -> assign("total_adaos", "value", number_format($total_adaos, 2, '.', ''));
    $objResponse -> assign("val_total", "value", number_format($val_total, 2, '.', ''));
    $objResponse -> assign("val_ach", "value", number_format($val_ach, 2, '.', ''));
    return $objResponse;
}

function calculatorPachete()
{
    $name = time();
    $txt = '
	<form id="frmPachete" name="frmPachete">
	<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="189"><strong>Numar pachete </strong></td>
    <td width="211"><div align="center">
      <input name="nr_pachete" type="text" onFocus="this.select()" id="nr_pachete" onKeyPress="if(event.keyCode == 13) {fn_focus(\'bucati_pachet\');}">
    </div></td>
  </tr>
  <tr>
    <td><strong>Nr. buc/pachet </strong></td>
    <td><div align="center">
      <input name="bucati_pachet" type="text" id="bucati_pachet" onKeyUp="if(event.keyCode == 13) { fn_focus(\'pret_pachet\');}">
    </div></td>
  </tr>
  <tr>
    <td><strong>Pret pachet </strong></td>
    <td><div align="center">
      <input name="pret_pachet" type="text" id="pret_pachet" onKeyUp="if(event.keyCode == 13) {xajax_calculeazaPachete(xajax.getFormValues(\'frmPachete\'), \''.$name.'\');fn_focus(\'cant\');}">
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="right">
      <input name="btnCalculator" type="button" id="btnCalculator" value="Ok" onClick="xajax_calculeazaPachete(xajax.getFormValues(\'frmPachete\'), \''.$name.'\')">
    </div></td>
  </tr>
</table>
</form>
	';
    
    $objResponse = window($name, $txt, 420, 200);
    $objResponse -> script("xajax.$('nr_pachete').focus();");
    return $objResponse;
}

function calculeazaPachete($frmValues, $window)
{
    $cantitate = $frmValues['nr_pachete']*$frmValues['bucati_pachet'];
    $pret_unitar = $frmValues['pret_pachet']/$frmValues['bucati_pachet'];
    $objResponse = close_window($window);
    $objResponse -> assign("cant", "value", $cantitate);
    $objResponse -> assign("pret_ach", "value", number_format($pret_unitar, 3, '.', ''));
    $objResponse -> script("xajax_calculeaza(xajax.getFormValues(frmCautaProdus), 'pret_ach')");
    $objResponse -> script("document.getElementById('btnAdd').focus()");
    return $objResponse;
}
function frmProdus($produs_id)
{
    global $mysql;
    global $html;
    include("views/config.produse/frm.produse.php");
    $innerHTML = '';
    if ($produs_id == 0) {
        $Produs = new Produse($mysql);
        $html -> replace($frmProdus, '<%titlu%>', 'Adauga produs');
    } else {
        $Produs = new Produse($mysql, $produs_id);
        $html -> replace($frmProdus, '<%titlu%>', 'Editare produs');
    }
    $Categorii = new Categorii($mysql);
    $Categorii -> find(array("ORDER BY", "denumire_categorie", "ASC"));
    if (isset($Categorii -> objects)) {
        $cat = "";
        foreach ($Categorii -> objects as $objCategorie) {
            if ($objCategorie -> categorie_id == $Produs -> obj -> categorie_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $html -> append(
                $cat,
                sprintf($html -> tags['option'], $html -> tagElements(array("value"=> $objCategorie -> categorie_id, $selected)), $objCategorie -> denumire_categorie)
            );
        }
        $html -> replace($frmProdus, '<%categorie_id%>', $cat);
    }
    if ($Produs -> obj -> la_vanzare == 'NU') {
        $html -> replace($frmProdus, '<%la_vanzare%>', '<option value="DA">DA</option><option value="NU" selected>NU</option>');
    } else {
        $html -> replace($frmProdus, '<%la_vanzare%>', '<option value="DA" selected>DA</option><option value="NU">NU</option>');
    }
    
    switch ($Produs -> obj -> tip_produs) {
        case "marfa":
        {
            $html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa" selected>Marfa</option><option value="reteta">Reteta</option>
		<option value="mp">Materie prima</option>
		<option value="serviciu">Serviciu</option>');
        }break;
        case "reteta":
        {
                $html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa">Marfa</option><option value="reteta" selected>Reteta</option>
		<option value="mp">Materie prima</option>
		<option value="serviciu">Serviciu</option>');

        }break;
        case "mp":
        {
                $html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa">Marfa</option><option value="reteta">Reteta</option>
		<option value="mp" selected>Materie prima</option>
		<option value="serviciu">Serviciu</option>');

        }break;
        case "serviciu":
        {
                $html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa" selected>Marfa</option><option value="reteta">Reteta</option>
		<option value="mo">Materie prima</option>
		<option value="serviciu" selected>Serviciu</option>');

        }break;
        default:
        {
                $html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa" selected>Marfa</option><option value="reteta">Reteta</option>
		<option value="mp">Materie prima</option>
		<option value="serviciu">Serviciu</option>');

        }break;
    }
        
    $Produs -> frmReplace($frmProdus, $html);
    $html -> append($innerHTML, $frmProdus);
    if ($produs_id != 0) {
        $html -> append($innerHTML, '<input type="button" value="STERGE PRODUS" onClick="r = confirm(\'Sigur sterg produs?\'); if(r) xajax_stergeProdus('. $produs_id .')">');
    }
    if ($produs_id != 0 && $Produs -> obj -> tip_produs == "reteta") {
        $html -> append($innerHTML, '<input type="button" value="RETETAR" onClick="xajax_retetar('. $produs_id .')">');
    }
    $objResponse = afisareDialog($innerHTML, "600px", "450px", "210px", "80px", null);
    $objResponse -> script("document.getElementById('denumire').focus();");
    return $objResponse;
}

function frmSave($frmValues)
{
    global $mysql;
    global $html;
    global $cfgStocuri;
    $Produs = new Produse($mysql);
    $nr_r = $Produs -> findAllBy("cod_bare", $frmValues['cod_bare']);
    if ($nr_r != 0) {
        if (!$frmValues['produs_id'] && $nr_r==1) {
            $objResponse = new xajaxResponse();
            $objResponse -> alert('Cod bare existent la produsul:'.$Produs -> objects[0] -> denumire);
            return $objResponse;
        }
    }
        
    if ($frmValues['produs_id'] && $cfgStocuri['modificari_pret']) {
        if ($frmValues['pret'] <> $Produs -> obj -> pret) {
            $mp = new ModificariPret($mysql);
            $mp -> obj -> produs_id = $frmValues['produs_id'];
            $mp -> obj -> pret_vechi = $Produs -> obj -> pret;
            $mp -> obj -> pret_nou = $frmValues['pret'];
            $frmValues['pret'] = $Produs -> obj -> pret;
            $mp -> obj -> data_modificare = date("Y-m-d H:i:s");
            $mp -> obj -> user_id = $_SESSION['USERID'];
            $mp -> obj -> stoc = $Produs -> getStoc();
            $mp -> save();
        }
    }
        
    $Produs -> frmGetValues($frmValues);
    $Produs -> save();
    $objResponse = new xajaxResponse();
    $objResponse -> script("xajax_btnRenuntaDialog();");
    $objResponse -> script("xajax_selectProdus(".$Produs -> obj -> produs_id.")");
    return $objResponse;
}

function inchideNir($frmNir)
{
    global $mysql;
    $nir = new Niruri($mysql, $frmNir['nir_id']);
    if ($nir -> saveEditNir($frmNir, $message)) {
        $nir -> stergeContinut();
        $nir -> genereazaLoturi();
        $nr_r = $nir -> salveazaPreturiVanzare();
        if ($nr_r) {
            $info = '<h3 style="color:red">Au aparut modificari la preturile de vanzare. Activati modificarile de pret!</h3>';
        }
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div>
		'. $info .'
		<h3 align="center">Am salvat nirul '. $nir -> obj -> numar_nir .'<h3>
		<div align="center"><input type="button" value="OK" class="btnTouch" onClick="window.location.href = \'nir.php?nir_id='.$nir -> obj -> nir_id.'\'"></div>', "400px", "250px", "350px", "250px", false);
    } else {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">'. $message .'<h3>', "400px", "150px", "350px", "250px", "OK");
    }
    return $objResponse;
}
function verificare($frmNir, $frmComponente)
{
    $message = null;
    global $mysql;
    $nir = new Niruri($mysql);
    if (!$nir -> verificare($frmNir, $message)) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">'. $message .'<h3>', "400px", "150px", "350px", "250px", "OK");
    } else {
        if (!empty($frmComponente['data'])) {
            $total_fara_tva = $nir -> calculeazaTotalComponente($frmComponente, 'val_ach');
            $total_tva = $nir -> calculeazaTotalComponente($frmComponente, 'total_tva_ach');
            $r1 = ($frmNir['total_fara_tva'] == $total_fara_tva) ? 'OK' : 'X';
            $r2 = ($frmNir['total_tva'] == $total_tva) ? 'OK' : 'X';
            $raspuns = '
		<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <th scope="col">&nbsp;</th>
    <th scope="col">Introdus</th>
    <th scope="col">Calculat</th>
    <th scope="col">Verificare</th>
  </tr>
  <tr>
  <th scope="col">Valoare Achizitie</th>
    <td>'. $frmNir['total_fara_tva'] .'</td>
    <td>'. $total_fara_tva .'</td>
    <td>'. $r1 .'</td>
  </tr>
  <tr>
  <th scope="col">Total Tva</th>
    <td>'. $frmNir['total_tva'] .'</td>
    <td>'. $total_tva .'</td>
    <td>'. $r2 .'</td>
  </tr>
</table>';
            $objResponse = afisareDialog('<div id="divwindowhead">Info!</div>'. $raspuns .'', "400px", "150px", "350px", "250px", "OK");
        } else {
            $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu s-au introdus componente!<h3>', "400px", "150px", "350px", "250px", "OK");
        }
    }
    return $objResponse;
}
    
function stergeIesire($nir_id)
{
    global $mysql;
    $mysql -> query("delete from niruri_componente where nir_id = '$nir_id'");
    $mysql -> query("delete from niruri where nir_id = '$nir_id'");
    $mysql -> query("delete from intrari_continut where nir_id = '$nir_id' and tip='nir'");
    $objResponse = new xajaxResponse();
    $objResponse -> script("window.location.href = 'evidenta.niruri.php'");
    return $objResponse;
}
    
$xajax->processRequest();
