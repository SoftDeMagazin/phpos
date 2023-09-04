<?php
class RptUtilizatoriModuri extends Rapoarte
	{
			var $view = "rpt_utilizatori_moduri";
	function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	function genereazaRaport($dateStart, $dateStop, $filtre = array())
		{
		$this -> columns = array(
			"nume", "nume_mod", "SUM(suma) as suma"
			);
		$this -> getByInterval($dateStart, $dateStop, $filtre);
		}
	
	function printDoc($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("GROUP BY", $this -> mysql -> csv(array("nume", "nume_mod")), "ORDER BY nume ASC, nume_mod ASC");
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		global $cfgImprimante;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => 'RAPORT UTILIZATORI',
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
			if($cat != $row['nume'])
				{
				$cat = $row['nume'];
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $cat,
				'coordX' => 0,
				'coordY' => "add:16",
				'font' => 'bold'
				);$i++;
				$lines++;
				}
			$totalCategorie += $row['suma'];
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $row['nume_mod'],
				'coordX' => 10,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => number_format($row['suma'], 2, '.',''),
				'coordX' => "add:300",
				'coordY' => "equal",
				);$i++;
			
			if($cat != $this -> rows[$j+1]['nume'])
				{
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => "TOTAL UTIL:".number_format($totalCategorie, 2, '.', ''),
				'coordX' => 0,
				'coordY' => "add:16",
				'font' => 'bold'
				);$i++;
				$total += $totalCategorie;
				$totalCategorie = 0;
				$lines++;
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
		$filtre = array("GROUP BY", $this -> mysql -> csv(array("nume", "nume_mod")), "ORDER BY nume ASC, nume_mod ASC");
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Mod Plata", "Total vanzari");
		$gv -> tableOptions['ColWidth'] = array("250", "100");

		if(isset($this -> rows))
			{
			$cat="";
			$i=0;
			$total=0;
			$totalCategorie=0;
			for($j=0; $j<count($this -> rows); $j++)
				{
				$row = $this -> rows[$j];
				if($cat != $row['nume'])
					{
					$gv -> dataTable[$i]['data'] = array($row['nume']);$i++;
					$cat = $row['nume'];
					}
				$gv -> dataTable[$i]['data'] = array($row['nume_mod'], number_format($row['suma'], 2, '.', ''));
				$i++;
				$totalCategorie += $row['suma'];
				if($cat != $this -> rows[$j+1]['nume'])
					{
					$gv -> dataTable[$i]['data'] = array("<strong>Total Utilizator:</strong>", number_format($totalCategorie, 2, '.', ''));
					$i++;
					$total += $totalCategorie;
					$totalCategorie = 0;
					}
				}
			$gv -> dataTable[$i]['data'] = array("Total", number_format($total, 2, '.',''));
			}
		return $gv -> getTable();			
		}
		
	function pie($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("GROUP BY", "nume");
		global $cfgImprimante;
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		if(isset($this -> rows))
		{	
			$chart = new PieChart(500, 250);
			$dataSet = new XYDataSet();
		$total = 0;
		$totalCategorie = 0;
		for($j = 0; $j < count($this -> rows); $j++)
			{
			$row = $this -> rows[$j];
			if($cat != $row['nume'])
				{
				$cat = $row['nume'];
				}
			$totalCategorie += $row['suma'];
			if($cat != $this -> rows[$j+1]['nume'])
				{
				$total += $totalCategorie;
				$dataSet->addPoint(new Point("". $row['nume'] ." (". number_format($totalCategorie, 2, '.', '') .")", number_format($totalCategorie, 2, '.', '')));
				$totalCategorie = 0;
				$lines++;
				}
			}
		$chart->setDataSet($dataSet);	
		$chart->setTitle("Vanzari pe utilizatori");
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