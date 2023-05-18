<?php
class BonuriPlata extends AbstractDB
{
	var $useTable="bonuri_plata";
	var $primaryKey="bon_plata_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>