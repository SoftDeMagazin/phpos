<?php
class AbstractDB
{	
	var $mysql;
	var $obj;
	var $objects;
	var $dataTable;
	
	function __construct($mysql, $id=NULL)
		{
		$this -> mysql = $mysql;
		if($id != NULL) $this -> get($id);
		else $this -> buildObj();
		}
	
	function setObjId($value)
		{
		$col = $this -> primaryKey;
		$this -> obj -> $col = $value;
		}
	
	function setObjValue($col, $value)
		{
		if(isset($value)) {
			$this -> obj -> $col = $value;
		}	
		}	

function setObjValueNull($col)
		{

			$this -> obj -> $col = NULL;
		}			
			
	function get($id)
		{
		$this -> obj = $this -> mysql -> getObject("SELECT * FROM ". $this -> useTable ." WHERE ". $this -> primaryKey ." = '$id'");
		}
	
	function resetObj()
		{
		$this -> obj = new stdClass();
		$col = $this -> primaryKey;
		$this -> obj -> $col = 0;
		}	
	
	function buildObj()
		{
		$fields = $this -> mysql -> getRows("SHOW COLUMNS FROM ". $this -> useTable ."");
		$this->obj = new stdClass();
		foreach($fields as $field)
			{
			$this -> setObjValueNull($field['Field']);
			}
		}
	
	function stringReplace($string, $obj)
		{
		$class_vars = get_object_vars($obj);
		if($class_vars)
		{
		foreach ($class_vars as $name => $value) 
		    {
			$string = str_replace("<%".$name."%>","'$value'",$string);
			}
		return $string;
		}	
		}
	
	function tableToForm()
		{
		$fields = $this -> mysql -> getRows("SHOW COLUMNS FROM ". $this -> useTable ."");
		
		foreach($fields as $field)
			{
			$form[$field['Field']] = array();
			}
		$this -> form = array_merge($form, $this -> form); 
		}
			
	function save()
		{
		$col = $this -> primaryKey;
		if(empty($this -> obj -> $col))
			{
			$this -> insert();
			}
		else 
			{
			$this -> update();
			}	
		}	
	
	/* functions data manipulation */
	function insert()
		{
		$this -> mysql -> insertObject($this -> obj, $this -> useTable, $this -> primaryKey);
		}
	
	function update()
		{
		$this -> mysql -> updateObject($this -> obj, $this -> useTable, $this -> primaryKey);
		}
	
	function delete()
		{
		$this -> mysql -> deleteObject($this -> obj, $this -> useTable, $this -> primaryKey);
		}	
	
	/* functions for getin' data */
	function findAll($dt=FALSE)
		{
		$sql = "SELECT * FROM ". $this -> useTable .";";
		if($dt)$this -> dataTable = $this -> mysql -> getRows($sql);
		else $this -> objects = $this -> mysql -> getObjects($sql);
		return count($this -> objects);
		}	
	
	function findAllBy($col, $value, $dt=FALSE)
		{
		$this -> objects = $this -> mysql -> getObjects("SELECT * FROM ". $this -> useTable ." WHERE $col = '$value';");
		return $this -> objects ? count($this -> objects) : 0;
		}
	
	function findBy($col, $value)
		{
		$this -> obj = $this -> mysql -> getObject("SELECT * FROM ". $this -> useTable ." WHERE $col = '$value'");
		}
	
	function find($options = array(), $cols = array())
		{
		if(count($cols))
			{
			$columns = $this -> mysql -> csv($cols);
			}
		else
			{
			$columns = "*";
			}	
		
		$sql = "SELECT ". $columns ." FROM ". $this -> useTable ." ". $this -> mysql -> arrayToSql($options) ."";
		$nr_r = $this -> mysql -> numRows($sql);
		
		if($nr_r == 0) return FALSE;
		else 
			{
			if(isset($dt)) $this -> dataTable = $this -> mysql -> getRows($sql);
			else $this -> objects = $this -> mysql -> getObjects($sql);
			return $nr_r;
			}	
		}
	
	function findLast($options = array())
		{
		$sql = '';
		if(isset($options))
			{
			foreach($options as $key=>$option)
				{
				if(!is_numeric($key)) $sql .= " $key ".$option;
				else $sql .= " ".$option;
				}
			}
		else $sql = "";
		$cmd = "SELECT * FROM ". $this -> useTable ." 
		WHERE 
		". $this -> primaryKey ." IN (SELECT MAX(". $this -> primaryKey .") FROM ". $this -> useTable ." $sql)
		;";
		$this -> obj = $this -> mysql -> getObject($cmd);
		}				
	
	/* form processing */
	function frmReplace(&$txt, $html)
		{
		$pri = $this -> primaryKey;
		$class_vars = get_object_vars($this -> obj);
		$i = 0;	
		if($class_vars) {
		foreach ($class_vars as $name => $value) 
		    {
			$html -> replace($txt, '<%'.$name.'%>', $value);
			}
		}
		}
	function frmGetValues($frmValues)
		{
		$i = 0;	
		$class_vars = get_object_vars($this -> obj);
		$i = 0;	
		foreach ($class_vars as $name => $value) 
		    {
			$this -> setObjValue($name, $frmValues[$name]);
			}
		}					
	
	function buildForm($ajax = false, $displayFrm = true)
		{
		$form = new Forms();
		$frm = "";
		$pK = $this -> primaryKey;
		if($displayFrm) $form -> append($frm, 
		$form -> create($this -> useTable, array("action" => "", "method" => "post"))
		);
		$form -> append($frm, '<table width="100%"  border="0" cellspacing="0" cellpadding="0">');
		foreach($this -> form as $key => $formElement)
			{
			$form -> append($frm, '<tr>');
			if($formElement['label']) $form -> append($frm, '<td width="30%"><label>'. $formElement['label'] .': <label></td>');
			else  $form -> append($frm, '<td width="30%">&nbsp;</td>');
			$form -> append($frm, '<td width="70%">'. $this -> formElement($key, $formElement) .'</td>');
			}
		if(!$ajax) $form -> append($frm, $form -> formEndButton());
		if($displayFrm) $form -> append($frm, $form -> formEnd());
		$form -> append($frm, '</table>');
		return $frm;
		}
	
	function input($column)
		{
		return $this -> formElement($column, $this -> form[$column]);
		}
	
	function formElement($column, $formElement)
		{
		$form = new Forms();
		if($formElement['input']['type'] == "select")
				{
				if(is_array($formElement['data_source']))
					{
					$options = $formElement['data_source'];
					}
				else
					{
					$rows = $this -> mysql -> getRows($formElement['data_source']);
					foreach($rows as $row)
						{
						$options[$row[0]] = $row[1];
						}
					}
				$formElement['input']['options'] = $options;
				$formElement['input']['selected'] = isset($this -> obj -> $column) ? $this -> obj -> $column : null;
				}
			else
				{
				if(array_key_exists("data_source", $formElement) && empty($this -> obj -> $column))
					{
					if(is_array($formElement['data_source']))
					{
					$row[0] = $formElement['data_source'][0];
					}
					else
					{
					$row = $this -> mysql -> getRow($formElement['data_source']);
					}
					$formElement['input']['value'] = $row[0];
					}
				else
					{	
					$formElement['input']['value'] = isset($this -> obj -> $column) ? $this -> obj -> $column : '';
					}	
				}
		return $form -> input($column, $formElement['input']);	
		}
	
	
	function saveForm($frmValues)
		{
		foreach($this -> form as $key => $formElement)
			{
			$this -> setObjValue($key, $frmValues[$key]);
			}
		$this -> save();
		}	

}
?>
