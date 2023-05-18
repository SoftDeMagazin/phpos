<?php
class UnitatiMasura extends AbstractDB
{
	var $useTable="unitati_masura";
	var $primaryKey="unitate_masura_id";
	var $form = array(
	"unitate_masura_id" => array(
		"input" => array("type" => "hidden"),
		"label" => false
		),
	"unitate_masura" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Unitate masura"
		),
	);
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>