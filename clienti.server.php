<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("clienti.common.php");

$mysql = new MySQL();
$html = new Html;
function lista($litera = null, $filtre = array())
{
    global $mysql;
    $objResponse = new xajaxResponse();
            $cls = new Clienti($mysql);
    if (!$filtre) {
        if (!$litera) {
            $nr_r = $cls -> find(array("ORDER BY", "denumire", "ASC"));
        } else {
            $nr_r = $cls -> find(array("WHERE", "denumire like '$litera%'", "ORDER BY", "denumire", "ASC"));
        }
    } else {
        $nr_r = $cls -> find(array("WHERE", $filtre['dupa'], "like", "'%". $filtre['txtSearch'] ."%'", "ORDER BY", "denumire", "ASC"));
    }
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Denumire", "REG COM");
        $gv -> tableOptions['ColWidth'] = array("50%", "50%");
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $cls -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> denumire, $obj -> reg_com);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm(". $obj -> client_id .")",
            );
        }
        $objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
    } else {
        $objResponse -> assign("listaObiecte", "innerHTML", "NU SUNT INREGISTRARI");
    }
    return $objResponse;
}

function loadForm($client_id)
{
    global $mysql;
    $objResponse = new xajaxResponse();
    $obj = new Clienti($mysql, $client_id);
    $objResponse -> assign("divForm", "innerHTML", $obj -> frmClient());
    return $objResponse;
}
function btnSave($frmValues)
{
    global $mysql;
    $obj = new Clienti($mysql);
    $obj -> salveazaClient($frmValues);
    $objResponse = lista(substr($frmValues['denumire'], 0, 1));
    $objResponse -> assign("divForm", "innerHTML", "");
    return $objResponse;
}
$xajax->processRequest();
