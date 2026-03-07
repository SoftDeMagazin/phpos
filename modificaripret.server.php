<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("modificaripret.common.php");

$mysql = new MySQL();
$html = new Html;
    
function activeaza()
{
    global $mysql;
    global $html;
      $mp = new ModificariPret($mysql);
      $doc = new DocModificariPret($mysql);
      $doc -> nou();
      $nr_r =  $mp -> find(array("where activat = 'NU'"));
    if ($nr_r) {
        $sql = "
					insert into intrari_continut
					(nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, data, pret_vanzare, adaos_unit, activ, tip) values
					";
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $mp -> objects[$i];
            $prod = new Produse($mysql, $obj -> produs_id);
            $prod -> obj -> pret = $obj -> pret_nou;
            $prod -> save();
            $mp -> obj = $obj;
            $mp -> obj -> stoc = $prod -> getStoc();
            $mp -> obj -> activat = 'DA';
            $mp -> obj -> doc_modificare_pret_id = $doc -> obj -> doc_modificare_pret_id;
            $mp -> save();

            $loturi = $mysql -> getRows("SELECT * FROM intrari_continut where cantitate_ramasa > 0 and produs_id = '". $obj -> produs_id ."' and pret_vanzare <> '". $obj -> pret_nou ."'");
            $mysql -> query("UPDATE intrari_continut set cantitate = cantitate - cantitate_ramasa, cantitate_ramasa = 0 where cantitate_ramasa > 0 and produs_id = '". $obj -> produs_id ."' and pret_vanzare <> '". $obj -> pret_nou ."'");
            if ($loturi) {
                $j = 0;
                foreach ($loturi as $lot) {
                    $adaos = number_format($obj -> pret_nou - $obj -> pret_nou*24/124 - $lot['pret_intrare'], 2, '.', '');
                    if ($j) {
                        $sql .= ",";
                    }
                    $sql .= "('". $lot['nir_componenta_id'] ."', '". $obj -> modificare_pret_id ."', '". $obj -> produs_id ."', '". $lot['cantitate_ramasa'] ."', 
						'". $lot['cantitate_ramasa'] ."', '". $lot['pret_intrare'] ."', '". $lot['data'] ."', '". $obj -> pret_nou ."', 
						'". $adaos ."', '1', 'modificare_pret')";
                    $j++;
                }
            }
        }
        if ($j) {
            $mysql -> query($sql);
        }
    }
    $objResponse = new xajaxResponse();
    $objResponse -> alert('Am efectuat modificarile de pret. Am salvat document nr. '. $doc -> obj -> numar_document .' ');
    $objResponse -> script("window.location.href = 'login.php'");
    return $objResponse;
}
$xajax->processRequest();
