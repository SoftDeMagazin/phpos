<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CONFIGURARI PRODUSE</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css">
<link type="text/css" rel="stylesheet" href="css/config.produse.css">
<script type="text/javascript" src="js/ui/jquery-1.2.6.js"></script>          
<style type="text/css">
<!--
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
-->
</style>
<?php
require_once("config.produse.common.php");
require("config/config.php");
$xajax->printJavascript('thirdparty/xajax/');
?>

<?php
if ($cfgGui['loading']) {
    $load = new Loading();
    echo $load -> javaScript();
}
$tn = new TastaturaNumerica();
echo $tn -> printJavaScript();
$kb = new KeyBoard();
echo $kb -> printJavaScript();
$tabView = new TabView();
$tabView -> root = "";
echo $tabView -> printCss();
echo $tabView -> printJavaScript();
?>
<script type="text/javascript">
function fn_loadProdus(denumire, pret_vanzare)
    {
    xajax.$('div_denumire').innerHTML = denumire;
    xajax.$('div_pret').innerHTML = pret_vanzare;
    }
function fn_focus(strId)
    {
    document.getElementById(strId).focus();
    }   
</script>

</head>
<?php
?>

<body>
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.dialog.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.resizable.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.draggable.js"></script>

<div id="main">
<div id="layout">
<div id="categorii">
<?php
$html = new Html();
$mysql = new MySQL();

    $categorii = new Categorii($mysql);
    $categorii -> find(array("ORDER BY", "denumire_categorie", "ASC"));
    $i = 0;
if (isset($categorii -> objects)) {
    foreach ($categorii -> objects as $objCategorie) {
        $content[$i] = array("name" => $objCategorie -> denumire_categorie, "value" => $objCategorie -> denumire_categorie,
        "class" => "btn_catalog",
        "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});document.getElementById('txtCodBare').value='';document.getElementById('txtDenumire').value='';xajax_catalogListaProduse(" . $objCategorie -> categorie_id . ", xajax.getFormValues('frmFiltre'));"
        );
        $i++;
    }
}


    $options = array("width" => 900, "height" => 30, "scroll" => 800, "content" => $content);
    $tabView = new TabView();
    echo $tabView -> printTabView($options, 2);
?>
</div>

<div id="catalogLista"  style="height:460px; overflow:auto; padding-left:10px; margin-top:10px;">
</div>
<input name="frm_categorie_id" id="frm_categorie_id" type="hidden" value="0">
<hr>
<form action="" method="post" name="frmFiltre" id="frmFiltre" style="padding:0px 0px 0px 0px; margin:0px 0px 0px 0px; ">
<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="300" valign="top">
    <fieldset><legend>Sortare Produse</legend>
    <div style="text-align: center ">
    <select name="orderBy" id="orderBy" style="width:150px ">
      <option value="denumire" selected>Denumire</option>
      <option value="cod_bare">Cod bare</option>
      <option value="pret">Pret</option>
      <option value="stoc">Stoc</option>
     </select>
    </div>
    <input name="ordMod" type="radio" value="ASC" checked>
    Ascendent
    <input name="ordMod" type="radio" value="DESC" >
    Descendent
    <br>
    <div style="text-align: center ">
    <input name="btnSort" type="button" class="btn_search" id="btnSort" value=" " onClick="xajax_catalogListaProduse(document.getElementById('frm_categorie_id').value, xajax.getFormValues('frmFiltre'))">
    </div>
    </fieldset> </td>
    <td width="400" valign="top"><fieldset><legend>Cautare Produse</legend>
    <div style="text-align:center; "><label style="text-align:left; ">Cod Bare</label>
    <br>
    <input type="text" name="txtCodBare" id="txtCodBare">
    <input name="Button2" type="button" class="kb_buttonTastatura" value=" " onClick="xajax_kbPopup('txtCodBare');">
    <input name="btnSearchCod" type="button" class="btn_search" id="btnSearchCod" value=" " onClick="xajax_catalogListaProduse(document.getElementById('frm_categorie_id').value, xajax.getFormValues('frmFiltre'))">
    <br>
    <input name="radMod" type="radio" value="AND">
    Si
    <input name="radMod" type="radio" value="OR" checked>
    Sau
    <br>
    <label style="text-align:left; ">Denumire</label> <br>
    <input type="text" name="txtDenumire" id="txtDenumire">
    <input name="Button" type="button" class="kb_buttonTastatura" value=" " onClick="xajax_kbPopup('txtDenumire');">
    <input name="btnSearchDenumire" type="button" class="btn_search" id="btnSearchDenumire" value=" " onClick="xajax_catalogListaProduse(document.getElementById('frm_categorie_id').value, xajax.getFormValues('frmFiltre'))">
    <div style="text-align:center; margin-top:10px; ">
    <input name="btnResetSearch" type="button" class="btnTouch" id="btnResetSearch" onClick="document.getElementById('txtCodBare').value='';document.getElementById('txtDenumire').value='';xajax_catalogListaProduse(document.getElementById('frm_categorie_id').value, xajax.getFormValues('frmFiltre'))" value="Reset">
    </div>

    </fieldset></td>
    <td width="320" valign="top"><fieldset><legend>Produs</legend>
          <input type="hidden" name="frm_produs_id" id="frm_produs_id" value="0">
        <div id="produsDenumire" style="height: 25px; text-align:center; color:#FF0000; font-weight:bold; ">&nbsp;</div>
        <div align="center">
            <input name="addProdus" type="button" class="btnTouch" accesskey="a" onClick="xajax_frmProdus(0);" value="Adauga Produs" style="height:40px; "><br>
            <input name="editProdus" type="button" class="btnTouch" accesskey="m" onClick="xajax_frmProdus(document.getElementById('frm_produs_id').value);" value="Modifica Produs" style="height:40px; margin-top:5px; ">
        </div>
        <div align="center" style="margin-top:10px; padding-top:10px; border-top:1px solid #ccc;">
          <input name="btnCautareAvansata" type="button" class="btnTouch" id="btnCautareAvansata" onClick="window.location.href = 'login.php'" value="IESIRE">
        </div>
    </fieldset></td>
  </tr>
</table>


</form>
<div id="overlay">
</div>
<div id="obiecte" class="flora">
</div>
</div>
</div>
<?php
if ($cfgGui['loading']) {
    $load = new Loading();
    echo $load -> div();
}
?>
<div id="windows">
</div>
</body>
</html>
