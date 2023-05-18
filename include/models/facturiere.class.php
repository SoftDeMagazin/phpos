<?php
class Facturiere extends AbstractDB
{
	var $useTable="facturiere";
	var $primaryKey="facturier_id";
	var $form = array();
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>