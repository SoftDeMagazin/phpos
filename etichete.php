<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>ETICHETE</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css" >
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
#loading {

    padding: 20px;
    border: 0px solid green;
    display: none; /* hidden */
    position: absolute;    
    left: 50%;
    margin-left: -100px;
    top: 25%;
    width: 200px;
    /*height: 100px;*/
        /*margin-top: -50;*/
    font-weight: bold;
    font-size: large;
    }

-->
</style>
<?php
require_once("etichete.common.php");

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
</head>
<?php
/*         DB              */
$mysql = new MySQL();
?>
<Script Language=JavaScript>

function getRealLeft(el){
xPos = document.getElementById(el).offsetLeft;
tempEl = document.getElementById(el).offsetParent;
while (tempEl != null) {
xPos += tempEl.offsetLeft;
tempEl = tempEl.offsetParent;
}
return xPos;
}

function getRealTop(el){
yPos = document.getElementById(el).offsetTop;
tempEl = document.getElementById(el).offsetParent;
while (tempEl != null) {
yPos += tempEl.offsetTop;
tempEl = tempEl.offsetParent;
}
return yPos;
}

function getRealLeftSec(el){
xPos = el.offsetLeft;
tempEl = el.offsetParent;
while (tempEl != null) {
xPos += tempEl.offsetLeft;
tempEl = tempEl.offsetParent;
}
return xPos;
}

function getRealTopSec(el){
yPos = el.offsetTop;
tempEl = el.offsetParent;
while (tempEl != null) {
yPos += tempEl.offsetTop;
tempEl = tempEl.offsetParent;
}
return yPos;
}

function dispTruePos(isID){
trueX = getRealLeft(isID);
trueY = getRealTop(isID);
alert('True Xpos is: '+trueX+'nTrue Ypos is: '+trueY)
}

</Script>
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
<script type="text/javascript">
var OnKeyRequestBuffer = 
    {
        bufferText: false,
        bufferTime: 500,
        
        modified : function(strId)
        {
                setTimeout('OnKeyRequestBuffer.compareBuffer("'+strId+'","'+xajax.$(strId).value+'");', this.bufferTime);
        },
        
        compareBuffer : function(strId, strText)
        {
            if (strText == xajax.$(strId).value && strText != this.bufferText)
            {
                this.bufferText = strText;
                OnKeyRequestBuffer.makeRequest(strId);
            }
        },
        
        makeRequest : function(strId)
        {
            xajax_searchProduse(document.getElementById('txtDenumire').value);
        }
    }
</script>
<?php
$eticheta_id = $_GET['eticheta_id'];
if (!empty($eticheta_id)) {
    echo '<body id="body" onLoad="xajax_onLoad(' . $eticheta_id . ');">';
} else {
    echo '<body id="body" onLoad="">';
}
?>
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">
<form action="frmEticheta" id="frmEticheta" method="post">
  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td><input name="eticheta_id" type="hidden" id="eticheta_id"></td>
      <td><strong>Margini pagina </strong></td>
      <td><div id="div_numar"></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Stanga</strong></td>
      <td><input name="stanga" type="text" id="stanga" value="10">
        mm</td>
      <td><strong>Numar coloane </strong></td>
      <td><input name="numar_coloane" type="text" id="numar_coloane">
        mm</td>
    </tr>
    <tr>
      <td><strong>Dreapta</strong></td>
      <td><input name="dreapta" type="text" id="dreapta" value="10">
        mm</td>
      <td><strong>Inaltime eticheta </strong></td>
      <td><input name="inaltime_eticheta" type="text" id="inaltime_eticheta">
        mm</td>
    </tr>
    <tr>
      <td><strong>Sus</strong></td>
      <td><input name="sus" type="text" id="sus" value="10">
        mm</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Jos</strong></td>
      <td><input name="jos" type="text" id="jos" value="10">
        mm</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="13%">&nbsp;</td>
      <td width="22%"><input name="btnSalveazaAntet" type="button" id="btnSalveazaAntet" value="SALVEAZA" onClick="xajax_salveazaAntet(xajax.getFormValues('frmEticheta'))"></td>
      <td width="20%">&nbsp;</td>
      <td width="45%"><input name="btnComponente" type="button" id="btnComponente" value="COMPONENTE" onClick="this.blur();xajax_componente();"></td>
    </tr>
  </table>
</form>
<hr>
<fieldset><legend>Componente</legend>
<div style="height:400px; width:980px; overflow:auto; ">    
<form action="" method="post" name="frmComponente" id="frmComponente" style="margin:0px 0px 0px 0px;">
<div id="etichete_continut" >
</div>
</form>
</div>
</fieldset>
<table width="800"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><div align="center">
        <input name="Button" type="button" class="btnTouch" value="TIPARESTE" onClick="window.location.href = 'print.etichete.php?eticheta_id='+document.getElementById('eticheta_id').value" accesskey="i">
    </div></td>
    <td><div align="center">
      <input name="Button2" type="button" class="btnTouch" value="Iesire" onClick="window.location.href='evidenta.etichete.php'"  accesskey="e">
    </div></td>
  </tr>
</table>
</div>
<div id="overlay">
</div>
<div id="obiecte" class="flora" style="z-index:500 ">
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
