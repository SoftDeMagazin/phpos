<?php
class DocModificariPret extends AbstractDB
{
	/*  */
	var $useTable = "doc_modificari_pret";
	var $primaryKey = "doc_modificare_pret_id";
	var $form = array();

	/* form processing */
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}

	function printDoc()
		{
		$out = '<h3 align="center">Lista diferente inventar nr. '. $this -> obj -> numar_document .'</h3>';
		$out .='<div align="center">Data: '. date('d/m/Y', strtotime($this -> obj -> data)) .'</div>';
		$mp = new ModificariPret($this -> mysql);
	  $nr_r =  $mp -> find(array("where doc_modificare_pret_id = '". $this -> obj -> doc_modificare_pret_id ."'"));
	if($nr_r)
		{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left", "id" => "componente");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Data", "Articol", "Pret vechi", "Pret nou", "Diferenta", "Stoc", "Val Diferenta");
					$gv -> tableOptions['ColWidth'] = array();
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $mp -> objects[$i];
						$prod = new Produse($this -> mysql, $obj -> produs_id);
						$diferenta = $obj -> pret_nou - $obj -> pret_vechi;
						$obj -> stoc = number_format($obj -> stoc, 2,'.','');
						$gv -> dataTable[$i]['data'] = array($obj -> data_modificare, $prod -> obj -> denumire, $obj -> pret_vechi, $obj -> pret_nou,number_format($diferenta, 2, '.','') , $obj -> stoc, number_format($obj -> stoc*$diferenta,2,'.',''));
						if($i%2==0) $class = "";
						else $class = "";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						
						);
						}
		$d = $gv -> getTable();
		}
	else
		{
		$d = "NU SUNT PRETURI NEACTIVATE";
		}	
		$out .= $d;
		return $out;
		}
	function nou()
		{
		$row = $this -> mysql -> getRow("SELECT MAX(numar_document) as nr from doc_modificari_pret");
		$this -> obj -> doc_modificare_pret_id = 0;
		$this -> obj -> data = date("Y-m-d H:i:s");
		$this -> obj -> numar_document = $row['nr']+1;
		$this -> obj -> user_id = $_SESSION['USERID'];
		$this -> save();
		}
}
?>