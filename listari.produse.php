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

form {
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
include("include/models/unitatimasura.class.php");
include("include/models/furnizori.class.php");

$mysql = new MySQL();
$Produse = new Produse($mysql);
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'login.php'">
  </div>
</div>

<form action="" method="post" name="frmListare" id="frmListare">
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="188"><strong>Categorie</strong></td>
      <td width="212"><?php echo $Produse -> input('categorie_id') ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="right">
        <input type="submit" name="Submit" value="Genereaza">
      </div></td>
    </tr>
  </table>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $produse = new ViewStocuriProduse($mysql);
    $categorie_id = $_POST['categorie_id'];
    $produse -> find("where categorie_id = '$categorie_id' order by denumire asc");
    if (isset($produse -> objects)) {
        echo '<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0" id="componente">
  <tr>
    <td><strong>Denumire produs </strong></td>
    <td><strong>Pret</strong></td>
    <td><strong>Stoc scriptic </strong></td>
    <td><strong>Stoc Faptic </strong></td>
  </tr>';
        foreach ($produse -> objects as $obj) {
            if ($obj -> stoc == null) {
                $stoc = 0;
            } else {
                $stoc = $obj -> stoc;
            }
             echo '<tr>
    	<td>' . $obj -> denumire . '</td>
    	<td>' . $obj -> pret . '</td>
    	<td>' . number_format($stoc, 2) . '</td>
    	<td>&nbsp;</td>
  		</tr>';
        }
        echo '</table>';
    }
}
?>



</body>
</html>
