<?php
class ZileEconomice extends AbstractDB
{
	var $useTable = "zile_economice";
	var $primaryKey = "zi_economica_id";
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function getLastDay()
		{
		$this -> findLast(array("WHERE", "inchis" => " = 'NU'"));
		}
	
	function closeDay($user_id)
		{
		$this -> getLastDay();
		$closed = $this -> obj;
		$this -> setObjValue("inchis", "DA");
		$this -> setObjValue("ora_inchidere", date("H:i:s"));
		$this -> setObjValue("user_id", $user_id);
		$this -> save();
		$this -> resetObj();
		$this -> setObjId(0);
if($closed -> data == '2012-10-28') {
$this -> setObjValue("data", '2012-10-29');

} else {
		$this -> setObjValue("data", date("Y-m-d", strtotime($closed -> data) + 3600*24));
}
		$this -> setObjValue("inchis", "NU");
		$this -> save();
		$this -> getLastDay();
		return $closed;
		}		
}
?>
