<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>EVIDENTA VANZARI</title>
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
select {
width: 190px;
}
-->
</style>
<?php
require_once("raportgestiune.common.php");
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
?>
<body>
<div id="main">
<div id="layout">
<?php
$mysql = new MySQL();
$zi = new ZileEconomice($mysql);
$zi -> getLastDay();
?>
<form action="raportgestiune.print.php" method="post" name="frmFiltre" id="frmFiltre" >
<br>
<br>

  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="center">
        <input name="dateStart" id="dateStart" type="text" value="<?php echo date("Y-m-d"); ?>" readonly="" onClick="xajax_calPopup('','', 'dateStart')">
    </div></td>
    <td><div align="center">
        <input name="dateStop" type="text" id="dateStop" value="<?php echo date("Y-m-d"); ?>" readonly="" onClick="xajax_calPopup('','', 'dateStop')">
    </div></td>
    <td width="200">&nbsp;</td>
  </tr>
  <tr>
    <td>    <div align="center">
      <label>
      <strong>Detaliat</strong>
      <input name="detaliat" type="checkbox" id="detaliat" value="DA">
      </label>
    </div>  </td>
    <td>
    <div align="center">
      <input name="btnGenereaza" type="button" id="btnGenereaza" value="Genereaza Raport" onClick="xajax_genereazaRaport(xajax.getFormValues('frmFiltre'));">   
      </div>    </td>
    <td><div align="center">
      <input name="btnGenereaza2" type="submit" id="btnGenereaza2" value="TIPARIRE">
    </div></td>
  </tr>
</table>
</form>

<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="550"  valign="top">
    <div id="preview" style="width:90%; height:500px;overflow:scroll; margin:20px auto;">
    </div>
    </td>
    </tr>
  <tr>
    <td width="420"><div align="center">
      <input name="btnIesire" type="button" class="btnTouch" id="btnIesire" value="Iesire" onClick="window.location.href = 'login.php'">
    </div></td>
    </tr>
</table>


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
<div id="windows"></div>
</body>
</html>
