<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("rapoarte.common.php");

$mysql = new MySQL;
$html = new Html;

function loadRaport($frmValues, $mod)
{
    global $mysql;
    $objResponse = new xajaxResponse();
    switch ($mod) {
        case "rptModuriPlata":
            {
            $rpt = new rptModuriPlata($mysql);
            $objResponse -> assign("preview", "innerHTML", $rpt -> preview($frmValues['dateStart'], $frmValues['dateStop']));
            if ($frmValues['chkGrafic'] == "DA") {
                if ($file = $rpt -> pie($frmValues['dateStart'], $frmValues['dateStop'])) {
                    $objResponse -> assign("grafic", "innerHTML", '<img src="temp/'.$file.'.png">');
                }
            } else {
                $objResponse -> assign("grafic", "innerHTML", '');
            }
        }break;
        case "rptVanzari":
            {
            $rpt = new rptVanzari($mysql);
            $objResponse -> assign("preview", "innerHTML", $rpt -> preview($frmValues['dateStart'], $frmValues['dateStop']));
            if ($frmValues['chkGrafic'] == "DA") {
                if ($file = $rpt -> pie($frmValues['dateStart'], $frmValues['dateStop'])) {
                    $objResponse -> assign("grafic", "innerHTML", '<img src="temp/'.$file.'.png">');
                }
            } else {
                $objResponse -> assign("grafic", "innerHTML", '');
            }
        }break;
        case "rptVanzariTigari":
        {
            $rpt = new rptVanzariTigari($mysql);
            $objResponse -> assign("preview", "innerHTML", $rpt -> preview($frmValues['dateStart'], $frmValues['dateStop']));
            if ($frmValues['chkGrafic'] == "DA") {
                if ($file = $rpt -> pie($frmValues['dateStart'], $frmValues['dateStop'])) {
                    $objResponse -> assign("grafic", "innerHTML", '<img src="temp/'.$file.'.png">');
                }
            } else {
                $objResponse -> assign("grafic", "innerHTML", '');
            }
        }break;
        case "rptUtilizatoriModuri":
            {
            $rpt = new rptUtilizatoriModuri($mysql);
            $objResponse -> assign("preview", "innerHTML", $rpt -> preview($frmValues['dateStart'], $frmValues['dateStop']));
            if ($frmValues['chkGrafic'] == "DA") {
                if ($file = $rpt -> pie($frmValues['dateStart'], $frmValues['dateStop'])) {
                    $objResponse -> assign("grafic", "innerHTML", '<img src="temp/'.$file.'.png">');
                }
            }
        }break;
        case "rptCaseModuri":
            {
            $rpt = new rptCaseModuri($mysql);
            $objResponse -> assign("preview", "innerHTML", $rpt -> preview($frmValues['dateStart'], $frmValues['dateStop']));
            $objResponse -> assign("grafic", "innerHTML", '');
        }break;
    
        case "rptBonuriEmise":
            {
            $rpt = new rptBonuriEmise($mysql);
            $objResponse -> assign("preview", "innerHTML", $rpt -> preview($frmValues['dateStart'], $frmValues['dateStop']));
            $objResponse -> assign("grafic", "innerHTML", '');
        }break;
    }
    $objResponse -> assign("rpt", "value", $mod);
    return $objResponse;
}

function printRaport($frm, $mod)
{
    global $mysql;
    $objResponse = new xajaxResponse();
    switch ($mod) {
        case "rptModuriPlata":
            {
            $rpt = new rptModuriPlata($mysql);
        }break;
        case "rptVanzari":
            {
            $rpt = new rptVanzari($mysql);
        }break;
        case "rptUtilizatoriModuri":
            {
            $rpt = new rptUtilizatoriModuri($mysql);
        }break;
        case "rptCaseModuri":
            {
            $rpt = new rptUtilizatoriModuri($mysql);
        }break;
    
        case "rptBonuriEmise":
            {
            $rpt = new rptBonuriEmise($mysql);
        }break;
    }
    
    $objResponse -> script("window.location.href = 'print.raport.php?dateStart=". $frm['dateStart'] ."&dateStop=". $frm['dateStop'] ."&raport=". $mod ."&chkGrafic=".$frm['chkGrafic']."'");
    return $objResponse;
}

$xajax->processRequest();
