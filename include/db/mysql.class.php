<?php
class MySQL
{	
	var $Link;
	var $Server;
	var $User;
	var $Pass;
	var $Db;
	
	function __construct($connect=TRUE)
		{
			if(file_exists('config/config.db.php')) {
				include_once('config/config.db.php');
				$this->Server = $cfgDb['server'];
				$this->User = $cfgDb['user'];
				$this->Pass = $cfgDb['pass'];
				$this->Db = $cfgDb['db'];
			}
			if($connect) $this -> connect();
		}
	
	function connect()
		{
		$this -> Link = mysqli_connect($this -> Server, $this -> User, $this -> Pass) 
		or die("Could not connect : " . mysqli_error());
		mysqli_select_db($this -> Link, $this -> Db) or die("Could not select database");
		}
	
	function query($sql)
		{
		$result = mysqli_query($this -> Link, $sql) or die(mysqli_error($this -> Link)." ".$sql);
		return $result;
		}
		
	function insertRow($sql)
		{
		$result = $this -> query($sql);
		$lastid = mysqli_insert_id($this -> Link);
		return $lastid;
		}		
	
	function numRows($sql)
		{
		$result = $this -> query($sql);
		return mysqli_num_rows($result);
		}	
	function tableColumns($table)
		{
		return $this -> getRows("SHOW COLUMNS FROM ". $table ."");
		}
	
	function getRow($sql)
		{
		$result = $this -> query($sql);
		if(mysqli_num_rows($result))
		{
		$array = mysqli_fetch_array($result);
		mysqli_free_result($result);
		return $array;
		}
		else
		{
		return NULL;
		}
		}
		
	function getRows($sql)
		{
		$result = $this -> query($sql);
		$array = NULL;
		$i = 0;
		while($ar = mysqli_fetch_array($result))
			{
			$array[$i] = $ar;
			$i++;
			}
		mysqli_free_result($result);	
		return $array;	
		}
	
	function getObjects($sql)
		{
		$result = $this -> query($sql);
		$array = NULL;
		$i = 0;
		while($ar = mysqli_fetch_object($result))
			{
			$array[$i] = $ar;
			$i++;
			}
		mysqli_free_result($result);
		return $array;	
		}
	
	function getObject($sql)
		{
		$result = $this -> query($sql);
		$array = mysqli_fetch_object($result);
		mysqli_free_result($result);
		return $array;
		}
	/* object to sql */
	function insertObject(&$obj, $table, $id)
		{
		$class_vars = get_object_vars($obj);
		$sql = "INSERT INTO $table (";
		$i = 0;	
		foreach ($class_vars as $name => $value) 
		    {
			if($name != $id && $value != NULL)
				{
				if($i==0) $sql .= "`$name`";
    			else $sql .=", `$name`";
				$i++;
				}
			}
		$sql .= ") VALUES (" ;	
		
		$i = 0;	
		foreach ($class_vars as $name => $value) 
		    {
			if($name != $id && $value != NULL)
				{
				if($i==0) $sql .= "'$value'";
    			else $sql .=", '$value'";
				$i++;
				} 
			}
		$sql .= ");";
	   	$lastid = $this -> insertRow($sql);
		$getSql = "SELECT * FROM $table WHERE $id = '$lastid'";
		$obj = $this -> getObject($getSql);
		}
	
	function updateObject(&$obj, $table, $id)
		{
		$class_vars = get_object_vars($obj);
		$sql = "UPDATE $table SET ";
		$i = 0;	
		foreach ($class_vars as $name => $value) 
		    {
			if($name != $id && (!empty($value) || $value == 0))
				{
				if($i==0) $sql .= "`$name` = '$value'";
    			else $sql .=", `$name` = '$value'";
				$i++;
				} 
			}
		$sql .= " WHERE $id = '". $class_vars[$id] ."'";	
		$this -> query($sql);
		$getSql = "SELECT * FROM $table WHERE $id = '". $class_vars[$id] ."'";
		$obj = $this -> getObject($getSql);
		}			
	
	function deleteObject($obj, $table, $id)
		{
		$class_vars = get_object_vars($obj);
		$sql = "DELETE FROM $table WHERE $id = '". $class_vars[$id] ."'";
		$this -> query($sql); 
		}	
		
	function arrayToSql($options = array())
		{
		$sql = "";
		if(is_array($options))
		{
		if(isset($options))
			{
			foreach($options as $key=>$option)
				{
				if(!is_numeric($key)) $sql .= " $key ".$option;
				else $sql .= " ".$option;
				}
			}
		}
		else
		{
		return $options;
		}	
		return $sql;
		}
	/* mysql helpers */
	function csv($options = array())
		{
		$sql = "";
		if(isset($options))
			{
			$i = 0;
			foreach($options as $key=>$option)
				{
				if($i) $sql .= ", ".$option;
				else $sql .= " ".$option;
				$i++;
				}
			}
		return $sql;
		}
	function between($s1, $s2)
		{
		return "BETWEEN '$s1' AND '$s2'";
		}		
	function equal($s)
		{
		return "= '$s'";
		}
	function like($s)
		{
		return " LIKE '%$s%'";
		}	
}

?>