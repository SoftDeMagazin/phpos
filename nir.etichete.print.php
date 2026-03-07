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

.tablerow {
page-break-inside:avoid;
}
</style></head>

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
include("include/models/inventar.class.php");
?>
<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'nir.etichete.php'">
  </div>
</div>
<?php
$mysql = new MySQL();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nr_r = count($_POST['denumire']);

    echo '<table width="100%" border=0>';
    $j = 0;
    for ($i = 0; $i < $nr_r; $i++) {
        if ($_POST['chk'][$i]) {
            if ($j % 3 == 0) {
                echo '</tr><tr class="tablerow">';
            }

            switch (strlen($_POST['cod_bare'][$i])) {
                case 8:
                    {
                    $cod = "ean8";
                    $txt = substr($_POST['cod_bare'][$i], 0, 7);
                }break;
                case 13:
                    {
                    $cod = "ean13";
                    $txt = substr($_POST['cod_bare'][$i], 0, 12);
                }break;
                default:
                    {
                    $cod = "code39";
                    $txt = $_POST['cod_bare'][$i];
                }
            }
            if ($txt) {
                $imgCod = '<img src="thirdparty/barcode/html/image.php?code=' . $cod . '&o=1&t=25&r=1&text=' . $txt . '&f=2&a1=&a2=">';
            } else {
                $imgCod = '';
            }
                echo
            '<td class="eticheta" width="300" height="150"><div>' . $imgCod . '</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td colspan="2" scope="col" style="font-size:11px;">' . $_POST['denumire'][$i] . '</td>
		  </tr>
		  <tr>
			<td width="" ><strong>Pret</strong></td>
			<td width="" ><strong style="font-size:19px;">' . number_format($_POST['pret'][$i], 2) . ' LEI</strong></td>
		  </tr>
		  <tr>
			<td ></td>
			<td ></td>
		  </tr>
		</table></td>';
            $j++;
        }
    }


    while ($j % 3 != 0) {
        echo '<td class="eticheta" width="300" >&nbsp;</td>';
        $j++;
    }
    echo '</tr></table>';
}
?>

</body>
</html>
