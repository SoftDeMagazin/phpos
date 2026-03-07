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
include("include/models/transformari.class.php");
$mysql = new MySQL();
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'evidenta.transformari.php'">
  </div>
</div>
<?php

$frmFiltre = $_POST;
    $trans = new Transformari($mysql);
    $nr_r = $trans -> find(array(
    "where",
    "data_transformare" => $mysql -> between($frmFiltre['dataStart'], $frmFiltre['dataStop']),
    "order by data_transformare asc"
    ));
    echo '<h3>Transformari</h3>';
    if ($nr_r) {
                    $gv = new GridView();
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 1, "cellspacing" => 0, "cellpadding" => 0, "align" => "center");
                    $gv -> tableOptions['head'] = array("class" => "rowhead");
                    $gv -> columns = array("Data", "Articol sursa", "Cantitate", "Articol Destinatie", "Cantitate");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i = 0; $i < $nr_r; $i++) {
            $obj = $trans -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> data_transformare, $obj -> sursa_denumire, $obj -> sursa_cantitate, $obj -> destinatie_denumire, $obj -> destinatie_cantitate);
            if ($i % 2 == 0) {
                $class = "";
            } else {
                $class = "";
            }

            $gv -> dataTable[$i]['tag'] = array("class" => $class);
        }
        $d = $gv -> getTable();
    } else {
        $d = "NU SUNT INREGISTRARI";
    }

    echo $d;
    echo '<h3>Produse sursa</h3>';
    $sursa = $mysql -> getRows("select produse.denumire, a.produs_id, sum(a.cantitate) as cantitate, round(sum(a.pret_intrare*a.cantitate),2) as valoare, round(sum((b.pret_vanzare*100/124 - a.pret_intrare)*a.cantitate),2) as adaos
from intrari_continut as a
inner join produse using(produs_id)
inner join intrari_continut as b on b.intrare_continut_id = a.nir_componenta_id
where a.tip = 'ajustare_transformare' and a.data between '" . $frmFiltre['dataStart'] . "' and '" . $frmFiltre['dataStop'] . "'
group by produse.denumire, a.produs_id;");
                    $gv = new GridView();
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 1, "cellspacing" => 0, "cellpadding" => 0, "align" => "center");
                    $gv -> tableOptions['head'] = array("class" => "rowhead");
                    $gv -> columns = array("Articol", "Cantitate", "Valoare", "Adaos");
                    $gv -> tableOptions['ColWidth'] = array();
    for ($i = 0; $i < count($sursa); $i++) {
        $gv -> dataTable[$i]['data'] = array($sursa[$i]['denumire'], $sursa[$i]['cantitate'] * (-1), $sursa[$i]['valoare'] * (-1), $sursa[$i]['adaos'] * (-1));
        if ($i % 2 == 0) {
            $class = "";
        } else {
            $class = "";
        }
        $totalVal +=  $sursa[$i]['valoare'] * (-1);
        $totalAdaos += $sursa[$i]['adaos'] * (-1);
        $gv -> dataTable[$i]['tag'] = array("class" => $class);
    }
                        $gv -> dataTable[count($sursa)]['data'] = array('Total', '&nbsp;', $totalVal, $totalAdaos);
    echo $gv -> getTable();

    echo '<h3>Produse destinatie</h3>';
    $destinatie = $mysql -> getRows("
select produse.denumire, produse.produs_id, sum(intrari_continut.cantitate) as cantitate, round(sum(intrari_continut.pret_intrare*intrari_continut.cantitate),2) as valoare from intrari_continut 
inner join produse using(produs_id)
where tip = 'transformare_destinatie' and intrari_continut.data between '" . $frmFiltre['dataStart'] . "' and '" . $frmFiltre['dataStop'] . "'
group by produse.denumire, produse.produs_id, intrari_continut.pret_intrare; 
");
                    $gv = new GridView();
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 1, "cellspacing" => 0, "cellpadding" => 0, "align" => "center");
                    $gv -> tableOptions['head'] = array("class" => "rowhead");
                    $gv -> columns = array("Articol", "Cantitate", "Valoare");
                    $gv -> tableOptions['ColWidth'] = array();
    for ($i = 0; $i < count($destinatie); $i++) {
        $gv -> dataTable[$i]['data'] = array($destinatie[$i]['denumire'], $destinatie[$i]['cantitate'], $destinatie[$i]['valoare']);
        if ($i % 2 == 0) {
            $class = "";
        } else {
            $class = "";
        }
        $totalMp += $destinatie[$i]['valoare'];
        $gv -> dataTable[$i]['tag'] = array("class" => $class);
    }
                    $gv -> dataTable[count($destinatie)]['data'] = array('Total','&nbsp;', $totalMp);
    echo $gv -> getTable();
    ?>
</body>
</html>
