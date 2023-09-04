<?php
class RptVanzariTigari extends Rapoarte
{
	var $view = "rpt_vanzari";
	function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	function genereazaRaport($dateStart, $dateStop, $filtre = array())
		{
		/*$this -> columns = array(
			"denumire as denumire", "denumire_categorie", "SUM(valoare_vanduta) as valoare_vanduta", "SUM(cantitate_vanduta) as cantitate_vanduta"
			);*/
		/*$this -> getByInterval($dateStart, $dateStop, $filtre);*/
$this -> rows = $this -> mysql -> getRows("
select 
`produse`.`denumire` AS `denumire`,
`categorii`.`denumire_categorie` AS `denumire_categorie`,
sum(`bonuri_continut`.`cantitate`) AS `cantitate_vanduta`,
sum((`bonuri_continut`.`cantitate` * `bonuri_continut`.`valoare`)) AS `valoare_vanduta`
from ((((`bonuri_continut` 
join `bonuri` on((`bonuri_continut`.`bon_id` = `bonuri`.`bon_id`))) 
join `produse` on((`bonuri_continut`.`produs_id` = `produse`.`produs_id`))) 
join `categorii` on((`produse`.`categorie_id` = `categorii`.`categorie_id`))) 
join `zile_economice` `ze` on((`ze`.`zi_economica_id` = `bonuri`.`zi_economica_id`))) 
where ze.data between '$dateStart' and '$dateStop' and produse.categorie_id = 13
group by `produse`.`denumire`, `categorii`.`denumire_categorie`

");
		}
	
	function printDoc($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("GROUP BY", $this -> mysql -> csv(array("denumire", "denumire_categorie")), "ORDER BY denumire_categorie ASC, denumire ASC");
		global $cfgImprimante;
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		$toPrint[$i] = array(
				'type' => 'text',
				'value' => 'RAPORT VANZARI',
				'coordX' => 130,
				'coordY' => 0,
				);$i++;
		$toPrint[$i] = array(
				'type' => 'text',
				'value' => "".date("d/m/Y", strtotime($dateStart))."--". date("d/m/Y", strtotime($dateStop)) ."",
				'coordX' => 130,
				'coordY' => "add:16",
				);$i++;

	if(isset($this -> rows))
		{
		$cat = "";
		$lines = 2;
		$page=1;
		$total = 0;
		$totalCategorie = 0;
		for($j = 0; $j < count($this -> rows); $j++)
			{
			$row = $this -> rows[$j];
			if($cat != $row['denumire_categorie'])
				{
				$cat = $row['denumire_categorie'];
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $cat,
				'coordX' => 0,
				'coordY' => "add:16",
				'font' => 'bold'
				);$i++;
				$lines++;
				}
				
				$totalCategorie += $row['valoare_vanduta'];
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $row['denumire'],
				'coordX' => 10,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $row['cantitate_vanduta'],
				'coordX' => "add:300",
				'coordY' => "equal",
				);$i++;
				$lines++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => number_format($row['valoare_vanduta']/$row['cantitate_vanduta'], 2,'.',''),
				'coordX' => 25,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' =>  number_format($row['valoare_vanduta'], 2,'.',''),
				'coordX' => "add:250",
				'coordY' => "equal",
				);$i++;
				$lines++;
			
			if($cat != $this -> rows[$j+1]['denumire_categorie'])
				{
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => "TOTAL CATEG:".number_format($totalCategorie, 2, '.', ''),
				'coordX' => 0,
				'coordY' => "add:16",
				'font' => 'bold'
				);$i++;
				$total += $totalCategorie;
				$totalCategorie = 0;
				$lines++;
				}

						
			$lines++;
			if($lines%$cfgImprimante['lungime_pagina'] == 0)
				{
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $page,
				'coordX' => 150,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'cut',
				'coordX' => 1,
				'coordY' => 1,
				);$i++;

				$page++;
				}	
			}
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => "TOTAL VANZARI:".number_format($total, 2, '.', ''),
				'coordX' => 0,
				'coordY' => "add:16",
				'font' => 'bold'
				);$i++;

		}
		$printJob = new Printer($cfgImprimante['imprimanta_rapoarte']);
		$printJob -> printDoc($toPrint, "rpt moduri");
		}	
	
	function preview($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("and categorie_id = '13' ","GROUP BY", $this -> mysql -> csv(array("denumire", "denumire_categorie")), "ORDER BY denumire_categorie ASC, denumire ASC");
		global $cfgImprimante;
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Produs", "Cantitate");
		$gv -> tableOptions['ColWidth'] = array("250", "100");

		if(isset($this -> rows))
			{
			$cat = "";
			$i=0; 
			$totalCategorie = 0;
			$total = 0;
			for($j=0;$j<count($this -> rows);$j++)
				{
				$row = $this -> rows[$j];
				if($cat != $row['denumire_categorie'])
					{
					$cat = $row['denumire_categorie'];
					$gv -> dataTable[$i]['data'] = array('<strong>'.$row['denumire_categorie'].'</strong>', "&nbsp;");
					$i++;
					}
				$gv -> dataTable[$i]['data'] = array($row['denumire'], number_format($row['cantitate_vanduta'], 2, '.', ''));$i++;
				$gv -> dataTable[$i]['data'] = array("Pret: ".number_format($row['valoare_vanduta']/$row['cantitate_vanduta'], 2, '.', ''), number_format($row['valoare_vanduta'], 2, '.', ''));
				$i++;
				$totalCategorie += $row['valoare_vanduta'];
				if($cat != $this -> rows[$j+1]['denumire_categorie'])
					{
					$gv -> dataTable[$i]['data'] = array("<strong>Total Cat:</strong>", number_format($totalCategorie, 2, '.', ''));
					$i++;
					$total += $totalCategorie;
					$totalCategorie = 0;
					}
				}
			$gv -> dataTable[$i]['data'] = array("<strong>Total</strong>", number_format($total, 2, '.', ''));
	
			}
		return $gv -> getTable();	
		}
	
	function pie($dateStart, $dateStop, $filtre = array())
		{
		$rows = $this -> mysql -> getRows("SELECT denumire_categorie, SUM(valoare_vanduta) as valoare_vanduta FROM rpt_vanzari WHERE
		data BETWEEN '$dateStart' AND '$dateStop' GROUP BY denumire_categorie
		");
		if(isset($rows))
			{
			$chart = new VerticalBarChart(500, 250);
			$dataSet = new XYDataSet();
			foreach($rows as $row)
				{
				$dataSet->addPoint(new Point($row['denumire_categorie'], number_format($row['valoare_vanduta'], 2, '.', '')));
				}
			$chart->setDataSet($dataSet);	
			$chart->setTitle("Vanzari pe categorii: ". date("d/m/Y", strtotime($dateStart)) ." ". date("d/m/Y", strtotime($dateStop)) ."");
			$name = time();
			$chart->render("temp/$name.png");
			return $name;	
		}
		else
		{
		return false;
		}	
		}
}
?>