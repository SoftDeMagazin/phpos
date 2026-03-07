<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>GENERATOR CODURI</title>
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
<style type="text/css" media="print">
input[type=button] {
display:none;
}
</style>
</head>
<body>

<div>
  <div align="center">
    <input type="button" name="Button" value="IESIRE" onClick="window.location.href = 'login.php'">
  </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $dela = $_POST['dela'];
    $panala = $_POST['panala'];
    if (is_numeric($dela) && is_numeric($panala) && $dela < $panala) {
        for ($i = 0; $i + $dela <= $panala; $i++) {
            if ($i % 3 == 0) {
                echo '  </tr>
</table><table width="100%" border="0" align="center" cellpadding="0" cellspacing="5" id="table">
  <tr>';
                if ($i % 30 == 0 && $i != 0) {
                    echo '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>';
                }
            }
            $cod = $i + $dela;
            echo '<td width="33%"  valign="top">
	<div align="center">
	<img src="thirdparty/barcode/html/image.php?code=code39&o=2&t=30&r=2&text=' . $cod . '&f=3&a1=&a2=">
    </div>
	<input name="a" type="text" style="border: 0px; border-bottom:1px solid #000; width:100%  " value="DEN:">		
	<input name="a" type="text" style="border: 0px; border-bottom:1px solid #000; width:100%  " value="PRET:">
	</td>';
        }
    } else {
        echo 'ceva';
    }
} else {
    ?>

  <form name="form1" method="post" action="">
    <h3 align="center">Generator coduri interne </h3>
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><div align="center"><strong>De la </strong></div></td>
        <td>&nbsp;</td>
        <td><div align="center"><strong>Pana la </strong></div></td>
      </tr>
      <tr>
        <td><div align="center">
          <input name="dela" type="text" id="dela">
        </div></td>
        <td>&nbsp;</td>
        <td><div align="center">
          <input name="panala" type="text" id="panala">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="center">
          <input type="submit" name="Submit" value="Genereaza">
        </div></td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>
    <?php
}
?> 
</body>
</html>
