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
tr {page-break-inside: avoid;}
td {page-break-inside: avoid;}
-->
</style>
<style type="text/css" media="print">
input[type=button] {
display:none;
}
tr {page-break-inside: avoid;}
td {page-break-inside: avoid;}
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
include("include/models/niruri.class.php");
include("include/models/niruricomponente.class.php");
include("include/models/unitatimasura.class.php");
include("include/models/furnizori.class.php");

$mysql = new MySQL();
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'valoare.stoc.php'">
  </div>
</div>
<?php
$frmFiltre = $_POST;
if ($frmFiltre['detaliat'] == "DA") {
    if ($frmFiltre['categorie_id']) {
        $filtre[] = "WHERE";
        $filtre['categorie_id'] = $mysql -> equal($frmFiltre['categorie_id']);
    }
    $filtre[] = "order by denumire_categorie, denumire asc";
    $vanzari = new ValoareStoc($mysql);
    if ($vanzari -> find($filtre)) {
        $gv = new GridView();
        $gv -> tableOptions['tag'] = array("width" => "90%", "border" => 1, "cellspacing" => 0, "cellpadding" => 0, "id" => "componente", "align" => "center");
        $gv -> tableOptions['head'] = array("class" => "rowhead");
        $gv -> columns = array("PRODUS", "Pret", "Stoc", "ValoareStoc");
        $gv -> tableOptions['ColWidth'] = array("50", "50","50");
        $i = 0;
        $cat = "";
        $totalCat = 0;
        $totalMod = 0;
        $total = 0;
        for ($j = 0; $j < count($vanzari -> objects); $j++) {
            $objBon = $vanzari -> objects[$j];
            if ($cat != $objBon -> denumire_categorie) {
                $cat = $objBon -> denumire_categorie;
                $gv -> dataTable[$i]['data'] = array('<strong>--' . $objBon -> denumire_categorie . '</strong>', "&nbsp;","&nbsp;","&nbsp;");
                $i++;
                $total += $totalCat;
                $totalCat = 0;
            }
            $gv -> dataTable[$i]['data'] = array($objBon -> denumire, $objBon -> pret, number_format($objBon -> stoc, 2, '.', ''),  number_format($objBon -> valoare_stoc, 2, '.', ''));
            $i++;
            $totalCat += $objBon -> valoare_stoc;
            if ($cat != $vanzari -> objects[$j + 1] -> denumire_categorie) {
                $gv -> dataTable[$i]['data'] = array('<strong>--Total cat</strong>', "&nbsp;", number_format($totalCat, 2, '.', ''), "&nbsp;");
                $i++;
            }
            $i++;
        }
            $gv -> dataTable[$i]['data'] = array('<strong>Total Stoc</strong>', "&nbsp;", number_format($total, 2, '.', ''), "&nbsp;");
        echo $gv -> getTable();
    } else {
    }
} else {
    $filtre = array();
    $filtre[] = "group by denumire_categorie order by denumire_categorie";
    $vanzari = new ValoareStoc($mysql);
    if ($vanzari -> find($filtre, array("denumire_categorie", "sum(valoare_stoc) as valoare_stoc"))) {
        $gv = new GridView();
        $gv -> tableOptions['tag'] = array("width" => "90%", "border" => 1, "cellspacing" => 0, "cellpadding" => 0, "id" => "componente", "align" => "center");
        $gv -> tableOptions['head'] = array("class" => "rowhead");
        $gv -> columns = array("CATEGORIE", "Valoare Stoc");
        $gv -> tableOptions['ColWidth'] = array("50", "50","50");
        $i = 0;
        $cat = "";
        $totalCat = 0;
        $totalMod = 0;
        $total = 0;
        for ($j = 0; $j < count($vanzari -> objects); $j++) {
            $objBon = $vanzari -> objects[$j];
            $gv -> dataTable[$i]['data'] = array($objBon -> denumire_categorie, number_format($objBon -> valoare_stoc, 2, '.', ''));
            $i++;
            $total += $objBon -> valoare_stoc;
        }
            $gv -> dataTable[$i]['data'] = array('<strong>Total Stoc</strong>', number_format($total, 2, '.', ''));
        echo $gv -> getTable();
    } else {
    }
}
?>
</body>
</html>
