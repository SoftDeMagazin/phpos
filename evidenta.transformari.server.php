<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("evidenta.transformari.common.php");

$mysql = new MySQL();
$html = new Html;

function incarcaTransformari($frmFiltre)
{
    global $mysql;
    global $html;
    $plati = '';
    
    $trans = new Transformari($mysql);
    $nr_r = $trans -> find(array(
    "where",
    "data_transformare" => $mysql -> between($frmFiltre['dataStart'], $frmFiltre['dataStop']),
    "order by data_transformare asc"
    ));
    if ($nr_r) {
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Data", "Articol sursa", "Cantitate", "Articol Destinatie", "Cantitate");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $trans -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> data_transformare, $obj -> sursa_denumire, $obj -> sursa_cantitate, $obj -> destinatie_denumire, $obj -> destinatie_cantitate);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            );
        }
        $d = $gv -> getTable();
    } else {
        $plati = "NU SUNT INREGISTRARI";
    }
        

    
    $objResponse = new xajaxResponse();
    $objResponse -> assign("div_transformari", "innerHTML", $d);
    return $objResponse;
}
    
$xajax->processRequest();
