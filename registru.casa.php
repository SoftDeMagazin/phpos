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
require_once("registru.casa.common.php");

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
echo '<body id="body" onLoad="$(\'#dataStart\').datepicker();$(\'#dataStop\').datepicker()">';
?>
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><form action="" method="post" name="frmFiltreRegistru" id="frmFiltreRegistru"><strong>        </strong>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><strong>Data inceput </strong></td>
            <td><strong>
              <input name="dataStart" type="text" id="dataStart" value="<?php echo date("Y-m-d"); ?>">
            </strong></td>
          </tr>
          <tr>
            <td><strong>Data sfarsit</strong> </td>
            <td><strong>
              <input name="dataStop" type="text" id="dataStop" value="<?php echo date("Y-m-d"); ?>">
            </strong></td>
          </tr>
        </table>
      </form></td>
    <td><div align="center">
      <input name="btnAfiseazaRegistru" type="button" id="btnAfiseazaRegistru" value="Afiseaza Registru" onClick="xajax_incarcaRegistru(xajax.getFormValues('frmFiltreRegistru'))">
    </div></td>
  </tr>
  <tr>
    <td width="50%"><strong>Plati</strong></td>
    <td width="50%"><strong>Incasari</strong></td>
  </tr>
  <tr>
    <td><div id="div_plati" style="width:95%; height:500px; overflow:auto;"></div></td>
    <td><div id="div_incasari" style="width:95%; height:500px; overflow:auto;"></div></td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="btnAdaugaPlata" type="button" class="btnTouch" id="btnAdaugaPlata" onClick="xajax_btnAdaugaPlata()" value="Adauga Plata">
    </div></td>
    <td><div align="center">
      <input name="btnAdaugaIncasare" type="button" class="btnTouch" id="btnAdaugaIncasare" onClick="xajax_btnAdaugaIncasare()" value="Adauga Incasare">
    </div></td>
  </tr>
</table>
<table width="800"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><div align="center">
      <input name="Button2" type="button" class="btnTouch" value="Iesire" onClick="window.location.href='login.php'"  accesskey="e">
    </div></td>
    </tr>
</table>
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
