<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>FURNIZORI PRODUSE</title>
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
require_once("furnizori.produse.common.php");

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
<body id="body"  onLoad="$('#dataStart').datepicker();$('#dataStop').datepicker()">
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">
  <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="487" valign="top">
    <form name="frmFiltre" id="frmFiltre" method="post" action="">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Furnizor</td>
        <td><?php
        $furn = '<select name="furnizor_id"  id="furnizor_id" style="width:350px" tabindex="1" >';
        $furnizori = new Furnizori($mysql);
        $furnizori -> find(array("ORDER BY", "nume", "ASC"));
        if (isset($furnizori -> objects)) {
            foreach ($furnizori -> objects as $obj) {
                if ($obj -> furnizor_id == $intrare -> obj -> furnizor_id) {
                    $furn .= '<option value="' . $obj -> furnizor_id . '" selected>' . $obj -> nume . '</option>';
                } else {
                    $furn .= '<option value="' . $obj -> furnizor_id . '">' . $obj -> nume . '</option>';
                }
            }
        }
        $furn .= '</select>';
        echo $furn;
        ?></td>
      </tr>
      <tr>
        <td width="11%">De la</td>
        <td width="89%"><label>
          <input type="text" name="dataStart" id="dataStart" value="<?php echo date("Y-m-d"); ?>">
        </label></td>
      </tr>
      <tr>
        <td>Pana la </td>
        <td><input type="text" name="dataStop" id="dataStop" value="<?php echo date("Y-m-d"); ?>"></td>
      </tr>
    </table>
    </form>    </td>
    <td width="513" valign="top"><label>
      <input type="button" name="btnAfiseazaProduse" id="btnAfiseazaProduse" value="Afiseaza produse" onClick="xajax_raport(xajax.getFormValues('frmFiltre'));">
    </label></td>
  </tr>
  <tr>
    <td colspan="2" valign="top">
    <div id="lista" style="height:500px; overflow:auto;">
    
    
    </div>
    </td>
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
<div id="obiecte" class="flora" style="">
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
