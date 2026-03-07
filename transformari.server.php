<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("transformari.common.php");

$mysql = new MySQL();
$html = new Html;
    
function cautaProdus($filtre = "", $action = "")
{
    global $mysql;
    global $html;
    
    $produse = new Produse($mysql);
    if ($filtre) {
        $produse -> findBy('cod_bare', $filtre);
        if ($produse -> obj) {
            $objResponse = selectProdus($produse -> obj -> produs_id, $action);
            return $objResponse;
        }
        $f[] = "where denumire like '%$filtre%'";
    }
    $f[] = "order by denumire asc";
    $nr_r = $produse -> find($f);
    $out = '';
    $windowName = "cautaProdus".time();
    if ($nr_r) {
        $select = '<select name="cauta_produs_id" id="cauta_produs_id"  size="20" multiple style="width:300px;" onChange="fn_loadProdus(this.options[this.selectedIndex].text, this.options[this.selectedIndex].title)"  accesskey="b" onKeyUp="if(event.keyCode == 13) {xajax_selectProdus(this.options[this.selectedIndex].value, \''. $action .'\'); xajax_close_window(\''. $windowName .'\')}" onDblClick="xajax_selectProdus(this.options[this.selectedIndex].value,\''. $action .'\'); xajax_close_window(\''. $windowName .'\')">';
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

function selectProdus($produs_id, $action = "")
{
    global $mysql;
    global $html;
    $produs = new Produse($mysql, $produs_id);
    $objResponse = new xajaxResponse();
    $objResponse -> assign($action."_produs_id", "value", $produs -> obj -> produs_id);
    $objResponse -> assign($action."_denumire", "value", $produs -> obj -> denumire);
    
    if ($action == "sursa") {
        $objResponse -> assign($action."_cantitate", "value", $produs -> getStoc());
    } else {
        $objResponse -> assign($action."_cantitate", "value", 1);
    }
    return $objResponse;
}


function frmProdus($produs_id, $action = "")
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
    $Produs = new Produse($mysql, $frmValues['produs_id']);
    $nr_r = $Produs -> findAllBy("cod_bare", $frmValues['cod_bare']);
    if ($nr_r != 0) {
        if (!$frmValues['produs_id'] && $nr_r==1) {
            $objResponse = new xajaxResponse();
            $objResponse -> alert('Cod bare existent la produsul:'.$Produs -> objects[0] -> denumire);
            return $objResponse;
        }
    }
            
    $Produs -> frmGetValues($frmValues);
    $Produs -> save();
    $objResponse = new xajaxResponse();
    $objResponse -> script("xajax_btnRenuntaDialog();");
    $objResponse -> script("xajax_selectProdus(".$Produs -> obj -> produs_id.", 'destinatie')");
    return $objResponse;
}
    


function salveazaTransformare($frmValues)
{
    global $mysql;
    global $html;
    $transformare = new Transformari($mysql);
    $valoare_sursa = $frmValues['sursa_pret']*$frmValues['sursa_cantitate'];
    $valoare_destinatie = $frmValues['destinatie_pret']*$frmValues['destinatie_cantitate'];
    $error="";
    if (!$frmValues['data_transformare']) {
        $error .= "Introduceti data.";
    }
    
    if ($frmValues['sursa_produs_id']  == $frmValues['destinatie_produs_id']) {
        $error .= "Articol sursa nu poate fi acelasi cu articol destinatie.";
    }
        
    if (!$frmValues['sursa_produs_id']) {
        $error .= "Selectati articol sursa.";
    }
    
    if (!$frmValues['destinatie_produs_id']) {
        $error .= "Selectati articol destinatie.";
    }
    
    if (!$frmValues['sursa_cantitate'] || !$frmValues['destinatie_cantitate']) {
        $error .= "Introduceti cantitate transformata.";
    }
    
        
    $produs_sursa = new Produse($mysql, $frmValues['sursa_produs_id']);
    if ($frmValues['sursa_cantitate'] > $produs_sursa -> getStoc()) {
        $error .= "Stoc insuficient pentru transformare.";
    }
    if ($error) {
        $objResponse = new xajaxResponse();
        $objResponse -> alert($error);
        return $objResponse;
    }
    $transformare -> tableToForm();
    $transformare -> saveForm($frmValues);
    $transformare -> proceseaza();
    $objResponse = new xajaxResponse();
    $objResponse -> alert('Am salvat proces verbal transformare.');
    $objResponse -> script("window.location.href = 'transformari.php'");
    return $objResponse;
}
$xajax->processRequest();
