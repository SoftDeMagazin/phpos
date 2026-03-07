<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>DREPTURI UTILIZATORI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css">
<link type="text/css" rel="stylesheet" href="css/config.produse.css">
<script type="text/javascript" src="js/ui/jquery-1.2.6.js"></script> 
<script type="text/javascript" src="js/selectbox.js"></script>         
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
require_once("default.common.php");
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
$user_id = $_GET['user_id'];
$user = new Users($mysql, $user_id);
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['actiune']) {
        case "drepturi":
            {
            $mysql -> query("DELETE FROM drepturi_users WHERE user_id = '$user_id';");
            $drepturi = explode(" ", $_POST['valoriDestinatieDrepturi']);
            if (isset($drepturi)) {
                foreach ($drepturi as $drept) {
                    if (!empty($drept)) {
                        $du = new DrepturiUsers($mysql);
                        $du -> setObjId(0);
                        $du -> setObjValue("user_id", $user_id);
                        $du -> setObjValue("drept_id", $drept);
                        $du -> save();
                    }
                }
            }
        }break;
    }
}
?>
<form action="" method="post" name="frmProdus" id="frmProdus">
        <table width="600" border="0" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td><div align="left"><strong>Utilizator
              <input name="actiune" type="hidden" id="actiune" value="drepturi">
            </strong></div></td>
            <td><div align="left"><?php echo $user -> obj -> nume; ?></div></td>
          </tr>
          <tr>
            <td colspan="2">
            
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><strong>Drepturi <input name="actiune" type="hidden" id="actiune" value="drepturi">
            </strong></td>
            <td>&nbsp;</td>
            <td><strong>Drepturi Utilizator </strong></td>
          </tr>
          <tr>
            <td>
            <select name="sursaDrepturi" id="sursaDrepturi" size="5" style="width:200px; height:200px;  ">
            <?php
            $drepturi = new Drepturi($mysql);
            $drepturi -> findAll();
            if (isset($drepturi -> objects)) {
                foreach ($drepturi -> objects as $objDrept) {
                    echo '<option value="' . $objDrept -> drept_id . '">' . $objDrept -> nume_drept . '</option>';
                }
            }
            ?>
            </select></td>
            <td><div align="center">
                <p>
                  <input  name="btnDreapta" type="button" class="btnTouch" id="btnDreapta2" onclick="copySelectedOptions(document.forms[0]['sursaDrepturi'],document.forms[0]['destinatieDrepturi'],false);return false;" value=">">
</p>
                <p>
                  <input name="btnSterge" type="button" class="btnTouch" id="btnSterge" onclick="removeSelectedOptions(document.forms[0]['destinatieDrepturi']); return false;" value="STERGE">          
                    </p>
            </div></td>
            <td>
              <div align="center">
              <select name="destinatieDrepturi" id="destinatieDrepturi" size="5" style="width:200px; height:200px; " multiple>
             <?php
                $rows = $mysql -> getObjects("SELECT drepturi.drept_id, drepturi.nume_drept 
			FROM drepturi_users 
			INNER JOIN drepturi on drepturi_users.drept_id = drepturi.drept_id
			WHERE user_id = '$user_id'");
                if (isset($rows)) {
                    foreach ($rows as $objDrept) {
                        echo '<option value="' . $objDrept -> drept_id . '">' . $objDrept -> nume_drept . '</option>';
                    }
                }
                ?>
              </select>
              <input name="valoriDestinatieDrepturi" type="hidden" id="valoriDestinatieDrepturi" value="">
                </div></td>
          </tr>
        </table>
            
            </td>
          </tr>
      </table>
        <table width="600"  border="0" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td>
              <div align="left">
                <input name="btnSalveaza" type="button" class="btnTouch" id="btnSalveaza" value="Salveaza" onClick="copyAllValues(document.forms[0]['destinatieDrepturi'], document.forms[0]['valoriDestinatieDrepturi']);document.forms[0].submit();">
              </div></td>
            <td><input name="btnCautareAvansata" type="button" class="btnTouch" id="btnCautareAvansata" onClick="window.location.href = 'login.php'" value="IESIRE"></td>
          </tr>
      </table>
    </form>


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
