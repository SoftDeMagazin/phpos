<?php
class Furnizori extends AbstractDB
{
	/*  */
	var $useTable = "furnizori";
	var $primaryKey = "furnizor_id";
	var $form = array(
	"furnizor_id" => array(
		"input" => array("type" => "hidden"),
		"label" => false
		),
	"nume" => array(
		"input" => array("type" => "text", "size" => 35),
		"label" => "Furnizor"
		),
	"adresa" => array(
		"input" => array("type" => "textarea", "rows" => 5, "cols" => "35"),
		"label" => "Adresa"
		),
	"telefon" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Telefon"
		),
	"cod_fiscal" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Cod fiscal"
		),
	"cont" => array(
		"input" => array("type" => "text", "size" => 35),
		"label" => "Cont"
		),
	"banca" => array(
		"input" => array("type" => "text", "size" => 35),
		"label" => "Banca"
		),
	"cod" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Cod furnizor"
		),
	"observatii" => array(
		"input" => array("type" => "textarea", "rows" => 5, "cols" => "35"),
		"label" => "Observatii"
		),
	
	);
	/* form processing */	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function totalFacturiEmise($options = array())
		{
		$sql = "SELECT sum(total_factura) as total from niruri where furnizor_id = '". $this -> obj -> furnizor_id ."'";
		$row = $this -> mysql -> getRow($sql);
		return number_format($row['total'],2,'.','');
		}	
	
	function totalPlatiAsociate($options = array())
		{
		$sql = "
		SELECT sum(suma) as total_plati from registru_casa 
		inner join niruri on niruri.nir_id = registru_casa.document_id
		where tip = 'plata' and tip_inregistrare = 'factura_furnizor' and niruri.furnizor_id = '". $this -> obj -> furnizor_id ."';
		";
		$row = $this -> mysql -> getRow($sql);
		return number_format($row['total_plati'],2,'.','');
		}
	
	function soldFurnizor()
		{
		return number_format($this -> totalFacturiEmise() - $this -> totalPlatiAsociate(),2,'.','');
		}	
}
?>