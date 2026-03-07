<?php
session_start();
header("Cache-control: private"); // IE 6 Fix
if (!isset($_SESSION['USERID'])) {
    header("Location: login.php");
}
