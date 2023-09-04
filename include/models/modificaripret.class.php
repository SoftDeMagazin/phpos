<?php
class ModificariPret extends AbstractDB
{
	/*  */
	var $useTable = "modificari_pret";
	var $primaryKey = "modificare_pret_id";
	var $form = array();

	/* form processing */
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}

}
?>