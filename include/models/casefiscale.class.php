<?php
class CaseFiscale extends AbstractDB
{
	/*  */
	var $useTable = "case_fiscale";
	var $primaryKey = "casa_id";
	var $form = array(
	"casa_id" => array(
		"input" => array("type" => "hidden"),
		"label" => false
		),
	"serie_fiscala" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Serie Fiscala"
		),
	"id" => array(
		"input" => array("type" => "text", "size" => 15),
		"label" => "Id Casa"
		),
	"nume_casa" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Nume Casa"
		),
	);
	/* form processing */	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}

}
?>