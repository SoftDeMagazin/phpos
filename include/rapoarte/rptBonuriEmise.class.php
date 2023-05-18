<?php
class rptBonuriEmise extends Rapoarte
	{
			var $view = "rpt_bonuri_emise";
	function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	function genereazaRaport($dateStart, $dateStop, $filtre = array())
		{
		$this -> columns = array(
			"bonuri_emise", "data", "nume_casa"
			);
		$this -> getByInterval($dateStart, $dateStop, $filtre);
		}
	
	function printDoc($dateStart, $dateStop, $filtre = array())
		{
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		global $cfgImprimante;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => 'RAPORT BONURI EMISE',
				'coordX' => 80,
				'coordY' => 0,
				);$i++;
		$toPrint[$i] = array(
				'type' => 'text',
				'value' => "".date("d/m/Y", strtotime($dateStart))."--". date("d/m/Y", strtotime($dateStop)) ."",
				'coordX' => 80,
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
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => date("d/m/Y", strtotime($row['data'])),
				'coordX' => 0,
				'coordY' => "add:14",
				);$i++;
				$toPrint[$i] = array(
				'type' => 'text',
				'value' => number_format($row['bonuri_emise'], 0, '.',''),
				'coordX' => "add:300",
				'coordY' => "equal",
				);$i++;
			}
		}	
		$printJob = new Printer($cfgImprimante['imprimanta_rapoarte']);
		$printJob -> printDoc($toPrint, "rpt moduri");
		}	
	function preview($dateStart, $dateStop, $filtre = array())
		{
		$this -> genereazaRaport($dateStart, $dateStop, $filtre);
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Data", "Nr. Bonuri Emise", "Casa");
		$gv -> tableOptions['ColWidth'] = array("250", "50","50");

		if(isset($this -> rows))
			{
			$cat="";
			$i=0;
			$total=0;
			$totalCategorie=0;
			for($j=0; $j<count($this -> rows); $j++)
				{
				$row = $this -> rows[$j];
				$gv -> dataTable[$i]['data'] = array(date("d/m/Y", strtotime($row['data'])), number_format($row['bonuri_emise'], 0, '.', ''), $row['nume_casa']);
				$i++;
				}
			}
		return $gv -> getTable();			
		}
		
}
?>