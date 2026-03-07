<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("nir.etichete.common.php");

$html = new Html;

function genereazaRaport($frm)
{
    global $mysql;
    $rpt = new RaportIntrari($frm);
    $objResponse = new xajaxResponse();
    $objResponse -> assign("preview", "innerHTML", $rpt -> genereazaRaport());
    return $objResponse;
}


$xajax->processRequest();
