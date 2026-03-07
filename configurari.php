<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CONFIGURARI PRODUSE</title>
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
require_once("configurari.common.php");
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
            0 => array("name" => "btnConfigCategorii", "value" => "Categorii",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadObiecte('categorii');"
            ),
            1 => array("name" => "btnConfigModuriPlata", "value" => "Moduri Plata",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadObiecte('moduriplata');"
            ),
            2 => array("name" => "btnConfigUsers", "value" => "Utilizatori",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadObiecte('users');"
            ),
            3 => array("name" => "btnConfigCase", "value" => "Case fiscale",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadObiecte('case');"
            ),
            4 => array("name" => "btnConfigFurnizori", "value" => "Furnizori",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadObiecte('furnizori');"
            ),
            5 => array("name" => "btnConfigUm", "value" => "Unitati Masura",
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_loadObiecte('um');"
            )
            );
            $i++;

            $options = array("width" => 900, "height" => 30, "scroll" => 800, "content" => $content);
            $tabView = new TabView();
            echo $tabView -> printTabView($options, 2);
            ?>
</div>
<input name="config" id="config" type="hidden" value="0">
<input name="form_name" id="form_name" type="hidden" value="0">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="550" width="450" valign="top">
    <div id="listaObiecte" style="width:90%; height: 500px;margin: 0px auto; margin-top:10px; overflow:auto;">
    </div>
    </td>
    <td valign="top" width="550">
    <div id="divForm">
    </div>
    </td>
  </tr>
  <tr>
    <td width="450">&nbsp;</td>
    <td width="550">
    
    <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><div align="center">
          <input name="btnSave" type="button" class="btnTouch" id="btnSave" onClick="xajax_btnSave(document.getElementById('config').value, xajax.getFormValues(document.getElementById('form_name').value))" value="Salveaza">
        </div></td>
        <td><div align="center">
          <input name="btnCautareAvansata" type="button" class="btnTouch" id="btnCautareAvansata" onClick="window.location.href = 'login.php'" value="IESIRE">
        </div></td>
        <td><div align="center">
          <input name="btnAdauga" type="button" class="btnTouch" id="btnAdauga" onClick="xajax_loadForm(document.getElementById('config').value,0)" value="Adauga">
        </div></td>
      </tr>
    </table>
    
    </td>
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
</body>
</html>
