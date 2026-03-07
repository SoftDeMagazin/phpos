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
require_once("etichete.common.php");

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
    <th scope="col">EVIDENTA ETICHETE </th>
  </tr>
  <tr>
    <th scope="col"><div id="preview" style="height: 550px; overflow:auto; ">
    <?php
        $continut = new Etichete($mysql);
        $nr_r = $continut -> findAll();
                    $gv = new GridView();
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding" => 0, "align" => "left");
                    $gv -> tableOptions['head'] = array("class" => "rowhead");
                    $gv -> columns = array("Cod", "Data");
                    $gv -> tableOptions['ColWidth'] = array("50%", "50%");
    for ($i = 0; $i < $nr_r; $i++) {
        $obj = $continut -> objects[$i];
        $gv -> dataTable[$i]['data'] = array($obj -> eticheta_id, $obj -> data);
        if ($i % 2 == 0) {
            $class = "roweven";
        } else {
            $class = "rowodd";
        }

        $gv -> dataTable[$i]['tag'] = array("class" => $class,
        "onMouseOver" => "$(this).addClass('rowhover')",
        "onMouseOut" => "$(this).removeClass('rowhover')",
        "onClick" => "window.location.href = 'etichete.php?eticheta_id=" . $obj -> eticheta_id . "'",
        );
    }
                    echo $gv -> getTable();
    ?>
    </div></th>
  </tr>
  <tr>
    <td></td>
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
          <th scope="col"><input name="Button3" type="button" class="btnTouch" value="ETICHETA NOUA" onClick="window.location.href = 'etichete.php'"></th>
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
