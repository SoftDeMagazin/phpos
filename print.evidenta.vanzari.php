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
include("include/models/zileeconomice.class.php");
include("include/models/users.class.php");
include("include/models/casefiscale.class.php");
include("include/models/clienti.class.php");
include("include/models/facturiere.class.php");
include("include/models/facturi.class.php");
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'login.php'">
  </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $frmFiltre = $_POST;
    $mysql = new MySQL();
    $filtre[] = "WHERE";
    $filtre['data'] = $mysql -> between($frmFiltre['dateStart'], $frmFiltre['dateStop']);
    if ($frmFiltre['casa_id']) {
        $filtre[] = "AND";
        $filtre['casa_id'] = $mysql -> equal($frmFiltre['casa_id']);
    }
    if ($frmFiltre['user_id']) {
        $filtre[] = "AND";
        $filtre['user_id'] = $mysql -> equal($frmFiltre['user_id']);
    }
    if ($frmFiltre['mod_plata_id']) {
        $filtre[] = "AND";
        $filtre['mod_plata_id'] = $mysql -> equal($frmFiltre['mod_plata_id']);
    }
    $filtre[] = "group by denumire,denumire_categorie,nume_mod , valoare order by nume_mod, denumire_categorie, denumire asc";
    $vanzari = new ViewVanzari($mysql);
    if ($vanzari -> find($filtre, array("denumire", "denumire_categorie", "nume_mod" , "sum(cantitate) as cantitate", "valoare"))) {
        $gv = new GridView();
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding" => 0, "align" => "left");
        $gv -> tableOptions['head'] = array("class" => "rowhead");
        $gv -> columns = array("PRODUS", "Cant", "Total");
        $gv -> tableOptions['ColWidth'] = array("50", "50","50");
        $i = 0;
        $cat = "";
        $mod = "";
        $totalCat = 0;
        $totalMod = 0;
        $total = 0;
        for ($j = 0; $j < count($vanzari -> objects); $j++) {
            $objBon = $vanzari -> objects[$j];
            if ($mod != $objBon -> nume_mod) {
                $mod = $objBon -> nume_mod;
                $gv -> dataTable[$i]['data'] = array('<strong>' . $objBon -> nume_mod . '</strong>', "","");
                $i++;
            }
            if ($cat != $objBon -> denumire_categorie) {
                $cat = $objBon -> denumire_categorie;
                $gv -> dataTable[$i]['data'] = array('<strong>--' . $objBon -> denumire_categorie . '</strong>', "","");
                $i++;
            }
            $gv -> dataTable[$i]['data'] = array($objBon -> denumire, $objBon -> cantitate, number_format($objBon -> cantitate * $objBon -> valoare, 2, '.', ''));
            $i++;
            $totalCat += $objBon -> cantitate * $objBon -> valoare;
            $totalMod += $objBon -> cantitate * $objBon -> valoare;
            $total += $objBon -> cantitate * $objBon -> valoare;
            if ($cat != $vanzari -> objects[$j + 1] -> denumire_categorie) {
                $gv -> dataTable[$i]['data'] = array('<strong>--Total cat</strong>', "", number_format($totalCat, 2, '.', ''));
                $totalCat = 0;
                $i++;
            }
            if ($mod != $vanzari -> objects[$j + 1] -> nume_mod) {
                $gv -> dataTable[$i]['data'] = array('<strong>Total mod</strong>', "", number_format($totalMod, 2, '.', ''));
                $totalMod = 0;
                $i++;
            }

            $i++;
        }
            $gv -> dataTable[$i]['data'] = array('<strong>Total Vanzari</strong>', "", number_format($total, 2, '.', ''));
        echo $gv -> getTable();
    } else {
        echo "RAPORTUL NU CONTINE INREGISTRARI";
    }
}
?>
</body>
</html>
