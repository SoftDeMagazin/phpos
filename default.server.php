<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("default.common.php");

$mysql = new MySQL;
$html = new Html;

$xajax->processRequest();
