<?php
session_start();
header("Cache-control: private"); // IE 6 Fix 

require_once("configurari.common.php");

$mysql = new MySQL;
$html = new Html;


function loadObiecte($mod)
	{
	global $mysql;
	$objResponse = new xajaxResponse();
	switch($mod)
		{
		case "users":
			{
			$cls = new Users($mysql);
			$nr_r = $cls -> find(array("ORDER BY", "nume", "ASC"));
				if($nr_r)
					{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Utilizatori");
					$gv -> tableOptions['ColWidth'] = array("100%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $cls -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> nume);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm('$mod', ". $obj -> user_id .")",
						);
						}
					$objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
					$objResponse -> assign("form_name", "value", $cls -> useTable);
				}
				else
					{
					$objResponse -> assign("listaObiecte", "innerHTML", "");
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
			}break;
			case "furnizori":
			{
			$cls = new Furnizori($mysql);
			$nr_r = $cls -> find(array("ORDER BY", "nume", "ASC"));
				if($nr_r)
					{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Furnizori");
					$gv -> tableOptions['ColWidth'] = array("100%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $cls -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> nume);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm('$mod', ". $obj -> furnizor_id .")",
						);
						}
					$objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
					$objResponse -> assign("form_name", "value", $cls -> useTable);
				}
				else
					{
					$objResponse -> assign("listaObiecte", "innerHTML", "");
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
			}break;
			case "um":
			{
			$cls = new UnitatiMasura($mysql);
			$nr_r = $cls -> find(array("ORDER BY", "unitate_masura", "ASC"));
				if($nr_r)
					{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Unitati masura");
					$gv -> tableOptions['ColWidth'] = array("100%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $cls -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> unitate_masura);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm('$mod', ". $obj -> unitate_masura_id .")",
						);
						}
					$objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
					$objResponse -> assign("form_name", "value", $cls -> useTable);
				}
				else
					{
					$objResponse -> assign("listaObiecte", "innerHTML", "");
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
			}break;
			case "case":
			{
			$cls = new CaseFiscale($mysql);
			$nr_r = $cls -> find(array("ORDER BY", "nume_casa", "ASC"));
				if($nr_r)
					{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Case fiscale");
					$gv -> tableOptions['ColWidth'] = array("100%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $cls -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> nume_casa);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm('$mod', ". $obj -> casa_id .")",
						);
						}
					$objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
					$objResponse -> assign("form_name", "value", $cls -> useTable);
				}
				else
					{
					$objResponse -> assign("listaObiecte", "innerHTML", "");
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
			}break;
	
		case "categorii":
			{
			$cls = new Categorii($mysql);
			$nr_r = $cls -> find(array("ORDER BY", "denumire_categorie", "ASC"));
				if($nr_r)
					{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Departamente");
					$gv -> tableOptions['ColWidth'] = array("100%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $cls -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> denumire_categorie);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm('$mod', ". $obj -> categorie_id .")",
						);
						}
					$objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
				else
					{
					$objResponse -> assign("listaObiecte", "innerHTML", "");
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
			}break;
		case "moduriplata":
			{
			$cls = new ModuriPlata($mysql);
			$nr_r = $cls -> find(array("ORDER BY", "nume_mod", "ASC"));
				if($nr_r)
					{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Nume Mod Plata");
					$gv -> tableOptions['ColWidth'] = array("100%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $cls -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> nume_mod);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm('$mod', ". $obj -> mod_plata_id .")",
						);
					$objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
					$objResponse -> assign("form_name", "value", $cls -> useTable);
						}
					}
				else
					{
					$objResponse -> assign("listaObiecte", "innerHTML", "");
					$objResponse -> assign("form_name", "value", $cls -> useTable);
					}
			}break;	
		}
	$objResponse -> assign("divForm", "innerHTML", "");	
	$objResponse -> assign("config", "value", $mod);	
	return $objResponse;	
	}

function loadForm($mod, $id)
	{
	global $mysql;
	global $html;
	$objResponse = new xajaxResponse();
	switch($mod)
		{
		case "users":
			{
			$cls = new Users($mysql, $id);
			$form = $cls -> buildForm(true);
			$html -> append($form, 
			'<div><input type="button" value="Drepturi Utilizator" onClick="window.location.href= \'drepturi.users.php?user_id='. $id .'\'" class="btnTouch"></div>'
			);
			$objResponse -> assign("divForm", "innerHTML", $form);			
			}break;
		case "furnizori":
			{
			$cls = new Furnizori($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> buildForm(true));			
			}break;
		case "um":
			{
			$cls = new UnitatiMasura($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> buildForm(true));			
			}break;
		case "case":
			{
			$cls = new CaseFiscale($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> buildForm(true));			
			}break;
		case "categorii":
			{
			$cls = new Categorii($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> buildForm(true));			
			}break;
		case "moduriplata":
			{
			$cls = new ModuriPlata($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> buildForm(true));			
			}break;
		}	
	return $objResponse;	
	}

function btnSave($mod, $frmValues)
	{
		global $mysql;
		$id = null;
	$objResponse = new xajaxResponse();
	switch($mod)
		{
		case "users":
			{
			$cls = new Users($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> saveForm($frmValues));			
			}break;
		case "furnizori":
			{
			$cls = new Furnizori($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> saveForm($frmValues));			
			}break;
		case "um":
			{
			$cls = new UnitatiMasura($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> saveForm($frmValues));			
			}break;
		case "case":
			{
			$cls = new CaseFiscale($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> saveForm($frmValues));			
			}break;
		case "categorii":
			{
			$cls = new Categorii($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> saveForm($frmValues));			
			}break;
		case "moduriplata":
			{
			$cls = new ModuriPlata($mysql, $id);
			$objResponse -> assign("divForm", "innerHTML", $cls -> saveForm($frmValues));			
			}break;
		}	
	$objResponse = loadObiecte($mod);	
	return $objResponse;	
	}		
$xajax->processRequest();
?>