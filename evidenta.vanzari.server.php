<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("evidenta.vanzari.common.php");

$mysql = new MySQL;
$html = new Html;

function genereazaRaport($frmFiltre)
{
    global $mysql;
    $filtre[] = "WHERE";
    $filtre['data'] = $mysql -> between($frmFiltre['dateStart'], $frmFiltre['dateStop']);
    if ($frmFiltre['casa_id']) {
        $filtre[] = "AND";
        $filtre['casa_id'] = $mysql -> equal($frmFiltre['casa_id']);
    }
    if ($frmFiltre['user_id']) {
        $filtre[] = "AND";
        $filtre['user_id'] = $mysql -> equal($frmFiltre['user_id']);
    }
    if ($frmFiltre['mod_plata_id']) {
        $filtre[] = "AND";
        $filtre['mod_plata_id'] = $mysql -> equal($frmFiltre['mod_plata_id']);
    }
    $filtre[] = "group by denumire,denumire_categorie,nume_mod , valoare order by nume_mod, denumire_categorie, denumire asc";
    $vanzari = new ViewVanzari($mysql);
    $objResponse = new xajaxResponse();
    if ($vanzari -> find($filtre, array("denumire", "denumire_categorie", "nume_mod" , "sum(cantitate) as cantitate", "valoare"))) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("PRODUS", "Cant", "Total");
        $gv -> tableOptions['ColWidth'] = array("50", "50","50");
        $i=0;
        $cat = "";
        $mod = "";
        $totalCat = 0;
        $totalMod = 0;
        $total = 0;
        for ($j=0; $j<count($vanzari -> objects); $j++) {
            $objBon = $vanzari -> objects[$j];
            if ($mod != $objBon -> nume_mod) {
                $mod = $objBon -> nume_mod;
                $gv -> dataTable[$i]['data'] = array('<strong>'.$objBon -> nume_mod.'</strong>', "","");
                $i++;
            }
            if ($cat != $objBon -> denumire_categorie) {
                $cat = $objBon -> denumire_categorie;
                $gv -> dataTable[$i]['data'] = array('<strong>--'.$objBon -> denumire_categorie.'</strong>', "","");
                $i++;
            }
            $gv -> dataTable[$i]['data'] = array($objBon -> denumire, $objBon -> cantitate, number_format($objBon -> cantitate*$objBon -> valoare, 2, '.', ''));
            $i++;
            $totalCat += $objBon -> cantitate*$objBon -> valoare;
            $totalMod += $objBon -> cantitate*$objBon -> valoare;
            $total += $objBon -> cantitate*$objBon -> valoare;
            if ($cat != $vanzari -> objects[$j+1] -> denumire_categorie) {
                $gv -> dataTable[$i]['data'] = array('<strong>--Total cat</strong>', "", number_format($totalCat, 2, '.', ''));
                $totalCat = 0;
                $i++;
            }
            if ($mod != $vanzari -> objects[$j+1] -> nume_mod) {
                $gv -> dataTable[$i]['data'] = array('<strong>Total mod</strong>', "", number_format($totalMod, 2, '.', ''));
                $totalMod = 0;
                $i++;
            }
    
            $i++;
        }
            $gv -> dataTable[$i]['data'] = array('<strong>Total Vanzari</strong>', "", number_format($total, 2, '.', ''));
        $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
    } else {
        $objResponse -> assign("preview", "innerHTML", "NU SUNT BONURI CE INDEPLINSESC CRITERIILE DE CAUTARE!");
    }
    $objResponse -> assign("bon_continut", "innerHTML", "");
    return $objResponse;
}

$xajax->processRequest();
