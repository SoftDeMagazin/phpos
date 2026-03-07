<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>[printable]</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}

h2 {
margin-bottom: 4px;
}
#componente {
border: 1px solid #000;
border-top: 0px solid #000;
border-left: 0px solid #000;
}
#componente td {
border-top:1px solid #000;
border-left:1px solid #000;
}
#componente th {
border-top:1px solid #000;
border-left:1px solid #000;
}
-->
</style>
<style type="text/css" media="print">
input[type=button] {
display:none;
}
</style>
</head>

<body>
<?php
/*         HELPERS         */
include("include/helpers/helper.class.php");
include("include/helpers/forms.class.php");
include("include/helpers/html.class.php");
include("include/helpers/tabView.class.php");
include("include/helpers/gridView.class.php");
include("include/helpers/gui.class.php");
include("include/helpers/print.class.php");

/*         DB              */
include("include/db/abstractdb.class.php");
include("include/db/mysql.class.php");


include("include/models/produse.class.php");
include("include/models/categorii.class.php");
include("include/models/comenzi.class.php");
include("include/models/comenzicontinut.class.php");
include("include/models/bonuri.class.php");
include("include/models/bonuricontinut.class.php");
include("include/models/bonuriplata.class.php");
include("include/models/moduriplata.class.php");
include("include/models/fiscal.class.php");
include("include/models/users.class.php");
include("include/models/zileeconomice.class.php");

include("include/rapoarte/rapoarte.class.php");
include("include/rapoarte/rptModuriPlata.class.php");
include("include/rapoarte/rptVanzari.class.php");
include("include/rapoarte/rptVanzariTigari.class.php");
include("include/rapoarte/rptUtilizatoriModuri.class.php");
include("include/rapoarte/rptBonuriEmise.class.php");
include("include/rapoarte/rptCaseModuri.class.php");

include("include/libchart/classes/libchart.php");
include("config/config.php");

$mysql = new MySQL();
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'rapoarte.php'">
  </div>
</div>


<?php
    $mod = $_GET['raport'];
    $dateStart = $_GET['dateStart'];
    $dateStop = $_GET['dateStop'];
    $chkGrafic = $_GET['chkGrafic'];
switch ($mod) {
    case "rptModuriPlata":
        {
        $rpt = new rptModuriPlata($mysql);
        echo $rpt -> preview($dateStart, $dateStop);
        if ($chkGrafic == "DA") {
            if ($file = $rpt -> pie($dateStart, $dateStop)) {
                echo '<img src="temp/' . $file . '.png">';
            }
        } else {
        }
    }break;
    case "rptVanzari":
        {
        $rpt = new rptVanzari($mysql);
        echo $rpt -> preview($dateStart, $dateStop);
        if ($chkGrafic == "DA") {
            if ($file = $rpt -> pie($dateStart, $dateStop)) {
                echo '<img src="temp/' . $file . '.png">';
            }
        } else {
        }
    }break;
    case "rptVanzariTigari":
            {
            $rpt = new rptVanzariTigari($mysql);
            echo $rpt -> preview($dateStart, $dateStop);
        if ($chkGrafic == "DA") {
            if ($file = $rpt -> pie($dateStart, $dateStop)) {
                echo '<img src="temp/' . $file . '.png">';
            }
        } else {
        }
    }break;
    case "rptUtilizatoriModuri":
        {
        $rpt = new rptUtilizatoriModuri($mysql);
        echo $rpt -> preview($dateStart, $dateStop);
        if ($chkGrafic == "DA") {
            if ($file = $rpt -> pie($frmValues['dateStart'], $frmValues['dateStop'])) {
                echo '<img src="temp/' . $file . '.png">';
            }
        }
    }break;
    case "rptCaseModuri":
        {
        echo $rpt = new rptCaseModuri($mysql);
        $rpt -> preview($dateStart, $dateStop);

    }break;

    case "rptBonuriEmise":
        {
        echo $rpt = new rptBonuriEmise($mysql);
        $rpt -> preview($dateStart, $dateStop);
    }break;
}
?>
</body>
</html>

