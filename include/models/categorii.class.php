<?php
class Categorii extends AbstractDB
{
	/*  */
	var $useTable = "categorii";
	var $primaryKey = "categorie_id";
	var $form = array(
	"categorie_id" => array(
		"input" => array("type" => "hidden"),
		"label" => false
		),
	"denumire_categorie" => array(
		"input" => array("type" => "text", "size" => 25),
		"label" => "Denumire Categorie"
		)
	);

	/* form processing */
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}

	function btnCategorie($options)
		{
		return '<button onClick="'. $options['onclick'] .';" style="'. $options['style'] .'" class="'. $options['class'] .'" id="'.$options['id'].'">'. $options['value'] .'</button>';
		}						
}
?>