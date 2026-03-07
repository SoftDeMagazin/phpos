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
require_once("transformari.common.php");

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
echo '<body id="body" onLoad="$(\'#data_transformare\').datepicker();">';
?>
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">

<form action="" method="post" name="frmTransformare" id="frmTransformare" onSubmit="return false;">
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2"><strong>Transformari</strong></td>
    </tr>
    <tr>
      <td>Data: 
        <input name="data_transformare" type="text" id="data_transformare" value="<?php echo date("Y-m-d"); ?>"></td>
      <td><div align="center"> </div></td>
    </tr>
    <tr class="rowhead">
      <td width="50%"><strong>Articol Sursa </strong></td>
      <td width="50%"><strong>Articol Destinatie</strong></td>
    </tr>
    <tr>
      <td><div id="div_sursa" style="width:95%; height:500px; overflow:auto;">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="29%"><strong>Produs</strong></td>
            <td width="71%"><input name="sursa_denumire" id="sursa_denumire" type="text" size="40" onKeyUp="if(event.keyCode == 13) { if(this.value == '') {xajax_cautaProdus('', 'sursa');} else {xajax_cautaProdus(this.value,'sursa');}}" onFocus="this.value = '';xajax.$('sursa_produs_id').value='';this.style.backgroundColor='#CCCCCC';this.select();" onDblClick="xajax_cautaProdus();" onBlur="this.style.backgroundColor='#FFFFFF'"></td>
          </tr>
          <tr>
            <td><input name="sursa_produs_id" type="hidden" id="sursa_produs_id" value="0"></td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td><strong>Cantitate</strong></td>
            <td><input name="sursa_cantitate" type="text" id="sursa_cantitate"></td>
          </tr>
        </table>
      </div></td>
      <td><div id="div_destinatie" style="width:95%; height:500px; overflow:auto;">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="24%"><strong>Produs</strong></td>
            <td width="76%"><input name="destinatie_denumire" id="destinatie_denumire" type="text" size="40" onKeyUp="if(event.keyCode == 13) { xajax_cautaProdus(this.value,'destinatie');}" onFocus="this.value = '';xajax.$('destinatie_produs_id').value='';this.style.backgroundColor='#CCCCCC';this.select();" onDblClick="xajax_cautaProdus();" onBlur="this.style.backgroundColor='#FFFFFF'">
            <input name="addProdus" type="button" value="NOU"  id="addProdus" onClick="xajax_frmProdus(document.getElementById('destinatie_produs_id').value, 'destinatie');">          </td>
          </tr>
          <tr>
            <td><input name="destinatie_produs_id" type="hidden" id="destinatie_produs_id" value="0"></td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td><strong>Cantitate</strong></td>
            <td><input name="destinatie_cantitate" type="text" id="destinatie_cantitate"></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td><div align="center">
        <input name="btnIesire" type="button" id="btnIesire" onClick="window.location.href = 'evidenta.transformari.php'" value="IESIRE"> 
        </div></td>
      <td><div align="center">
        <input name="btnSalveazaTransformare" type="button" id="btnSalveazaTransformare" onClick="xajax_salveazaTransformare(xajax.getFormValues('frmTransformare'));" value="SALVEAZA TRANSFORMARE"> 
        </div></td>
    </tr>
  </table>
</form>
</div>
<div id="overlay">
</div>
<div id="obiecte" class="flora" style=" ">
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
