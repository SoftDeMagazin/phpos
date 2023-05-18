<?php
class Intrari extends AbstractDB
{
	var $useTable="intrari";
	var $primaryKey="intrare_id";
	var $form = array(
		"intrare_id" => array(
			"input" => array("type" => "hidden"),
			"label" => false
		),
		"furnizor_id" => array(
			"input" => array("type" => "select"),
			"data_source" => "SELECT furnizor_id, nume FROM furnizori order by nume asc",
			"label" => "Furnizor"
		),
		"nr_factura" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"data_factura" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"data_scadenta" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"total_fara_tva" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
		"total_tva" => array(
			"input" => array("type" => "text", "size" => "20"),
			"label" => "Factura/Aviz"
		),
	);
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function stergeContinut()
		{
		$this -> mysql -> query("DELETE FROM intrari_continut WHERE intrare_id = '". $this -> obj -> intrare_id ."'");
		}
	
	function valideaza()
		{
		$this -> mysql -> query("UPDATE intrari SET activ = 1 WHERE intrare_id = '". $this -> obj -> intrare_id ."'");
		$this -> mysql -> query("UPDATE intrari_continut SET activ = 1 WHERE intrare_id = '". $this -> obj -> intrare_id ."'");
		}
	
	function invalideaza()
		{
		$this -> mysql -> query("UPDATE intrari SET activ = 0 WHERE intrare_id = '". $this -> obj -> intrare_id ."'");
		$this -> mysql -> query("UPDATE intrari_continut SET activ = 0 WHERE intrare_id = '". $this -> obj -> intrare_id ."'");
		}
	
	function verifica()
		{
		$row = $this -> mysql -> getRow("
		SELECT SUM(cantitate) as cantitate, SUM(cantitate_ramasa) as cantitate_ramasa 
		FROM intrari_continut
		WHERE intrare_id = '". $this -> obj -> intrare_id ."';
		");
		if($row['cantitate'] != $row['cantitate_ramasa']) return false;
		return true;
		}	
}
?>