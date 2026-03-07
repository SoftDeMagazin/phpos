<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("inventar.common.php");

$mysql = new MySQL();
$html = new Html;

function deschideInventar($inventar_id = null)
{
    global $mysql;
    if (!$inventar_id) {
        $inventar = new Inventar($mysql);
        $inventar -> deschideInventar();
        $objResponse = new xajaxResponse();
        $objResponse -> assign("nir", "innerHTML", '<h1 align="center">Inventar nr. '.$inventar -> obj -> numar_inventar.'</h1><input type="hidden" id="inventar_id" name="inventar_id" value="'. $inventar -> obj -> inventar_id .'">');
    } else {
        $inventar = new Inventar($mysql, $inventar_id);
        $objResponse = new xajaxResponse();
        $objResponse -> assign("nir", "innerHTML", '<h1 align="center">Inventar nr. '.$inventar -> obj -> numar_inventar.'</h1><input type="hidden" id="inventar_id" name="inventar_id" value="'. $inventar -> obj -> inventar_id .'">');
        $objResponse -> assign("intrare_componente", "innerHTML", $inventar -> continut());
        if ($inventar -> obj -> calculat == "DA") {
            $objResponse -> assign("btnRecalculare", "disabled", true);
            $objResponse -> assign("btnAnuleaza", "disabled", true);
            $objResponse -> assign("btnAddComponenta", "disabled", true);
        }
    }
    return $objResponse;
}
    
function searchProduse($str)
{
    global $mysql;
    global $html;
    $Produse = new ViewStocuriProduse($mysql);
    $nr_r = $Produse -> find(array("WHERE", "denumire" => " LIKE '%$str%'", "ORDER BY", "denumire", "ASC"));
    $objResponse = new xajaxResponse();
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Denumire Produs");
        $gv -> tableOptions['ColWidth'] = array("100%");
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $Produse -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> denumire);
            if (empty($obj -> stoc)) {
                $obj -> stoc = 0;
            }
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            if ($obj -> la_vanzare == 'NU') {
                $class = "rowred";
            }
            $gv -> dataTable[$i]['tag'] = array("class"=>"$class",
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"xajax_selectProdus('". $obj -> denumire ."', ". $obj -> produs_id .", ".$obj -> stoc.");");
        }
        $objResponse -> assign("produse_lista", "innerHTML", $gv -> getTable());
    } else {
        $objResponse -> assign("produse_lista", "innerHTML", "NICI UN PRODUS IN LISTA");
    }
    return $objResponse;
}

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
    $produs = new ViewStocuriProduse($mysql, $produs_id);
    if (!$produs -> obj -> stoc) {
        $produs -> obj -> stoc = 0;
    }
    $objResponse = new xajaxResponse();
    $objResponse -> assign("produs_id", "value", $produs -> obj -> produs_id);
    $objResponse -> assign("txtDenumire", "value", $produs -> obj -> denumire);
    $objResponse -> assign("stoc_scriptic", "value", $produs -> obj -> stoc);
    $objResponse -> script("xajax.$('stoc_faptic').focus();");
    return $objResponse;
}


function searchCodBare($cod)
{
    global $mysql;
    global $html;
    $Produse = new ViewStocuriProduse($mysql);
    $Produse -> findBy("cod_bare", $cod);
    if (!empty($Produse -> obj)) {
        $objResponse = selectProdus($Produse -> obj -> denumire, $Produse -> obj -> produs_id, $Produse -> obj -> stoc);
        $objResponse -> assign("txtCodBare", "value", "");
    } else {
        $objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Cod neidentificat!<h3>', "400px", "150px", "350px", "250px", "OK");
        $objResponse -> assign("txtCodBare", "value", "");
    }
    return $objResponse;
}

function cod_bare()
{
    $objResponse = afisareDialog('<div id="divwindowhead">Citeste cod!</div><input type="text" name="txtCodBare" id="txtCodBare" style="width:395px" onKeyPress="var key=event.keyCode || event.which; if (key==13){this.blur();xajax_searchCodBare(document.getElementById(\'txtCodBare\').value);xajax_btnRenuntaDialog()};">', "400px", "150px", "350px", "250px", "OK");
    $objResponse -> script("document.getElementById('txtCodBare').focus();");
    return $objResponse;
}
    
