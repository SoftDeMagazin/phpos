<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("furnizori.produse.common.php");

$mysql = new MySQL();
$html = new Html;

function raport($frmFiltre = array())
{
    global $mysql;
    $sql = "
select vs.produs_id, vs.denumire, sum(nc.cant) as cantitate_intrare, vs.stoc from niruri_componente as nc
inner join niruri as n on n.nir_id = nc.nir_id
inner join view_stocuri_produse as vs on nc.produs_id = vs.produs_id
where n.furnizor_id = '". $frmFiltre['furnizor_id'] ."' and vs.denumire <> 'DISCOUNT' 
and n.data_factura between '". $frmFiltre['dataStart'] ."' and '". $frmFiltre['dataStop'] ."' 
and vs.la_vanzare = 'DA'
group by vs.produs_id, vs.denumire, vs.stoc
;	";
    $rows = $mysql -> getRows($sql);
    $nr_r = count($rows);
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Denumire articol", "Cantitate adusa", "Cantitate vanduta", "Stoc actual");
                    $gv -> tableOptions['ColWidth'] = array();
    for ($i=0; $i<$nr_r; $i++) {
        $row = $rows[$i];
        $vanzare = $mysql -> getRow("
						SELECT sum(cantitate) as cantitate FROM bonuri_continut
						INNER JOIN bonuri using(bon_id)
						WHERE produs_id = '". $row['produs_id'] ."' AND 
						data between '". $frmFiltre['dataStart'] ."' and '". $frmFiltre['dataStop'] ."'
						");
        $gv -> dataTable[$i]['data'] = array($row['denumire'], number_format($row['cantitate_intrare'], 2), number_format($vanzare['cantitate'], 2), number_format($row['stoc'], 2));
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
        $objResponse = new xajaxResponse();
        $objResponse -> assign("lista", "innerHTML", $d);
        return $objResponse;
}
    
$xajax->processRequest();
