<?php
class RptModuriPlata extends Rapoarte
{
	var $view = "rpt_moduri_plata";
	function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	function genereazaRaport($dateStart, $dateStop, $filtre = array())
		{
		$this -> columns = array(
			"nume_mod as nume_mod", "SUM(total_suma) as total_suma"
			);
		$this -> getByInterval($dateStart, $dateStop, $filtre);
		}
	
	function printDoc($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("GROUP BY", "nume_mod");
		global $cfgImprimante;
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		$toPrint[$i] = array(
				'type' => 'text',
				'value' => 'Raport moduri',
				'coordX' => 130,
				'coordY' => 0,
				);$i++;
		$toPrint[$i] = array(
				'type' => 'text',
				'value' => "".date("d/m/Y", strtotime($dateStart))."--". date("d/m/Y", strtotime($dateStop)) ."",
				'coordX' => 100,
				'coordY' => "add:16",
				);$i++;

		if(isset($this -> rows))
		{
		$cat = "";
		$lines = 2;
		$page=1;
		$total = 0;
		foreach($this -> rows as $row)
			{
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => $row['nume_mod'],
				'coordX' => 0,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => number_format($row['total_suma'], 2, '.', ''),
				'coordX' => "add:300",
				'coordY' => "equal",
				);$i++;
			$lines++;
			$total += $row['total_suma'];

			}
			$toPrint[$i] = array(
				'type' => 'text',
				'value' => "Total",
				'coordX' => 0,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => number_format($total, 2, '.', ''),
				'coordX' => "add:300",
				'coordY' => "equal",
				);$i++;
	
		}
		$printJob = new Printer($cfgImprimante['imprimanta_rapoarte']);
		$printJob -> printDoc($toPrint, "Bon Comanda");
		return $toPrint;
		}
	
	function preview($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("GROUP BY", "nume_mod");
		global $cfgImprimante;
		global $mysql;
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Mod Plata", "Total");
		$gv -> tableOptions['ColWidth'] = array("250", "100");
		if(isset($this -> rows))
			{
			$i = 0;
			$total = 0;
			for($j=0;$j<count($this -> rows);$j++)
				{
				$row = $this -> rows[$j];
				$gv -> dataTable[$i]['data'] = array($row['nume_mod'], number_format($row['total_suma'], 2, '.', ''));
				if($row['nume_mod'] == 'PROTOCOL' || $row['nume_mod'] == 'EXPIRATE' || $row['nume_mod'] == 'TRANSFER')  {
					$total+= $row['total_suma'];
					//$total_fara_protocol +=  $row['total_suma'];
				}
				else {
					$total+= $row['total_suma'];
					$total_fara_protocol +=  $row['total_suma'];
				}
				$i++;
				}
			$gv -> dataTable[$i]['data'] = array("Total", number_format($total, 2, '.',''));
			$i++;
			$gv -> dataTable[$i]['data'] = array("Total fara protocol", number_format($total_fara_protocol, 2, '.',''));
			$i++;
			$row = $mysql -> getRow("
			select sum(cantitate*valoare*bonuri_continut.discount/100) as discount from bonuri_continut 
			inner join bonuri using(bon_id)
			inner join zile_economice using(zi_economica_id)
			where zile_economice.data between '$dateStart' and '$dateStop'
			");
			$gv -> dataTable[$i]['data'] = array("Discount", number_format($row['discount'], 2, '.',''));
			}
		return $gv -> getTable();
		}
	
	function pie($dateStart, $dateStop, $filtre = array())
		{
		$filtre = array("GROUP BY", "nume_mod");
		global $cfgImprimante;
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		if(isset($this -> rows))
		{	
			$chart = new PieChart(500, 250);
			$dataSet = new XYDataSet();
			for($j=0;$j<count($this -> rows);$j++)
				{
				$row = $this -> rows[$j];
				$dataSet->addPoint(new Point("". $row['nume_mod'] ." (". number_format($row['total_suma'], 2, '.', '') .")", number_format($row['total_suma'], 2, '.', '')));
				}
			$chart->setDataSet($dataSet);	
			$chart->setTitle("Vanzari pe moduri de plata");
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