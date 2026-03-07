<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("evidenta.inventar.common.php");

$mysql = new MySQL;
$html = new Html;

function genereazaLista($frmFiltre)
{
    $objResponse = new xajaxResponse();
    global $mysql;
    $filtre[] = "WHERE";
    $filtre['data'] = $mysql -> between($frmFiltre['dateStart'], $frmFiltre['dateStop']);
    if ($frmFiltre['user_id']) {
        $filtre[] = "AND";
        $filtre['user_id'] = $mysql -> equal($frmFiltre['user_id']);
    }
    if (!empty($frmFiltre['numar_inventar'])) {
        $filtre = array("WHERE", $frmFiltre['nr'] => $mysql -> equal($frmFiltre['numar_inventar']));
    }
    
    $niruri = new Inventar($mysql);
    $nr_r = $niruri -> find($filtre);
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Numar Inventar", "Data", "Adaugat de");
        $gv -> tableOptions['ColWidth'] = array("25%", "25%", "25%", "25%");
        $i=0;
        foreach ($niruri -> objects as $obj) {
            $user = new Users($mysql, $obj -> user_id);
            $gv -> dataTable[$i]['data'] = array($obj -> numar_inventar, $obj -> data, $user -> obj -> nume);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            $gv -> dataTable[$i]['tag'] = array("class"=> $class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"xajax_selectInventar(". $obj -> inventar_id .")",
            );
            $i++;
        }
        $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
        $objResponse -> assign("actiuni", "innerHTML", '');
    } else {
        $objResponse -> assign("preview", "innerHTML", 'NU SUNT INVENTARIERI CE CORESPUND CRITERIILOR CAUTATE');
        $objResponse -> assign("actiuni", "innerHTML", '');
    }
    return $objResponse;
}

function selectInventar($nir_id)
{
    $objResponse = new xajaxResponse();
    $innerHTML = '
		  <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <th scope="col"><input name="Button" type="button" class="btnTouch" value="Editeaza" onClick="window.location.href = \'inventar.php?inventar_id='. $nir_id .'\'"></th>
          </tr>
      </table>
	';
    $objResponse -> assign("actiuni", "innerHTML", $innerHTML);
    return $objResponse;
}


    
$xajax->processRequest();
