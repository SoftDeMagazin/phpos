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
require_once("evidenta.inventar.common.php");

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
<body id="body">
<div id="main">
<div id="layout" style="width:1020px;">
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col">EVIDENTA INVENTAR</th>
  </tr>
  <tr>
    <th scope="col"><form action="" method="post" name="frmFiltre" id="frmFiltre" >
<br><br>

  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200"><div align="center">
      <input name="dateStart" id="dateStart" type="text" value="<?php echo date("Y-m-d"); ?>" readonly="" onClick="xajax_calPopup('','', 'dateStart')">
    </div></td>
    <td width="200"><div align="center">
      <input name="dateStop" type="text" id="dateStop" value="<?php echo date("Y-m-d"); ?>" readonly="" onClick="xajax_calPopup('','', 'dateStop')">
    </div></td>
    <td width="200">
    <div align="center">
      <?php
            $frm = new Forms();
            $user = new Users($mysql);
            $user -> find(array("WHERE", "activ" => " = 'DA'", "ORDER BY", "nume", "ASC"));
            $options = null;
            $options['0'] = "Filtru Utilizatori";
        if (isset($user -> objects)) {
            foreach ($user -> objects as $objUser) {
                $options[$objUser -> user_id] = $objUser -> nume;
            }
        }
            echo $frm -> input("user_id", array("options" => $options));
        ?>  
      </div></td>
    <td width="200"><div align="center">
    </div></td>
  </tr>
  <tr>
    <td><div align="left">
            <label for="data_adaugare"></label>
    </div></td>
    <td><div align="left">
<input name="nr" type="radio" value="numar_inventar" checked>      
Numar inventar:<br>
        </div></td>
    <td>    <div align="center">
      <input name="numar_inventar" type="text" id="numar_inventar">
    </div>  </td>
    <td>
    <div align="center">
      <input name="btnGenereaza" type="button" id="btnGenereaza" value="Genereaza Raport" onClick="xajax_genereazaLista(xajax.getFormValues('frmFiltre'));">    
      </div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form></th>
  </tr>
  <tr>
    <th scope="col"><div id="preview" style="height: 350px; overflow:auto; "></div></th>
  </tr>
  <tr>
    <td><div id="actiuni">

    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">      <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <th scope="col"><div align="center">
            <input name="Button2" type="button" class="btnTouch" onClick="window.location.href = 'login.php'" value="IESIRE">
</div></th>
          <th scope="col">&nbsp;</th>
          <th scope="col"><input name="Button3" type="button" class="btnTouch" value="ADAUGA INVENTAR" onClick="window.location.href = 'inventar.php'"></th>
        </tr>
      </table>
    </div></td>
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
