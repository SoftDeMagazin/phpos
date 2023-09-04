<?php
class Transformari extends AbstractDB
{
	var $useTable = "transformari";
	var $primaryKey = "transformare_id";
	var $form = array();
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function proceseaza()
		{
		$cant_sursa = $this -> obj -> sursa_cantitate;
		$cant_dest = $this -> obj -> destinatie_cantitate;
				$loturiDisponibile = $this -> mysql -> getRows("
				SELECT *
				FROM intrari_continut
				WHERE cantitate_ramasa > 0
				  AND produs_id = '". $this -> obj -> sursa_produs_id ."'
				  AND tip <> 'ajustare'
				ORDER BY data ASC  
				");
				$cantitateVanduta = $cant_sursa;
				$array_pret = array();
				$j = 0;
				if(isset($loturiDisponibile))
					{
					$terminat = 0;
					for($i = 0; $i<count($loturiDisponibile) && $terminat == 0;$i++)
						{
						$lot = $loturiDisponibile[$i];
						if($cantitateVanduta <= $loturiDisponibile[$i]['cantitate_ramasa'])
							{
							$this -> mysql -> query("
							UPDATE intrari_continut SET
							cantitate_ramasa = round(cantitate_ramasa - ". $cantitateVanduta .",3)
							WHERE intrare_continut_id = '". $loturiDisponibile[$i]['intrare_continut_id'] ."';
							");
							$this -> mysql -> query("
							INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare,pret_vanzare, adaos_unit, activ, tip, data)
							VALUES ('". $this -> obj -> transformare_id ."','". $lot['intrare_continut_id'] ."', '". $lot['produs_id'] ."','-". $cantitateVanduta ."','0','". $lot['pret_intrare'] ."','". $lot['pret_vanzare'] ."', '". $lot['adaos_unit'] ."',  1, 'ajustare_transformare','". $this -> obj -> data_transformare."' )
							");							
							$terminat = 1;
							$array_pret[$j]['cant'] = $cantitateVanduta;
							$array_pret[$j]['pret_intrare'] = $loturiDisponibile[$i]['pret_intrare'];
							$j++;
							$cantitateVanduta = 0;
							}
						else
							{
							$this -> mysql -> query("
							UPDATE intrari_continut SET
							cantitate_ramasa = round(cantitate_ramasa - ". $cantitateVanduta .",3)
							WHERE intrare_continut_id = '". $loturiDisponibile[$i]['intrare_continut_id'] ."';
							");
							$this -> mysql -> query("
							INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare,pret_vanzare, adaos_unit, activ, tip, data)
							VALUES ('". $this -> obj -> transformare_id ."','". $lot['intrare_continut_id'] ."', '". $lot['produs_id'] ."','-". $cantitateVanduta ."','0','". $lot['pret_intrare'] ."','". $lot['pret_vanzare'] ."', '". $lot['adaos_unit'] ."',  1, 'ajustare_transformare','". $this -> obj -> data_transformare."' )
							");							
							$cantitateVanduta -= $loturiDisponibile[$i]['cantitate_ramasa'];
							$array_pret[$j]['cant'] = $loturiDisponibile[$i]['cantitate_ramasa'];
							$array_pret[$j]['pret_intrare'] = $loturiDisponibile[$i]['pret_intrare'];
							$j++;
							}	
						}
				}	
		if($array_pret)
		{
		foreach($array_pret as $ar)
			{
			$valoare_intrari += $ar['cant']*$ar['pret_intrare'];
			}
		}	
		$pret_intrare = number_format($valoare_intrari/$this -> obj -> destinatie_cantitate, 4);
		$sql =  "
		INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, activ, tip, data)
 		VALUES ('". $this -> obj -> transformare_id ."','0','". $this -> obj -> destinatie_produs_id."','". $this -> obj -> destinatie_cantitate."','". $this -> obj -> destinatie_cantitate."', '$pret_intrare', 1, 'transformare_destinatie','". $this -> obj -> data_transformare."' )
		";
		$this -> mysql -> query($sql);
		}
}
?>
