<?php
class Retetar extends AbstractDB
{
	var $useTable = "retetar";
	var $primaryKey = "retetar_id";
	var $form = array();
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>
