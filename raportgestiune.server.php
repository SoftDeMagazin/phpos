<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("raportgestiune.common.php");

$mysql = new MySQL;
$html = new Html;

function genereazaRaport($frmFiltre)
{
    global $mysql;
    if ($frmFiltre['detaliat'] == 'DA') {
        $intrari_iesiri = $mysql -> getRows("select sum(cantitate) as cantitate, pret_intrare, pret_vanzare, produs_id, denumire, tip from intrari_iesiri where cantitate <> 0 and  
		data between'". $frmFiltre['dateStart'] ."' and '". $frmFiltre['dateStop'] ."'
		group by pret_intrare, pret_vanzare, produs_id, denumire, tip
		order by denumire asc, data, tip asc
		");
            $out .= '<table width="100%" border=0 cellpadding="0" cellspacing="0">				
				';
            $produs = "";
            $tip = "";
                    $intrari = '';
        $totalValAchIntr = 0;
        $totalAdaosIntr = 0;
        $totalCantitateIntr = 0;
                    $iesiri = '';
        $totalValIes = 0;
        $totalAdaosIes = 0;
        $totalCantitateIes =0;
        $totalPretAch =0;
        for ($i=0; $i<=count($intrari_iesiri); $i++) {
            $intr = $intrari_iesiri[$i];
            if ($intr['tip'] == 'a') {
                $pret_f_tva = $intr['pret_vanzare'] - $intr['pret_vanzare']*24/124;
                $adaos = $pret_f_tva - $intr['pret_intrare'];
                if ($adaos < 0) {
                    $adaos = 'NA';
                }
                $intrari .= '
					<tr>	
<td>&nbsp;<td>
					<td>'. $intr['cantitate'] .'<td>
					<td>'. number_format($intr['pret_intrare'], 2) .'<td>
					<td>'. number_format($intr['cantitate']*$intr['pret_intrare'], 2) .'<td>
					<td>'. number_format($intr['pret_vanzare'], 2, '.', '') .'<td>
					<td>'. number_format($adaos, 2, '.', '') .'<td>
					<td>'. number_format($adaos*$intr['cantitate'], 2, '.', '') .'<td>
					</tr>';
                $totalValAchIntr += $intr['cantitate']*$intr['pret_intrare'];
                $totalAdaosIntr += $adaos*$intr['cantitate'];
                $totalCantitateIntr += $intr['cantitate'];
            } else {
                $pret_f_tva = $intr['pret_vanzare'] - $intr['pret_vanzare']*24/124;
                $adaos = $pret_f_tva - $intr['pret_intrare'];
                if ($intr['pret_vanzare'] == $intr['pret_intrare']) {
                    $adaos = 0;
                }
                $iesiri .= '
					<tr>
<td>&nbsp;<td>	
					<td>'. $intr['cantitate'] .'<td>
					<td>'. number_format($intr['pret_intrare']*$intr['cantitate'], 2) .'<td>
					<td>'. number_format($intr['pret_vanzare'], 2, '.', '') .'<td>
					<td>'. number_format($intr['cantitate']*$intr['pret_vanzare'], 2) .'<td>
					<td>'. number_format($adaos, 2, '.', '') .'<td>
					<td>'. number_format($adaos*$intr['cantitate'], 2, '.', '') .'<td>
					</tr>';
                $totalValIes += $intr['cantitate']*$intr['pret_vanzare'];
                $totalPretAch += $intr['cantitate']*$intr['pret_intrare'];
                $totalAdaosIes += $adaos*$intr['cantitate'];
                $totalCantitateIes += $intr['cantitate'];
            }
                    
            if ($intr['denumire'] != $intrari_iesiri[$i+1]['denumire']) {
                $out .= '
					<tr>
					<td colspan=6>'. $intr['denumire'] .'<td>
					</tr>
					<tr>
					<td colspan=6><strong>Intrari</strong><td>
					</tr>
					<tr>
<td>&nbsp;<td>
					<td>Cantitate<td>
					<td>Pret achizitie<td>
					<td>Valoare ach.<td>
					<td>Pret vanzare<td>
					<td>Adaos Unit<td>
					<td>Adaos Total<td>
					</tr>
					'. $intrari .'
<tr>
<td>Total<td>
					<td>'.$totalCantitateIntr.'<td>
					<td>&nbsp;<td>
					<td>'. number_format($totalValAchIntr, 2) .'<td>
					<td>&nbsp;<td>
<td>&nbsp;<td>
					<td>'. number_format($totalAdaosIntr, 2, '.', '') .'<td>
					</tr>
					<tr>
					<td colspan=6><strong>Iesiri</strong><td>
					</tr>
					<tr>
<td>&nbsp;<td>
					<td>Cantitate<td>
					<td>Val. pret ach<td>
					<td>Pret vanzare<td>
					<td>Valoare vanzare<td>
					<td>Adaos Unit<td>
					<td>Adaos Total<td>
					</tr>
					'. $iesiri .'
<tr>
<td>Total<td>
					<td>'.$totalCantitateIes.'<td>
					<td>'. number_format($totalPretAch, 2) .'<td>
					<td>&nbsp;<td>
					<td>'. number_format($totalValIes, 2) .'<td>
<td>&nbsp;<td>
					<td>'. number_format($totalAdaosIes, 2, '.', '') .'<td>
					</tr>
					<tr>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					<td style="border-bottom:1px solid #000">&nbsp;<td>
					</tr>';
                $intrari = '';
                $totalValAchIntr = 0;
                $totalAdaosIntr = 0;
                $totalCantitateIntr = 0;
                $iesiri = '';
                $totalValIes = 0;
                $totalAdaosIes = 0;
                $totalCantitateIes =0;
                $totalPretAch =0;
            }
        }
                $out .='</table>';
    } else {
        $intrari_iesiri = $mysql -> getRows("
	select produs_id, denumire, cantitate, pret_intrare, 
pret_vanzare, tip, produse.tip_produs
from intrari_iesiri 
inner join produse using(produs_id)
where data between'". $frmFiltre['dateStart'] ."' and '". $frmFiltre['dateStop'] ."' and (produse.tip_produs = 'marfa' OR produse.tip_produs = 'mp' or produse.tip_produs = 'reteta' or produse.tip_produs = 'serviciu')
order by produse.tip_produs asc, denumire asc 
	");
        $out .= '<h3 align="center">Raport intrari iesiri</h3>
	<div align="center">De la:'. date("d/m/Y", strtotime($frmFiltre['dateStart'])) .' -- Pana la:'. date("d/m/Y", strtotime($frmFiltre['dateStop'])) .'</div>
	';
        $totalValIntrare = 0;
        $totalCantitateIntrare = 0;
        $totalAdaosIntrare = 0;
    
        $totalValIntrareTip = 0;
        $totalCantitateIntrareTip = 0;
        $totalAdaosIntrareTip = 0;
    
        $totalValIesire = 0;
        $totalValAchIesire = 0;
        $totalCantitateIesire = 0;
        $totalAdaosIesire = 0;
    
        $totalValIesireTip = 0;
        $totalValAchIesireTip = 0;
        $totalCantitateIesireTip = 0;
        $totalAdaosIesireTip = 0;
    
        $totalIntrari = 0;
        $totalAdaosIntrari = 0;
        $totalIesiriAch = 0;
        $totalIesiriVal = 0;
        $totalAdaosIesiri = 0;
        $tip_produs = "";
    
    
        for ($i=0; $i<=count($intrari_iesiri); $i++) {
            $intr = $intrari_iesiri[$i];
        
            if ($tip_produs != $intr['tip_produs']) {
                if ($i > 0) {
                    $out .= '</table>';
                }
                $out .= '<h3>'. $intr['tip_produs'] .'</h3>
			<table width="100%" border=1 cellpadding="0" cellspacing="0">
	<tr>
		<td>Denumire</td>
		<td colspan=3>Intrari</td>
		<td colspan=4>Iesiri</td>
	</tr>
	<tr>
		<td>Denumire</td>
		<td>Cantitate</td>
		<td>Valoare ach</td>
		<td>Adaos</td>
		<td>Cantitate</td>
		<td>Valoare ach</td>
		<td>Valoare vanzare</td>
		<td>Adaos</td>
	</tr>';
                $tip_produs = $intr['tip_produs'];
            }
        
            if ($intr['tip'] == 'a') {
                $totalCantitateIntrare += $intr['cantitate'];
                $totalValIntrare += $intr['cantitate']*$intr['pret_intrare'];
                $adaos = ($intr['pret_vanzare'] - $intr['pret_vanzare']*24/124) - $intr['pret_intrare'];
                if ($adaos < 0) {
                    $adaos = 0;
                }
                $totalAdaosIntrare +=  $adaos*$intr['cantitate'];
                $intr_cant = $intr['cantitate'];
                $intrari = '<td>'. number_format($totalCantitateIntrare, 2) .'</td>
		<td>'. number_format($totalValIntrare, 2) .'</td>
		<td>'. number_format($totalAdaosIntrare, 2, '.', '') .'</td>';
            } else {
                $totalCantitateIesire += $intr['cantitate'];
                $totalValAchIesire += $intr['cantitate']*$intr['pret_intrare'];
                $totalValIesire += $intr['cantitate']*$intr['pret_vanzare'];
                $adaos = ($intr['pret_vanzare'] - $intr['pret_vanzare']*24/124) - $intr['pret_intrare'];
                if ($intr['pret_vanzare'] == $intr['pret_intrare']) {
                    $adaos = 0;
                }
                $totalAdaosIesire += $adaos*$intr['cantitate'];
                $iesiri = '		<td>'. number_format($totalCantitateIesire, 2) .'</td>
		<td>'. number_format($totalValAchIesire, 2) .'</td>
		<td>'. number_format($totalValIesire, 2) .'</td>
		<td>'. number_format($totalAdaosIesire, 2, '.', '') .'</td>';
            }
            if ($intr['denumire'] != $intrari_iesiri[$i+1]['denumire']) {
                if (empty($intrari)) {
                    $intrari = '<td>0.00</td>
		<td>0.00</td>
		<td>0.00</td>';
                    ;
                }
                if (empty($iesiri)) {
                    $iesiri = '<td>0.00</td>
		<td>0.00</td>
		<td>0.00</td>
		<td>0.00</td>';
                }
                $out .= '
			<tr>
				<td>'. $intr['denumire'] .'</td>
				'. $intrari .'
				'. $iesiri .'
			</tr>
			';
            
                $totalIntrari += $totalValIntrare;
                $totalAdaosIntrari += $totalAdaosIntrare;
            
                $totalValIntrareTip += $totalValIntrare;
                $totalAdaosIntrareTip += $totalAdaosIntrare;
            
                $totalIesiriAch += $totalValAchIesire;
                $totalIesiriVal += $totalValIesire;
                $totalAdaosIesiri += $totalAdaosIesire;

                $totalValIesireTip += $totalValIesire;
                $totalValAchIesireTip += $totalValAchIesire;
                $totalAdaosIesireTip += $totalAdaosIesire;
            
                $intrari = '';
                $iesiri = '';
                $totalValIntrare = 0;
                $totalCantitateIntrare = 0;
                $totalAdaosIntrare = 0;
    
                $totalValIesire = 0;
                $totalValAchIesire = 0;
                $totalCantitateIesire = 0;
                $totalAdaosIesire = 0;
            }
            if ($tip_produs != $intrari_iesiri[$i+1]['tip_produs']) {
                $out .= '
	<tr>
		<td>Total</td>
		<td>&nbsp;</td>
		<td>'. number_format($totalValIntrareTip, 2, '.', '') .'</td>
		<td>'. number_format($totalAdaosIntrareTip, 2, '.', '') .'</td>
		<td>&nbsp;</td>
		<td>'. number_format($totalValAchIesireTip, 2, '.', '') .'</td>
		<td>'. number_format($totalValIesireTip, 2, '.', '') .'</td>
		<td>'. number_format($totalAdaosIesireTip, 2, '.', '') .'</td>
	</tr>';
                $totalValIntrareTip = 0;
                $totalAdaosIntrareTip = 0;
            
                $totalValIesireTip = 0;
                $totalValAchIesireTip = 0;
                $totalAdaosIesireTip = 0;
            }
        }
        

        
        $out .= '	
	<tr>
		<td>Total</td>
		<td>&nbsp;</td>
		<td>'. number_format($totalIntrari, 2, '.', '') .'</td>
		<td>'. number_format($totalAdaosIntrari, 2, '.', '') .'</td>
		<td>&nbsp;</td>
		<td>'. number_format($totalIesiriAch, 2, '.', '') .'</td>
		<td>'. number_format($totalIesiriVal, 2, '.', '') .'</td>
		<td>'. number_format($totalAdaosIesiri, 2, '.', '') .'</td>
	</tr>
		</table>';
    }
    $objResponse = new xajaxResponse();
    $objResponse -> assign("preview", "innerHTML", $out);
    return $objResponse;
}

$xajax->processRequest();
