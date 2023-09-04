<?php
class Produse extends AbstractDB
{
	var $useTable = "produse";
	var $primaryKey = "produs_id";
	var $form = array(
		"categorie_id" => array(
			"input" => array("type" => "select"),
			"data_source" => "SELECT categorie_id, denumire_categorie FROM categorii ORDER BY denumire_categorie ASC"
			)
		);
		function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function btnProdus($options)
		{
		return '<button onClick="'. $options['onclick'] .'" onDblClick="'. $options['onclick'] .'" style="'. $options['style'] .'" class="'. $options['class'] .'" id="'.$options['id'].'">'. $options['value'] .'</button>';
		}
	
	function getStoc()
		{
		$row = $this -> mysql -> getRow("select stoc from view_stocuri_produse where produs_id = '". $this -> obj -> produs_id ."'");
		return number_format($row['stoc'], 2, '.', '');
		}
	
	function getComponente(&$array,$cantitate)
		{
		switch($this -> obj -> tip_produs)
		{
		case "reteta":
			{
			$retetar = new Retetar($this -> mysql);
			$nr_r = $retetar -> find("where produs_id = '". $this -> obj -> produs_id ."'");
			if($nr_r)
				{
				$prod = new Produse($this -> mysql);
				for($i=0;$i<$nr_r;$i++)
					{
					$obj = $retetar -> objects[$i];
					$prod -> get($obj -> componenta_id);
					$prod -> getComponente($array, $cantitate*$obj -> cantitate);
					}				
				}
			}break;
		case "serviciu":
			{
			$nr_r = count($array);	
			$array[$nr_r]['produs_id'] = $this -> obj -> produs_id;
			$array[$nr_r]['cant'] = 0;
			}break;	
		default:
			{
			for($i=0;$i<count($array);$i++)
				{
				if($array[$i]['produs_id'] == $this -> obj -> produs_id)
					{
					$array[$i]['cant'] += $cantitate;
					return true;
					}
				}
			$nr_r = count($array);	
			$array[$nr_r]['produs_id'] = $this -> obj -> produs_id;
			$array[$nr_r]['cant'] = $cantitate;
			}break;	
		}
		}
	
	function verificareStoc($cantitate)
		{
		switch($this -> obj -> tip_produs)
		{
		case "reteta":
			{
			$retetar = new Retetar($this -> mysql);
			$nr_r = $retetar -> find("where produs_id = '". $this -> obj -> produs_id ."'");
			if($nr_r)
				{
				$prod = new Produse($this -> mysql);
				for($i=0;$i<$nr_r;$i++)
					{
					$obj = $retetar -> objects[$i];
					$prod -> get($obj -> componenta_id);
					if($prod -> verificareStoc($cantitate*$obj -> cantitate) == FALSE) return FALSE;
					}				
				return TRUE;
				}
			return TRUE;	
			}break;
		case "serviciu":
			{
			return TRUE;
			}break;	
		default:
			{
			$row = $this -> mysql -> getRow("select stoc from view_stocuri_produse where produs_id = '". $this -> obj -> produs_id ."'");
			if($cantitate > $row['stoc']) return FALSE;
			else return TRUE;
			}break;	
		}
		}
			
	function scadereStoc($cantitate, $document_id, $tip)
		{
		if($this -> obj -> tip_produs != "reteta")
		{
				$loturiDisponibile = $this -> mysql -> getRows("
				SELECT intrare_continut_id, cantitate_ramasa 
				FROM intrari_continut
				WHERE cantitate_ramasa > 0
				  AND produs_id = '". $this -> obj -> produs_id ."' AND tip <> 'ajustare'
				ORDER BY data ASC  
				");
				$cantitateVanduta = $cantitate;
				if(isset($loturiDisponibile))
					{
					$terminat = 0;
					for($i = 0; $i<count($loturiDisponibile) && $terminat == 0;$i++)
						{
						if($cantitateVanduta <= $loturiDisponibile[$i]['cantitate_ramasa'])
							{
							$this -> mysql -> query("
							INSERT INTO iesiri
							(bon_continut_id, intrare_continut_id, cantitate, tip)
							VALUES
							('". $document_id ."','". $loturiDisponibile[$i]['intrare_continut_id'] ."','". $cantitateVanduta ."', '". $tip ."')
							");
							$this -> mysql -> query("
							UPDATE intrari_continut SET
							cantitate_ramasa = round(cantitate_ramasa - ". $cantitateVanduta .",3)
							WHERE intrare_continut_id = '". $loturiDisponibile[$i]['intrare_continut_id'] ."';
							");							
							$terminat = 1;
							$cantitateVanduta = 0;
							}
						else
							{
							$this -> mysql -> query("
							INSERT INTO iesiri
							(bon_continut_id, intrare_continut_id, cantitate, tip)
							VALUES
							('". $document_id ."','". $loturiDisponibile[$i]['intrare_continut_id'] ."','". $loturiDisponibile[$i]['cantitate_ramasa'] ."', '". $tip ."')
							");
							$this -> mysql -> query("
							UPDATE intrari_continut SET
							cantitate_ramasa = 0
							WHERE intrare_continut_id = '". $loturiDisponibile[$i]['intrare_continut_id'] ."';
							");
							$cantitateVanduta -= $loturiDisponibile[$i]['cantitate_ramasa'];							
							}	
						}
				}		
			
				if($cantitateVanduta != 0)
						{
						
							$lot = $this -> mysql -> getRow("SELECT intrare_continut_id, cantitate_ramasa FROM intrari_continut
							WHERE produs_id = '". $this -> obj -> produs_id ."' AND tip <> 'ajustare'
							ORDER BY data DESC
							LIMIT 0, 1;
							");
							if(!empty($lot))
							{
							$this -> mysql -> query("
							INSERT INTO iesiri
							(bon_continut_id, intrare_continut_id, cantitate, tip)
							VALUES
							('". $document_id ."','". $lot['intrare_continut_id'] ."','". $cantitateVanduta ."', '". $tip ."')
							");
							$this -> mysql -> query("
							UPDATE intrari_continut SET
							cantitate_ramasa = round(cantitate_ramasa - ". $cantitateVanduta .",3)
							WHERE intrare_continut_id = '". $lot['intrare_continut_id'] ."';
							");
							}							
						}
		}
		else
		{
		$retetar = $this -> mysql -> getRows("SELECT * FROM retetar WHERE produs_id = '". $this -> obj -> produs_id ."'");
		if($retetar)
			{
			foreach($retetar as $comp)
				{
				$prod = new Produse($this -> mysql, $comp['componenta_id']);
				$prod -> scadereStoc($cantitate*$comp['cantitate'], $document_id, $tip);
				}
			}
		}				
	
		}	
	
}

class ViewStocuriProduse extends AbstractDB
	{
	var $useTable="view_stocuri_produse";
	var $primaryKey = "produs_id";
	function ViewStocuriProduse($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
	}


class ViewStocuri extends AbstractDB
	{
	var $useTable="view_stocuri";
	var $primaryKey = "produs_id";
	function ViewStocuri($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
	}

class ValoareStoc extends AbstractDB
	{
	var $useTable="valoare_stoc";
	var $primaryKey="produs_id";
	function ValoareStoc($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql, $id);
		}
	}	
?>
