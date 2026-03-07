<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("evidenta.bonuri.common.php");

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
    $bonuri = new ViewBonuriModuri($mysql);
    $objResponse = new xajaxResponse();
    if ($bonuri -> find($filtre)) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Nr Bon", "Total", "Achitat", "Mod Plata", "Ora");
        $gv -> tableOptions['ColWidth'] = array("50", "50","50", "100", "50");
        $i=0;
        foreach ($bonuri -> objects as $objBon) {
            $gv -> dataTable[$i]['data'] = array($objBon -> numar_bon, $objBon -> total, $objBon -> suma, $objBon -> nume_mod, substr($objBon -> data_ora, 10));
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            $gv -> dataTable[$i]['tag'] = array("class" => $class,
            "onClick" => "xajax_loadBonInfo(". $objBon -> bon_id .")"
            );
            $i++;
        }
        $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
    } else {
        $objResponse -> assign("preview", "innerHTML", "NU SUNT BONURI CE INDEPLINSESC CRITERIILE DE CAUTARE!");
    }
    $objResponse -> assign("bon_continut", "innerHTML", "");
    return $objResponse;
}

function loadBonInfo($bon_id)
{
    global $mysql;
    global $html;
    $Bon = new Bonuri($mysql, $bon_id);
    $BonModuri = new ViewBonuriModuri($mysql);
    $BonModuri -> findAllBy("bon_id", $bon_id);
    $Comanda = new Comenzi($mysql, $Bon -> obj -> comanda_id);
    $innerHTML = "";
    $info = '
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr class="rowhead">
          <td>Total</td>
          <td>Casa</td>
          <td>Casier</td>
        </tr>
        <tr>
          <td>'. number_format($BonModuri -> objects[0] -> total, 2, '.', '').'</td>
          <td>'. $BonModuri -> objects[0] -> nume_casa.'</td>
          <td>'. $BonModuri -> objects[0] -> nume .'</td>
        </tr>
      </table>
	';
    $moduri = '
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" class="rowhead">Achitat</td>
          </tr>
        <tr class="rowhead">
          <td>Mod Plata </td>
          <td>Suma</td>
        </tr>
	';
    foreach ($BonModuri -> objects as $objBon) {
        $html -> append($moduri, '<tr>
          <td>'. $objBon -> nume_mod .'</td>
          <td>'. number_format($objBon -> suma, 2, '.', '') .'</td>
        </tr>');
    }
    $html -> append($moduri, '<tr>
          <td>Total</td>
          <td>'. number_format($BonModuri -> objects[0] -> total, 2, '.', '') .'</td>
        </tr>');
    $html -> append($moduri, '<tr>
          <td>Factura</td>
          <td><input type="button" value="DESCHIDE COMANDA" onClick="window.location.href = \'comanda.php?comanda_id='.$Comanda -> obj -> comanda_id.'\';"</td>
        </tr>');
        $html -> append($moduri, '</table>');
    $html -> append($innerHTML, $Comanda -> comandaContinut(false));
    $html -> append($innerHTML, $info);
    $html -> append($innerHTML, $moduri);
    $objResponse = new xajaxResponse();
    $objResponse -> assign("bon_continut", "innerHTML", $innerHTML);
    return $objResponse;
}
    
function stergeBonuri($frmFiltre)
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
    $sql = "SELECT bon_id FROM view_bonuri_moduri ".$mysql -> arrayToSql($filtre);
    $rows  = $mysql -> getRows($sql);
    if (isset($rows)) {
        $i = 0;
        $cvs = "";
        foreach ($rows as $row) {
            if ($i == 0) {
                $cvs .= "(".$row['bon_id'];
            } else {
                $cvs .= ",".$row['bon_id'];
            }
            $i++;
        }
        $cvs .= ")";
    }
    $mysql -> query("DELETE FROM bonuri WHERE bon_id in $cvs");
    $mysql -> query("DELETE FROM bonuri_continut WHERE bon_id in $cvs");
    $mysql -> query("DELETE FROM bonuri_plata WHERE bon_id in $cvs");
    $objResponse = genereazaRaport($frmFiltre);
    return $objResponse;
}

$xajax->processRequest();
