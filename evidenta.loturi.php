<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>EVIDENTA LOTURI</title>
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
require_once("evidenta.loturi.common.php");

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
$ds = new divScroll();
echo $ds -> printJavaScript();
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
<body id="body">
<div id="main">
<div id="layout" style="width:1020px;">
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<table width="700" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td scope="col"><strong>EVIDENTA LOTURI</strong></td>
  </tr>
  <tr>
    <td scope="col"><form action="" method="post" name="frmFiltre" id="frmFiltre" ><br>
  <table width="800" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td>De la</td>
    <td>Pana la </td>
    <td colspan="2">Produs</td>
    </tr>
  <tr>
    <td width="200"><div align="center">
      <input name="dateStart" id="dateStart" type="text" value="<?php echo date("Y-m-d"); ?>" readonly="" onClick="xajax_calPopup('','', 'dateStart')">
    </div></td>
    <td width="200"><div align="center">
      <input name="dateStop" type="text" id="dateStop" value="<?php echo date("Y-m-d"); ?>" readonly="" onClick="xajax_calPopup('','', 'dateStop')">
    </div></td>
    <td colspan="2"><input name="denumire" id="denumire" type="text" size="40" onKeyUp="if(event.keyCode == 13) { if(this.value == '') {xajax_cautaProdus('', '');} else {xajax_cautaProdus(this.value,'');}}" onFocus="this.value = '';xajax.$('produs_id').value='';this.style.backgroundColor='#CCCCCC';this.select();" onDblClick="xajax_cautaProdus('','');" onBlur="this.style.backgroundColor='#FFFFFF'">
      <input name="produs_id" type="hidden" id="produs_id"></td>
    </tr>
  <tr>
    <td>
              <div align="center">
                <label for="data_adaugare">
                <input name="btnGenereaza" type="button" id="btnGenereaza2" value="Genereaza Raport" onClick="xajax_genereazaLista(xajax.getFormValues('frmFiltre'));">
                </label>
            </div></td><td><div align="center"></div></td>
    <td width="200">    <div align="center">
    </div>  </td>
    <td width="200">
    <div align="center">      </div>
    </td>
  </tr>
</table>
</form></td>
  </tr>
  <tr>
    <th scope="col"><div id="preview" style="height: 350px; overflow:auto; margin-top:10px; "></div></th>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="btnIesire" type="button" class="btnTouch" id="btnIesire" value="Iesire" onClick="window.location.href = 'login.php'">
      </div></td>
  </tr>
  <tr>
    <td><div align="center">      </div></td>
  </tr>
</table>


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
