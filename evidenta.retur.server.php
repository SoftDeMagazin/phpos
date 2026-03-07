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
    
    $rows = $mysql -> getRows("
select produse.denumire, users.nume, cantitate, valoare, data, ora from retururi
inner join produse using(produs_id)
inner join users on users.user_id = retururi.utilizator_id
where data between '". $frmFiltre['dataStart'] ."' and '".$frmFiltre['dataStop']."'
	");
    $nr_r = count($rows);
    if ($nr_r) {
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Articol", "Cantitate", "Pret", "Data", "Ora", "User");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $rows[$i];
            $gv -> dataTable[$i]['data'] = array($obj['denumire'], $obj['cantitate'], $obj['valoare'], $obj['data'], $obj['ora'], $obj['nume']);
                        
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
