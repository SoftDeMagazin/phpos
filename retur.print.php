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
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'evidenta.retur.php'">
  </div>
</div>
<?php

$frmFiltre = $_POST;
    $rows = $mysql -> getRows("
select produse.denumire, users.nume, cantitate, valoare, data, ora from retururi
inner join produse using(produs_id)
inner join users on users.user_id = retururi.utilizator_id
where data between '" . $frmFiltre['dataStart'] . "' and '" . $frmFiltre['dataStop'] . "'
	");
    $nr_r = count($rows);
    if ($nr_r) {
                    $gv = new GridView();
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 1, "cellspacing" => 0, "cellpadding" => 0, "align" => "left");
                    $gv -> tableOptions['head'] = array("class" => "rowhead");
                    $gv -> columns = array("Articol", "Cantitate", "Pret", "Data", "Ora", "User");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i = 0; $i < $nr_r; $i++) {
            $obj = $rows[$i];
            $gv -> dataTable[$i]['data'] = array($obj['denumire'], $obj['cantitate'], $obj['valoare'], $obj['data'], $obj['ora'], $obj['nume']);

            if ($i % 2 == 0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }

            $gv -> dataTable[$i]['tag'] = array("class" => $class,
            "onMouseOver" => "$(this).addClass('rowhover')",
            "onMouseOut" => "$(this).removeClass('rowhover')",
            );
        }
        $d = $gv -> getTable();
    } else {
        $d = "NU SUNT INREGISTRARI";
    }

    echo $d;
    ?>
</body>
</html>
