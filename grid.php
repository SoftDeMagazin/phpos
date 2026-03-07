<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<script type="text/javascript" src="js/ui/jquery-1.2.6.js"></script>        
<script type="text/javascript">


</script>
<body >
<?php
include("include/db/mysql.class.php");
$mysql = new MySQL();
$rows = $mysql -> getRows("SELECT denumire from produse");
?>
<select name="select" size="20" multiple style="width:300px;" onChange=""  accesskey="a">
<?php
foreach ($rows as $row) {
    echo '<option value="' . $row['denumire'] . '">' . $row['denumire'] . '</option>';
}
?>
</select>
<?php
$rows = $mysql -> getRows("SELECT denumire from produse");
?>
<select name="furnizori" size="1" multiple style="width:300px;" onChange=""  accesskey="b">
<?php
foreach ($rows as $row) {
    echo '<option value="' . $row['denumire'] . '">' . $row['denumire'] . '</option>';
}
?>
</select>
</body>
</html>
