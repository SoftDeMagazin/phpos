<?php
class ModuriPlata extends AbstractDB
{
	var $useTable="moduri_plata";
	var $primaryKey="mod_plata_id";
	var $form = array(
	"mod_plata_id" => array(
		"input" => array("type" => "hidden"),
		"label" => false
		),
	"nume_mod" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Nume Mod Plata"
		),
	"fiscal" => array(
		"input" => array("type" => "select"),
		"data_source" => array("DA" => "DA", "NU" => "NU"),
		"label" => "Este mod fiscal"
		),
	"cash" => array(
		"input" => array("type" => "select"),
		"data_source" => array("DA" => "DA", "NU" => "NU"),
		"label" => "Rest"
		),
	"final_fiscal" => array(
		"input" => array("type" => "text", "size" => 10),
		"label" => "Cod mod plata"
		)
	);
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>