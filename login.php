<?php
session_start();
header("Cache-control: private"); // IE 6 Fix
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>LOGIN</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css">
<link type="text/css" rel="stylesheet" href="css/config.produse.css">
<style type="text/css">
<!--
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
    background-image:url(files/img/bg.jpg)
}
-->
</style>
<?php
require_once("login.common.php");

$xajax->printJavascript('thirdparty/xajax/');
?>
<?php
$tn = new TastaturaNumerica();
echo $tn -> printJavaScript();
$kb = new KeyBoard();
echo $kb -> printJavaScript();
$tabView = new TabView();
$tabView -> root = "";
echo $tabView -> printCss();
echo $tabView -> printJavaScript();
?>
</head>
<?php
/*         DB              */
include("include/db/abstractdb.class.php");
include("include/db/mysql.class.php");
$mysql = new MySQL();
include("include/models/users.class.php");
include("include/models/casefiscale.class.php");
?>
<body>

<div id="main">
<div id="layout" style="background-image:url(files/img/bg-omulet.gif); background-position:center; background-repeat:no-repeat; ">
<?php
if (!isset($_SESSION['USERID'])) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $user = new Users($mysql, $_POST['user_id']);
        if ($user -> obj -> parola == $_POST['parola'] && $user -> obj -> activ == "DA") {
            $_SESSION['USERID'] = $user -> obj -> user_id;
            $_SESSION['CASAID'] = $_POST['casa_id'];
            include("views/login/meniu.php");
        } else {
            include("views/login/login.php");
            echo $kb -> html('parola', 'parola');
        }
    } else {
        include("views/login/login.php");
        echo $kb -> html('parola', 'parola');
    }
} else {
    $user = new Users($mysql, $_SESSION['USERID']);
    include("views/login/meniu.php");
}
?>
<div id="overlay">
</div>
<div id="obiecte" class="flora">
</div>
</div>
</div>

</body>
</html>
