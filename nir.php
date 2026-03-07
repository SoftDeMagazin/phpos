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
include("include/models/niruri.class.php");
include("include/models/niruricomponente.class.php");
include("include/models/unitatimasura.class.php");
include("include/models/furnizori.class.php");

$mysql = new MySQL();
$nir_id = $_GET['nir_id'];
$nir = new Niruri($mysql, $nir_id);
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'evidenta.niruri.php'">
  </div>
</div>
<?php
echo $nir -> preview();
?>
</body>
</html>
