<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>RAPOARTE</title>
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
require_once("rapoarte.common.php");
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
<div id="meniu">
<?php
$html = new Html();
$mysql = new MySQL();

            $content = array(
            0 => array("name" => "btnRptModuriPlata", "value" => "Raport Moduri Plata",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadRaport(xajax.getFormValues('frmFiltre'), 'rptModuriPlata')"
            ),
            1 => array("name" => "btnRptVanzari", "value" => "RaportVanzari",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadRaport(xajax.getFormValues('frmFiltre'), 'rptVanzari')"
            ),
            3 => array("name" => "btnRaportUtilizatori", "value" => "Raport Utilizatori",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadRaport(xajax.getFormValues('frmFiltre'), 'rptUtilizatoriModuri')"
            ),
            4 => array("name" => "btnRaportUtilizatori", "value" => "Raport Nr. Bonuri Emise",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadRaport(xajax.getFormValues('frmFiltre'), 'rptBonuriEmise')"
            ),
            5 => array("name" => "btnRaportCase", "value" => "Raport Case Fiscale",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadRaport(xajax.getFormValues('frmFiltre'), 'rptCaseModuri')"
            )

            );
            $i++;

            $options = array("width" => 900, "height" => 30, "scroll" => 800, "content" => $content);
            $tabView = new TabView();
            echo $tabView -> printTabView($options, 2);
            ?>
</div>
<input name="rpt" id="rpt" type="hidden" value="0">
<input name="form_name" id="form_name" type="hidden" value="0">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="550" width="350" valign="top">
    <div id="preview" style="width:350px; height: 500px; margin-top:10px; overflow:scroll;">
    </div>
    </td>
    <td valign="top" width="650">
    <div align="center">
    <?php
    $zi = new ZileEconomice($mysql);
    $zi -> getLastDay();
    ?>
    <form action="" method="post" name="frmFiltre" id="frmFiltre">
      <p>
        <input name="dateStart" id="dateStart" type="text" value="<?php echo $zi -> obj -> data; ?>" readonly="" onClick="xajax_calPopup('','', 'dateStart')"> 
        -----
        <input name="dateStop" type="text" id="dateStop" value="<?php echo $zi -> obj -> data; ?>" readonly="" onClick="xajax_calPopup('','', 'dateStop')">
        <input name="btnGenereaza" type="button" class="btnTouch" id="btnGenereaza" value="Regenereaza raport" onClick="xajax_loadRaport(xajax.getFormValues('frmFiltre'), document.getElementById('rpt').value)">
        <br>
        <input name="chkGrafic" type="checkbox" id="chkGrafic" value="DA">
        <strong>Generez grafic (daca este disponibil) </strong></p>
      </form>
    </div>
    <div id="grafic" style="margin-top: 20px; text-align:center">
    </div></td>
  </tr>
  <tr>
    <td width="350"><div align="center">
   <input name="btnPrint" type="button" class="btnTouch" id="btnPrint" value="Tipareste raport" onClick="xajax_printRaport(xajax.getFormValues('frmFiltre'), document.getElementById('rpt').value)">
    </div></td>
    <td width="650"><div align="center">
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
