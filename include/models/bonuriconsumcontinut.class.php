<?php
class BonuriConsumContinut extends AbstractDB
{
	/*  */
	var $useTable = "bonuri_consum_continut";
	var $primaryKey = "bon_consum_continut_id";
	var $form = array();

	/* form processing */
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}

}
?>