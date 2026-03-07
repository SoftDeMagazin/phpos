<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("etichete.common.php");

$mysql = new MySQL();
$html = new Html;

function onLoad($eticheta_id)
{
    global $mysql;
    global $html;
    $intrare = new Etichete($mysql, $eticheta_id);
        $objResponse = new xajaxResponse();
        $objResponse -> assign("eticheta_id", "value", $intrare -> obj -> eticheta_id);
        $objResponse -> assign("div_numar", "innerHTML", $intrare -> obj -> eticheta_id);
        $objResponse -> assign("stanga", "value", $intrare -> obj -> stanga);
        $objResponse -> assign("dreapta", "value", $intrare -> obj -> dreapta);
        $objResponse -> assign("sus", "value", $intrare -> obj -> sus);
        $objResponse -> assign("jos", "value", $intrare -> obj -> jos);
        $objResponse -> assign("numar_coloane", "value", $intrare -> obj -> numar_coloane);
        $objResponse -> assign("inaltime_eticheta", "value", $intrare -> obj -> inaltime_eticheta);
        $objResponse -> assign("etichete_continut", "innerHTML", $intrare -> continut());
    return $objResponse;
}

function salveazaAntet($frmValues)
{
    global $mysql;
    global $html;
    $frmValues['data'] = date("Y-m-d H:i:s");
    $eticheta = new Etichete($mysql);
    $eticheta -> tableToForm();
    $eticheta -> saveForm($frmValues);
        $objResponse = new xajaxResponse();
        $objResponse -> assign("eticheta_id", "value", $eticheta -> obj -> eticheta_id);
        $objResponse -> assign("div_numar", "innerHTML", $eticheta -> obj -> eticheta_id);
    return $objResponse;
}

function adaugaComponenta($frmValues, $eticheta_id, $window)
{
    global $mysql;
    if (empty($eticheta_id)) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Salvati antet!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    $eticheta = new Etichete($mysql, $eticheta_id);
    $componenta = new EticheteContinut($mysql);
    $frmValues['eticheta_id'] = $eticheta_id;
    $Produs = new Produse($mysql, $frmValues['produs_id']);
    $frmValues['cod'] = $Produs -> obj -> cod_bare;
    $frmValues['pret'] = $Produs -> obj -> pret;
    $componenta -> tableToForm();
    $componenta -> saveForm($frmValues);
    $objResponse = new xajaxResponse();
    $objResponse = close_window($window);
    $objResponse -> assign("etichete_continut", "innerHTML", $eticheta -> continut());
    $objResponse -> script("xajax.$('btnComponente').focus()");
    return $objResponse;
}

function salveazaComponenta($frmValues, $nir_id, $window)
{
    global $mysql;
    if (empty($nir_id)) {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Salvati antet nir!<h3>', "400px", "150px", "350px", "250px", "OK");
        return $objResponse;
    }
    $componenta = new NiruriComponente($mysql);
    $componenta -> adaugaComponenta($frmValues, $nir_id);
    $objResponse = new xajaxResponse();
    $objResponse = close_window($window);
    $objResponse -> assign("etichete_continut", "innerHTML", $eticheta -> continut());
    $objResponse -> script("xajax.$('btnComponente').focus()");
    return $objResponse;
}

function editComponenta($eticheta_continut_id)
{
    global $mysql;
    $comp = new EticheteContinut($mysql, $eticheta_continut_id);
    $windowName = "componenta".time();
    $out = '
	<form action="" method="post" name="frmCautaProdus" id="frmCautaProdus" onSubmit="return false;">
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="15%"><strong>PRODUS</strong></td>
    <td colspan="3"><input name="denumire" type="text" id="denumire" size="50" readonly value="'. $comp -> obj -> denumire .'">
    <input name="produs_id" type="hidden" id="produs_id" value="'. $comp -> obj -> produs_id .'">
	<input name="eticheta_continut_id" type="hidden" id="eticheta_continut_id" value="'. $comp -> obj -> eticheta_continut_id .'">	</td>
  </tr>
  <tr>
    <td><strong>Numar etichete</strong></td>
    <td width="29%"><input name="numar_etichete" type="text" id="numar_etichete" value="'. $comp -> obj -> numar_etichete .'" onKeyUp="if(event.keyCode == 13) { fn_focus(\'btnAdd\');}" onFocus="this.select();" >
	</td>
    <td width="22%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
  </tr>
  <tr>
    <td><input name="btnAdd" type="button" id="btnSterge" value="STERGE" onClick="xajax_stergeComponenta('. $comp -> obj -> eticheta_continut_id .', \''. $windowName .'\')"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="btnAdd" type="button" id="btnAdd" value="SALVEAZA" onClick="xajax_adaugaComponenta(xajax.getFormValues(\'frmCautaProdus\'), document.getElementById(\'eticheta_id\').value, \''. $windowName .'\')"></td>
  </tr>
	</table>
	</form>
	';
    $objResponse = window($windowName, $out, 700, 600);
    return $objResponse;
}

