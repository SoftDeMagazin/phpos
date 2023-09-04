<?php
class Rapoarte
{	
	var $mysql;
	var $rows;
	var $columns;
	function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	function getByDate($date, $options = array())
		{
		$sql = "SELECT * FROM ". $this -> view ." WHERE data = '$date' ". $this -> mysql -> arrayToSql($options) ."";
		$this -> rows = $this -> mysql -> getRows($sql);
		}
	
	function  getByInterval($dateStart, $dateStop, $options = array())
		{
		if(count($this -> columns))
			{
			$columns = $this -> mysql -> csv($this -> columns);
			}
		else
			{
			$columns = "*";
			}	
		$sql = "SELECT ". $columns ." FROM ". $this -> view ." WHERE data BETWEEN '$dateStart' AND '$dateStop' ". $this -> mysql -> arrayToSql($options) ."";
		$this -> rows = $this -> mysql -> getRows($sql);
		}	
}
?>