function saveComponenta($frmValues, $frmInventar = array())
{
    global $mysql;
    $componenta = new InventarContinut($mysql, $frmValues['inventar_continut_id']);
    $componenta -> obj -> stoc_faptic = $frmValues['stoc_faptic'];
    $componenta -> save();
    $inventar = new Inventar($mysql, $frmInventar['inventar_id']);
    $objResponse = btnRenuntaDialog();
    $objResponse -> assign("produs_id", "value", "");
    $objResponse -> assign("txtDenumire", "value", "");
    $objResponse -> assign("stoc_scriptic", "value", "");
    $objResponse -> assign("stoc_faptic", "value", "");
    $objResponse -> assign("intrare_componente", "innerHTML", $inventar -> continut());
    $objResponse -> script("document.getElementById('txtDenumire').focus();");
    return $objResponse;
}

function addComponenta($frmValues, $frmInventar = array())
{
    global $mysql;
    $inventar = new Inventar($mysql, $frmInventar['inventar_id']);
    
    $ret = $inventar -> verificaProdus($frmValues['produs_id']);
    if (!$ret) {
        $inventar -> addComponenta($frmValues);
        $objResponse = new xajaxResponse();
        $objResponse -> assign("produs_id", "value", "");
        $objResponse -> assign("txtDenumire", "value", "");
        $objResponse -> assign("stoc_scriptic", "value", "");
        $objResponse -> assign("stoc_faptic", "value", "");
        $objResponse -> assign("intrare_componente", "innerHTML", $inventar -> continut());
        $objResponse -> script("document.getElementById('txtDenumire').focus();");
        return $objResponse;
    } else {
        $objResponse = editComponenta($ret);
        $objResponse -> assign("produs_id", "value", "");
        $objResponse -> assign("txtDenumire", "value", "");
        return $objResponse;
    }
}

function editComponenta($inventar_continut_id)
{
    global $mysql;
    $componenta = new InventarContinut($mysql, $inventar_continut_id);
    $objResponse = afisareDialog($componenta -> editareComponenta());
    return $objResponse;
}

function removeComponenta($inventar_continut_id)
{
    global $mysql;
    $comp = new InventarContinut($mysql, $inventar_continut_id);
    $inventar = new Inventar($mysql, $comp -> obj -> inventar_id);
    $comp -> delete();
    $objResponse = new xajaxResponse();
    $objResponse -> assign("produs_id", "value", "");
    $objResponse -> assign("txtDenumire", "value", "");
    $objResponse -> assign("stoc_scriptic", "value", "");
    $objResponse -> assign("stoc_faptic", "value", "");
    $objResponse -> assign("intrare_componente", "innerHTML", $inventar -> continut());
    $objResponse -> script("document.getElementById('txtDenumire').focus();");
    return $objResponse;
}
        

function recalculare($frmInventar)
{
        global $mysql;
        $inventar = new Inventar($mysql, $frmInventar['inventar_id']);
        $inventar -> recalculare();
        $objResponse = afisareDialog('<div id="divwindowhead">Info</div><h3 align="center">Au fost actualizate stocurile!</h3><div align="center"><input type="button" value="OK" class="btnTouch" onClick="window.location.href = \'login.php\'"></div>', "400px", "150px", "350px", "250px", null);
        return $objResponse;
}

function stergeInventar($frmInventar)
{
        global $mysql;
        $inventar = new Inventar($mysql, $frmInventar['inventar_id']);
        $inventar -> stergeInventar();
        $objResponse = new xajaxResponse();
        $objResponse -> script("window.location.href = 'evidenta.inventar.php'");
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
        
    $Produs -> frmReplace($frmProdus, $html);
    $html -> append($innerHTML, $frmProdus);
    $objResponse = afisareDialog($innerHTML, "600px", "350px", "210px", "180px", null);
    $objResponse -> script("document.getElementById('denumire').focus();");
    return $objResponse;
}

function frmSave($frmValues)
{
    global $mysql;
    global $html;
    $Produs = new Produse($mysql);
    $nr_r = $Produs -> findAllBy("cod_bare", $frmValues['cod_bare']);
    if ($nr_r != 0) {
        if (!$frmValues['produs_id'] && $nr_r==1) {
            $objResponse = new xajaxResponse();
            $objResponse -> alert('Cod bare existent la produsul:'.$Produs -> objects[0] -> denumire);
            return $objResponse;
        }
    }
        
    if ($frmValues['produs_id']) {
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
    $objResponse = selectProdus($Produs -> obj -> denumire, $Produs -> obj -> produs_id, 0);
    $objResponse -> script("xajax_btnRenuntaDialog();");
    return $objResponse;
}
    
$xajax->processRequest();
