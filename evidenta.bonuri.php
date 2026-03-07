<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>EVIDENTA BONURI</title>
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
require_once("evidenta.bonuri.common.php");
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
<form action="" method="post" name="frmFiltre" id="frmFiltre" >
<br><br>

  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200"><div align="center">
      <input name="dateStart" id="dateStart" type="text" value="<?php echo $zi -> obj -> data; ?>" readonly="" onClick="xajax_calPopup('','', 'dateStart')">
    </div></td>
    <td width="200"><div align="center">
      <input name="dateStop" type="text" id="dateStop" value="<?php echo $zi -> obj -> data; ?>" readonly="" onClick="xajax_calPopup('','', 'dateStop')">
    </div></td>
    <td width="200">
    <div align="center">
    <?php
        $options = null;
    $frm = new Forms();
                $ModuriPlata = new ModuriPlata($mysql);
            $ModuriPlata -> find(array("ORDER BY nume_mod ASC"));
            $options['0'] = "Filtru Moduri plata";
    if (isset($ModuriPlata -> objects)) {
        foreach ($ModuriPlata -> objects as $objCasa) {
            $options[$objCasa -> mod_plata_id] = $objCasa -> nume_mod;
        }
    }
            echo $frm -> input("mod_plata_id", array("options" => $options));

    ?></div></td>
    <td width="200"><div align="center">
      <?php
        $options = null;
            $case = new CaseFiscale($mysql);
            $case -> find(array("ORDER BY nume_casa ASC"));
            $options['0'] = "Filtru Case";
        if (isset($case -> objects)) {
            foreach ($case -> objects as $objCasa) {
                $options[$objCasa -> casa_id] = $objCasa -> nume_casa;
            }
        }
            echo $frm -> input("casa_id", array("options" => $options));
        ?>
    </div></td>
  </tr>
  <tr>
    <td></td>
    <td>&nbsp;</td>
    <td>    <div align="center"><?php
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
    ?></div>    </td>
    <td>
    <div align="center">
      <input name="btnGenereaza" type="button" id="btnGenereaza" value="Genereaza Raport" onClick="xajax_genereazaRaport(xajax.getFormValues('frmFiltre'));">   
      </div>
    </td>
  </tr>
</table>
</form>

<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="550" width="420" valign="top">
    <div id="preview" style="width:420px; height: 500px;overflow:scroll; margin:20px auto;">
    </div>
    </td>
    <td valign="top" width="580">
    <div id="bon_continut" style="height: 500px;overflow:scroll;margin-top: 20px;">
      
    </div></td>
  </tr>
  <tr>
    <td width="420"><div align="center">
      <input name="btnStergeBonuri" type="button" class="btnTouch" id="btnStergeBonuri" value="Sterge Bonuri " onClick="r=confirm('Sterg bonurile afisate? Atentie datele nu vor putea fi recuperate!'); if(r==true){xajax_stergeBonuri(xajax.getFormValues('frmFiltre'));}">
    </div></td>
    <td width="580"><div align="center">
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
