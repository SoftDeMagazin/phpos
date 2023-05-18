<?php
class Users extends AbstractDB
{
	/*  */
	var $useTable = "users";
	var $primaryKey = "user_id";
	var $form = array(
	"user_id" => array(
		"input" => array("type" => "hidden"),
		"label" => false
		),
	"parola" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Parola"
		),
	"nume" => array(
		"input" => array("type" => "text", "size" => 30),
		"label" => "Nume Utilizator"
		),
	"activ" => array(
		"input" => array("type" => "select"),
		"data_source" => array("DA" => "DA", "NU" => "NU"),
		"label" => "Activ"
		),
	);
	/* form processing */	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	function verificaDrept($drept,$user_id=NULL)
		{
		if($user_id == NULL)
			{
			$user_id = $this -> obj -> user_id;
			}
		$rows = $this -> mysql -> numRows("SELECT drepturi.drept_id, drepturi.nume_drept 
			FROM drepturi_users 
			INNER JOIN drepturi on drepturi_users.drept_id = drepturi.drept_id
			WHERE user_id = '$user_id' AND drepturi.nume_drept = '$drept'");
		if($rows) return true;
		return false;
		}	
	function verificaDreptButton($drept,$user_id=NULL)
		{
		if($user_id == NULL)
			{
			$user_id = $this -> obj -> user_id;
			}
		if($this -> verificaDrept($drept, $user_id)) return "";
		else return "disabled";
		}
}

class Drepturi extends AbstractDB
{	
	var $useTable = "drepturi";
	var $primaryKey = "drept_id";
	function Drepturi($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
}
class DrepturiUsers extends AbstractDB
{	
	var $useTable = "drepturi_users";
	var $primaryKey = "drept_user_id";
	function Drepturi($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
}
?>