function stergeComponenta($eticheta_continut_id, $windowName)
{
    global $mysql;
    $continut = new EticheteContinut($mysql, $eticheta_continut_id);
    $eticheta = new Etichete($mysql, $continut -> obj -> eticheta_id);
    $continut -> delete();
    $objResponse = close_window($windowName);
    $objResponse -> assign("etichete_continut", "innerHTML", $eticheta -> continut());
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
    $windowName = "componenta".time();
    $out = '
	<form action="" method="post" name="frmCautaProdus" id="frmCautaProdus" onSubmit="return false;">
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="15%"><strong>PRODUS</strong></td>
    <td colspan="3"><input name="denumire" type="text" id="denumire" size="50" onKeyUp="if(event.keyCode == 13) { if(this.value == \'\') {xajax_cautaProdus();} else {xajax_searchCodBare(this.value);}}" onFocus="this.value = \'\';xajax.$(\'produs_id\').value=\'\';this.style.backgroundColor=\'#CCCCCC\';this.select();" onBlur="this.style.backgroundColor=\'#FFFFFF\'">
    <input name="produs_id" type="hidden" id="produs_id">
	<input name="addProdus" type="button" value="PRODUS NOU"  id="addProdus" onClick="xajax_frmProdus(0);">
	</td>
  </tr>
  <tr>
    <td><strong>Numar etichete</strong></td>
    <td width="29%"><input name="numar_etichete" type="text" id="numar_etichete" value="1" onKeyUp="if(event.keyCode == 13) { fn_focus(\'btnAdd\');}" onFocus="this.select();" >
	</td>
    <td width="22%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="btnAdd" type="button" id="btnAdd" value="ADAUGA" onClick="xajax_adaugaComponenta(xajax.getFormValues(\'frmCautaProdus\'), document.getElementById(\'eticheta_id\').value, \''. $windowName .'\')"></td>
  </tr>
	</table>
	</form>
	';
    $objResponse = window($windowName, $out, 700, 600);
    $objResponse -> script("setTimeout(\"xajax.$('denumire').focus()\",100);");
    $objResponse -> script("setTimeout(\"xajax.$('denumire').focus()\",100);");
    return $objResponse;
}
    
function cautaProdus()
{
    global $mysql;
    global $html;
    $produse = new Produse($mysql);
    $nr_r = $produse -> find(array("order by denumire asc"));
    $out = '';
    $windowName = "cautaProdus".time();
    if ($nr_r) {
        $select = '<select name="cauta_produs_id" id="cauta_produs_id"  size="20" multiple style="width:300px;" onChange="fn_loadProdus(this.options[this.selectedIndex].text, this.options[this.selectedIndex].title)"  accesskey="b" onKeyPress="if(event.keyCode == 13) {xajax_selectProdus(this.options[this.selectedIndex].value); xajax_close_window(\''. $windowName .'\')}">';
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
    $objResponse -> script("xajax.$('numar_etichete').focus();xajax.$('numar_etichete').select();");
    return $objResponse;
}

function calculeaza($frmValues, $mod)
{
    global $mysql;
    $objResponse = new xajaxResponse();
    switch ($mod) {
        case "adaos_unit":
            {
            $adaos_proc = number_format(($frmValues['adaos_unit']/$frmValues['pret_ach'])*100, 2, '.', '');
            $pret_vanzare = number_format($frmValues['pret_ach'] + $frmValues['pret_ach']*$adaos_proc/100, 2, '.', '');
            $pret_vanzare = number_format($pret_vanzare*1.24, 2, '.', '');
            $objResponse -> assign("pret_vanzare", "value", $pret_vanzare);
            $objResponse -> assign("adaos_proc", "value", $adaos_proc);
            $tva_vanzare = number_format($pret_vanzare*24/124, 2, '.', '');
            $frmValues['pret_vanzare'] = $pret_vanzare;
        }break;
        case "pret_ach":
            {
            $pret_vanzare_tva = number_format($frmValues['pret_vanzare']*100/124, 2, '.', '');
            $adaos_unit = $pret_vanzare_tva - $frmValues['pret_ach'];
            $adaos_proc = number_format(($adaos_unit/$frmValues['pret_ach'])*100, 2, '.', '');
            $objResponse -> assign("adaos_unit", "value", $adaos_unit);
            $objResponse -> assign("adaos_proc", "value", $adaos_proc);
            $tva_vanzare = number_format($frmValues['pret_vanzare']*24/124, 2, '.', '');
            $frmValues['adaos_unit'] = $adaos_unit;
        }break;
        case "pret_vanzare":
            {
            $pret_vanzare_tva = number_format($frmValues['pret_vanzare']*100/124, 2, '.', '');
            $adaos_unit = $pret_vanzare_tva - $frmValues['pret_ach'];
            $adaos_proc = number_format(($adaos_unit/$frmValues['pret_ach'])*100, 2, '.', '');
            $objResponse -> assign("adaos_unit", "value", $adaos_unit);
            $objResponse -> assign("adaos_proc", "value", $adaos_proc);
            $tva_vanzare = number_format($frmValues['pret_vanzare']*24/124, 2, '.', '');
            $frmValues['adaos_unit'] = $adaos_unit;
        }break;
        
        case "cant":
            {
            $tva_vanzare = number_format($frmValues['pret_vanzare']*24/124, 2, '.', '');
        }break;
        case "adaos_proc":
            {
            $adaos_unit = number_format(($frmValues['adaos_proc']*$frmValues['pret_ach'])/100, 2, '.', '');
            $pret_vanzare = number_format($frmValues['pret_ach'] + $frmValues['pret_ach']*$frmValues['adaos_proc']/100, 2, '.', '');
            $pret_vanzare = number_format($pret_vanzare*1.24, 2, '.', '');
            $objResponse -> assign("pret_vanzare", "value", $pret_vanzare);
            $objResponse -> assign("adaos_unit", "value", $adaos_unit);
            $tva_vanzare = number_format($pret_vanzare*24/124, 2, '.', '');
            $frmValues['pret_vanzare'] = $pret_vanzare;
            $frmValues['adaos_unit'] = $adaos_unit;
        }break;
    }
    $tva_ach = number_format($frmValues['pret_ach']*0.24, 2, '.', '');
    $total_tva_ach = number_format($frmValues['cant']*$tva_ach, 2, '.', '');
    $total_tva_vanzare = number_format($frmValues['cant']*$tva_vanzare, 2, '.', '');
    $total_adaos = number_format($frmValues['adaos_unit']*$frmValues['cant'], 2, '.', '');
    $val_total = number_format($frmValues['pret_vanzare']*$frmValues['cant'], 2, '.', '');
    $val_ach = number_format($frmValues['pret_ach']*$frmValues['cant'], 2, '.', '');
    $objResponse -> assign("tva_ach", "value", $tva_ach);
    $objResponse -> assign("tva_vanzare", "value", $tva_vanzare);
    $objResponse -> assign("total_tva_ach", "value", $total_tva_ach);
    $objResponse -> assign("total_tva_vanzare", "value", $total_tva_vanzare);
    $objResponse -> assign("total_adaos", "value", $total_adaos);
    $objResponse -> assign("val_total", "value", $val_total);
    $objResponse -> assign("val_ach", "value", $val_ach);
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
      <input name="nr_pachete" type="text" id="nr_pachete" onKeyUp="if(event.keyCode == 13) {fn_focus(\'bucati_pachet\');}">
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
    $pret_unitar = number_format($frmValues['pret_pachet']/$frmValues['bucati_pachet'], 2, '.', '');
    $objResponse = close_window($window);
    $objResponse -> assign("cant", "value", $cantitate);
    $objResponse -> assign("pret_ach", "value", $pret_unitar);
    $objResponse -> script("xajax_calculeaza(xajax.getFormValues(frmCautaProdus), 'pret_ach')");
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
    $Produs -> frmReplace($frmProdus, $html);
    $html -> append($innerHTML, $frmProdus);
    $objResponse = afisareDialog($innerHTML, "600px", "300px", "210px", "180px", null);
    $objResponse -> script("document.getElementById('denumire').focus();");
    return $objResponse;
}

function frmSave($frmValues)
{
    global $mysql;
    global $html;
    $Produs = new Produse($mysql);
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
        $nir -> salveazaPreturiVanzare();
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Am salvat nirul '. $nir -> obj -> numar_nir .'<h3>
		<div align="center"><input type="button" value="OK" class="btnTouch" onClick="window.location.href = \'nir.php?nir_id='.$nir -> obj -> nir_id.'\'"></div>', "400px", "150px", "350px", "250px", false);
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
