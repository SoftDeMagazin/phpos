<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>INCHIDERE ZI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css">
<link type="text/css" rel="stylesheet" href="css/config.produse.css">
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
require_once("inchidere.zi.common.php");

$xajax->printJavascript('thirdparty/xajax/');
?>

<?php
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
include("include/db/abstractdb.class.php");
include("include/db/mysql.class.php");
$mysql = new MySQL();
include("include/models/users.class.php");
include("include/models/zileeconomice.class.php");
include("include/helpers/print.class.php");
?>
<body>
<div id="main">
<div id="layout">

<div style="margin-top:100px; ">

<?php
$r = isset($_GET['r']) ? $_GET['r'] : null;
$zi = new ZileEconomice($mysql);
$zi -> getLastDay();
if ($r != "DA") {
    ?>  
    <div align="center">
      <h1><strong>Inchidere zi economica?</strong></h1>
      <h1><strong><?php echo date("d/m/Y", strtotime($zi -> obj -> data)); ?></strong></h1>
      <table width="400"  border="0" cellspacing="5" cellpadding="5">
        <tr>
          <td><div align="center">
            <input name="kb_btnOk" type="button" id="kb_btnOk" value="DA" onClick="window.location.href = 'inchidere.zi.php?r=DA'">
          </div></td>
          <td><div align="center">
            <input name="kb_btnRenunta" type="button" id="kb_btnRenunta" value="NU" onClick="window.location.href = 'login.php'">
          </div></td>
        </tr>
      </table>
    </div>
    <?php
} else {
    $closed = $zi -> closeDay($_SESSION['USERID']);
    ?>
    
    <div align="center">
      <h1><strong>Inchidere zi: <?php echo date("d/m/Y", strtotime($closed -> data)); ?></strong></h1>
      <h1><strong>Se lucreaza in:<?php echo date("d/m/Y", strtotime($zi -> obj -> data)); ?></strong></h1>
      <p>
        <input name="kb_btnRenunta" type="button" id="kb_btnRenunta" value="IESIRE" onClick="window.location.href = 'login.php'">
      </p>
    </div>
    
    <?php
}
?>
    
</div>

<div id="overlay">
</div>
<div id="obiecte" >
</div>
</div>
</div>

</body>
</html>
