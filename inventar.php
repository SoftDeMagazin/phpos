<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>INTRARI</title>
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
require_once("inventar.common.php");

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
$inventar_id = $_GET['inventar_id'];
if (!isset($inventar_id)) {
    echo '<body id="body" onLoad="xajax_deschideInventar()">';
} else {
    echo '<body id="body" onLoad="xajax_deschideInventar(' . $inventar_id . ')">';
}
?>
<div id="main">
<div id="layout" style="width:1020px;">
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<form name="frmInventar" id="frmInventar" style="margin:0px 0px 0px 0px;">
<div id="nir" style="overflow:hidden; height:100px "></div>
</form> 
<div id="meniu" class="page" style="height:50px">
  <table width="800"  border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td><div align="center">
        <input name="Button" type="button" id="btnRecalculare" class="btnTouch" value="RECALCULARE" onClick="xajax_recalculare(xajax.getFormValues('frmInventar'));">
      </div></td>
          <td><div align="center">
        <input name="Button" type="button" id="btnAnuleaza" class="btnTouch" value="Anuleaza" onClick="xajax_stergeInventar(xajax.getFormValues('frmInventar'));">
      </div></td>      
      <td><div align="center">
        <input name="Button2" type="button" class="btnTouch" value="Iesire" onClick="window.location.href='evidenta.inventar.php'">
        </div></td>
    </tr>
  </table>
</div>
<div id="componente" style="height:550px; overflow:hidden">
<form action="" method="get" name="frmAddComponenta" id="frmAddComponenta" style="margin:0px 0px 0px 0px;">
<fieldset ><legend>Introducere componente</legend>

<table width="1000"  border="0" cellspacing="0" cellpadding="1" align="center">
  <tr class="rowhead">
    <td width="329"><strong>Denumire Produs</strong></td>
    <td width="163"><strong>Stoc Scriptic </strong></td>
    <td width="126"><strong>Stoc Faptic </strong></td>
    <td width="81"><strong>Salveaza</strong></td>
    <td width="71">&nbsp;</td>
  </tr>
  <tr>
    <td><input name="txtDenumire" id="txtDenumire" type="text" size="45" onKeyUp="if(event.keyCode == 13) { if(this.value == '') {xajax_cautaProdus('', 'sursa');} else {xajax_cautaProdus(this.value,'sursa');}}" onFocus="this.value = '';xajax.$('produs_id').value='';this.style.backgroundColor='#CCCCCC';this.select();" onDblClick="xajax_cautaProdus();" onBlur="this.style.backgroundColor='#FFFFFF'">
    <input name="produs_id" type="hidden" value="" id="produs_id"></td>
    <td><input name="stoc_scriptic" type="text" id="stoc_scriptic" size="10" readonly=""></td>
    <td><input name="stoc_faptic" type="text" id="stoc_faptic" size="10" onKeyUp="if(event.keyCode == 13) xajax_addComponenta(xajax.getFormValues('frmAddComponenta'),xajax.getFormValues('frmInventar'))">
      </td>
    <td><div align="center">
      <input name="btnAddComponenta" type="button" id="btnAddComponenta" value="Adauga" onClick="xajax_addComponenta(xajax.getFormValues('frmAddComponenta'),xajax.getFormValues('frmInventar'))">
    </div></td>
    <td><div align="right">
      <input type="reset" name="Reset" value="Reset">
    </div></td>
  </tr>
</table>

</fieldset>
</form>

<fieldset><legend>Componente</legend>
<div style="height:400px; overflow:auto; ">
<form action="" method="post" name="frmComponente" id="frmComponente" style="margin:0px 0px 0px 0px;">
<div id="intrare_componente">
</div>
</form>
</div>
<table width="800"  border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td><div align="center">
        <input name="Button3" type="button" class="btnTouch" value="ADAUGA PRODUS" onClick="xajax_frmProdus(0);">
        
      </div></td>
      </tr>
  </table>
</fieldset>
  </div>
</div>
<div style="position:absolute; width:300px; height:400px; display:none; border:1px solid #000; background-color:#FFFFFF; " id="div">
<div id="close" style="text-align:right; border-bottom: 1px solid #000;" class="rowhead"><img src="i/dialog-titlebar-close.png" onClick="document.getElementById('div').style.display='none'"></div>
<div id="produse_lista" style="height:350px; overflow:scroll">
</div>
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
<div id="windows">  </div>
</body>
</html>
