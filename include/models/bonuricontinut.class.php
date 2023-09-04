<?php
class BonuriContinut extends AbstractDB
{
	var $useTable="bonuri_continut";
	var $primaryKey="bon_continut_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}

class ViewBonuriContinut extends AbstractDB
{
	var $useTable="view_bonuri_continut";
	var $primaryKey="bon_continut_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>