<?php
class ComenziContinut extends AbstractDB
{
	var $useTable = "comenzi_continut";
	var $primaryKey = "comanda_continut_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function plusCantitate()
		{
		$this -> setObjValue("cantitate", $this -> obj -> cantitate + 1);
		$this -> save();
		}
	
	function minusCantitate()
		{
		$cantitateNoua = $this -> obj -> cantitate - 1;
		if($cantitateNoua > 0)
			{
			$this -> setObjValue("cantitate", $cantitateNoua);
			$this -> save();
			}
		else
			{
			$this -> stergeProdus();
			}	
		}
	
	function stergeProdus()
		{
		$this -> delete();
		}			
}

class ViewComenziContinut extends AbstractDB
{
	var $useTable = "view_comenzi_continut";
	var $primaryKey = "comanda_continut_id";
	function ViewComenziContinut($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}

}
?>
