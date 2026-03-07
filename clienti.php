<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CATALOG CLIENTI</title>
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
require_once("clienti.common.php");

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
<body id="body">

<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">
<?php
$html = new Html();
$mysql = new MySQL();
    $content[] = array("name" => "btnConfigCategorii", "value" => "Toti",
            "class" => "btn_catalog",
            "style" => "width:45px",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_lista();"
            );
    for ($i = 'A'; $i < 'Z'; $i++) {
        $content[] = array("name" => "btnConfigCategorii", "value" => "$i",
            "class" => "btn_catalog",
            "style" => "width:45px",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_lista('$i');"
            );
    }
    $content[] = array("name" => "btnConfigCategorii", "value" => "Z",
            "class" => "btn_catalog",
            "style" => "width:45px",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_lista('Z');"
            );
    $options = array("width" => 900, "height" => 30, "scroll" => 800, "content" => $content);
    $tabView = new TabView();
    echo $tabView -> printTabView($options, 2);
    ?>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="550" width="450" valign="top">
      <div id="listaObiecte" style="width:90%; height: 500px;margin: 0px auto; margin-top:10px; overflow:auto;"> </div></td>
    <td valign="top" width="550"><form action="" method="post" name="frmClient" id="frmClient">
      <div id="divForm"> </div>
      </form></td>
  </tr>
  <tr>
    <td width="450"><form action="" method="post" name="frmCauta" id="frmCauta">
      <table width="100%"  border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="17%"><strong>Caut</strong></td>
          <td width="83%"><input name="txtSearch" type="text" id="txtSearch" size="40"></td>
        </tr>
        <tr>
          <td><strong>Dupa</strong></td>
          <td><select name="dupa" id="dupa">
            <option value="denumire" selected>Denumire</option>
            <option value="reg_com">Reg Com</option>
            <option value="cif">Cod fiscal</option>
            <option value="judetul">Judet</option>
            <option value="sediul">Sediul</option>
          </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="btnCauta" type="button" class="btn_search" id="btnCauta" value=" " onClick="xajax_lista(0, xajax.getFormValues('frmCauta'))"></td>
        </tr>
      </table>
    </form></td>
    <td width="550">
      <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center">
              <input name="btnSave" type="button" class="btnTouch" id="btnSave" onClick="xajax_btnSave(xajax.getFormValues('frmClient'))" value="Salveaza">
          </div></td>
          <td><div align="center">
              <input name="btnCautareAvansata" type="button" class="btnTouch" id="btnCautareAvansata" onClick="window.location.href = 'login.php'" value="IESIRE">
          </div></td>
          <td><div align="center">
              <input name="btnAdauga" type="button" class="btnTouch" id="btnAdauga" onClick="xajax_loadForm(0)" value="Adauga">
          </div></td>
        </tr>
    </table></td>
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
