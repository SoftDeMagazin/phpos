<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("evidenta.niruri.common.php");

$mysql = new MySQL;
$html = new Html;

function genereazaLista($frmFiltre)
{
    $objResponse = new xajaxResponse();
    global $mysql;
    $filtre[] = "WHERE";
    $filtre[$frmFiltre['data']] = $mysql -> between($frmFiltre['dateStart'], $frmFiltre['dateStop']);
    if ($frmFiltre['user_id']) {
        $filtre[] = "AND";
        $filtre['user_id'] = $mysql -> equal($frmFiltre['user_id']);
    }
    if ($frmFiltre['furnizor_id']) {
        $filtre[] = "AND";
        $filtre['furnizor_id'] = $mysql -> equal($frmFiltre['furnizor_id']);
    }
    if (!empty($frmFiltre['numar_nir'])) {
        $filtre = array("WHERE", $frmFiltre['nr'] => $mysql -> equal($frmFiltre['numar_nir']));
    }
    
    $niruri = new Niruri($mysql);
    $nr_r = $niruri -> find($filtre);
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Numar Nir", $frmFiltre['data'], "Furnizor", "Adaugat de");
        $gv -> tableOptions['ColWidth'] = array("25%", "25%", "25%", "25%");
        $i=0;
        foreach ($niruri -> objects as $obj) {
            $furnizor = new Furnizori($mysql, $obj -> furnizor_id);
            $user = new Users($mysql, $obj -> user_id);
            $gv -> dataTable[$i]['data'] = array($obj -> numar_nir, $obj -> $frmFiltre['data'], $furnizor -> obj -> nume, $user -> obj -> nume);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            $gv -> dataTable[$i]['tag'] = array("class"=> $class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"xajax_selectNir(". $obj -> nir_id .")",
            );
            $i++;
        }
        $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
        $objResponse -> assign("actiuni", "innerHTML", '');
    } else {
        $objResponse -> assign("preview", "innerHTML", 'NU SUNT NIRURI CE CORESPUND CRITERIILOR CAUTATE');
        $objResponse -> assign("actiuni", "innerHTML", '');
    }
    return $objResponse;
}

function selectNir($nir_id)
{
    $objResponse = new xajaxResponse();
    $innerHTML = '
		  <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <th scope="col"><div align="center">
            <input name="Button" type="button" class="btnTouch" value="Tiparire" onClick="window.location.href = \'nir.php?nir_id='. $nir_id .'\'">
          </div></th>
          <th scope="col"><input name="Button" type="button" class="btnTouch" value="Editeaza" onClick="window.location.href = \'receptie.tastatura.php?nir_id='. $nir_id .'\'"></th>
    <th scope="col"><input name="Button" type="button" class="btnTouch" value="Etichete" onClick="window.location.href = \'nir.etichete.php?nir_id='. $nir_id .'\'"></th>
          <th scope="col"><input name="Button" type="button" class="btnTouch" value="EXPORT EXCEL" onClick="xajax_exportXls('. $nir_id .')"></th>
          </tr>
      </table>
	';
    $objResponse -> assign("actiuni", "innerHTML", $innerHTML);
    return $objResponse;
}

function exportXls($nir_id)
{
    global $mysql;
    set_include_path(get_include_path() . PATH_SEPARATOR . 'thirdparty/');
    require("PHPExcel.php");
    include 'PHPExcel/IOFactory.php';
    $Nir = new Niruri($mysql, $nir_id);
    $file = $Nir -> exportXls();
    $objResponse =  afisareDialog('Am salvat fisierul. <a href="temp/'. $file .'.xls" target="_blank">deschide fisier</a>', "400px", "200px", "300px", "250px", "RENUNTA");
    return $objResponse;
}

    
$xajax->processRequest();
