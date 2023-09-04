<?php
class IntrariContinut extends AbstractDB
{
	var $useTable="intrari_continut";
	var $primaryKey="intrare_continut_id";
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
class ViewIntrariContinut extends AbstractDB
{
	var $useTable="view_intrari_continut";
	var $primaryKey="intrare_continut_id";
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}

class ViewIesiriVanzari extends AbstractDB
{
	var $useTable="view_iesiri_vanzari";
	var $primaryKey="iesire_id";
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
}
?>