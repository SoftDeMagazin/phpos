<?php
class CoteTva extends AbstractDB
{
	var $useTable = "cotetva";
	var $primaryKey = "cotatva_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>
