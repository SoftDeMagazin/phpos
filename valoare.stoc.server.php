<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("valoare.stoc.common.php");

$mysql = new MySQL;
$html = new Html;

function genereazaRaport($frmFiltre)
{
    global $mysql;
    if ($frmFiltre['detaliat'] == "DA") {
        if ($frmFiltre['categorie_id']) {
            $filtre[] = "WHERE";
            $filtre['categorie_id'] = $mysql -> equal($frmFiltre['categorie_id']);
        }
        $filtre[] = "order by denumire_categorie, denumire asc";
        $vanzari = new ValoareStoc($mysql);
        $objResponse = new xajaxResponse();
        if ($vanzari -> find($filtre)) {
            $gv = new GridView;
            $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
            $gv -> tableOptions['head'] = array("class"=> "rowhead");
            $gv -> columns = array("PRODUS", "Pret", "Stoc", "ValoareStoc");
            $gv -> tableOptions['ColWidth'] = array("50", "50","50");
            $i=0;
            $cat = "";
            $totalCat = 0;
            $totalMod = 0;
            $total = 0;
            for ($j=0; $j<count($vanzari -> objects); $j++) {
                $objBon = $vanzari -> objects[$j];
                if ($cat != $objBon -> denumire_categorie) {
                    $cat = $objBon -> denumire_categorie;
                    $gv -> dataTable[$i]['data'] = array('<strong>--'.$objBon -> denumire_categorie.'</strong>', "","");
                    $i++;
                    $total += $totalCat;
                    $totalCat = 0;
                }
                $gv -> dataTable[$i]['data'] = array($objBon -> denumire, $objBon -> pret, number_format($objBon -> stoc, 2, '.', ''),  number_format($objBon -> valoare_stoc, 2, '.', ''));
                $i++;
                $totalCat += $objBon -> valoare_stoc;
                if ($cat != $vanzari -> objects[$j+1] -> denumire_categorie) {
                    $gv -> dataTable[$i]['data'] = array('<strong>--Total cat</strong>', "", number_format($totalCat, 2, '.', ''));
                    $i++;
                }
                $i++;
            }
            $gv -> dataTable[$i]['data'] = array('<strong>Total Stoc</strong>', "", number_format($total, 2, '.', ''));
            $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
        } else {
            $objResponse -> assign("preview", "innerHTML", "");
        }
    } else {
        $filtre = array();
        $filtre[] = "group by denumire_categorie order by denumire_categorie";
        $vanzari = new ValoareStoc($mysql);
        $objResponse = new xajaxResponse();
        if ($vanzari -> find($filtre, array("denumire_categorie", "sum(valoare_stoc) as valoare_stoc"))) {
            $gv = new GridView;
            $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
            $gv -> tableOptions['head'] = array("class"=> "rowhead");
            $gv -> columns = array("CATEGORIE", "Valoare Stoc");
            $gv -> tableOptions['ColWidth'] = array("50", "50","50");
            $i=0;
            $cat = "";
            $totalCat = 0;
            $totalMod = 0;
            $total = 0;
            for ($j=0; $j<count($vanzari -> objects); $j++) {
                $objBon = $vanzari -> objects[$j];
                $gv -> dataTable[$i]['data'] = array($objBon -> denumire_categorie, number_format($objBon -> valoare_stoc, 2, '.', ''));
                $i++;
                $total += $objBon -> valoare_stoc;
            }
            $gv -> dataTable[$i]['data'] = array('<strong>Total Stoc</strong>', number_format($total, 2, '.', ''));
            $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
        } else {
            $objResponse -> assign("preview", "innerHTML", "");
        }
    }
    return $objResponse;
}

$xajax->processRequest();
