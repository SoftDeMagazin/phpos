<?php
class Etichete extends AbstractDB
{
	var $useTable="etichete";
	var $primaryKey="eticheta_id";
	var $form = array();
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function continut()
		{
		$continut = new EticheteContinut($this -> mysql);
		$nr_r = $continut -> findAllBy("eticheta_id", $this -> obj -> eticheta_id);
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Produs", "Numar etichete", "Cod");
					$gv -> tableOptions['ColWidth'] = array("50%", "25%","25%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $continut -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> denumire, $obj -> numar_etichete, $obj -> cod);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
			
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"xajax_editComponenta(". $obj -> eticheta_continut_id .");",
						);
						}
					return $gv -> getTable();
		}	
	
	function css()
		{
		$pageWidth = 209 - $this -> obj -> stanga - $this -> obj -> dreapta;
		$pageHeight = 296 - $this -> obj -> sus - $this -> obj -> jos;
		$etichetaWidth = number_format($pageWidth/$this -> obj -> numar_coloane, 2, '.','');
		$etichetaHeight = $this -> obj -> inaltime_eticheta;
		$eticheteRanduri = number_format($pageHeight/$this -> obj -> inaltime_eticheta, 0);
		
		$out = '
		<style type="text/css">
<!--
body
{
	margin-left: 0mm;
	margin-top: 0mm;
	margin-right: 0mm;
	margin-bottom: 0mm;
}
#content {
	border: 0.5mm solid #000;
	width:'. $pageWidth .'mm;
	height:'. $pageHeight .'mm;
}

#pagebreak
	{
	page-break-after: always;
	}

.eticheta
	{
	width:'. $etichetaWidth .'mm;
	height: '. $etichetaHeight .'mm;
	text-align:center;
	border: 0.1mm dotted #000;
	}	
-->
</style>
		';
		return $out;
		}	
		
	function printEtichete()
		{
		$row = $this -> mysql -> getRow("SELECT sum(numar_etichete) as nr_etichete FROM etichete_continut WHERE eticheta_id = '". $this -> obj -> eticheta_id ."'");
		$pageWidth = 209 - $this -> obj -> stanga - $this -> obj -> dreapta;
		$pageHeight = 296 - $this -> obj -> sus - $this -> obj -> jos;
		$etichetaWidth = number_format($pageWidth/$this -> obj -> numar_coloane, 2, '.','');
		$etichetaHeight = $this -> obj -> inaltime_eticheta;
		$eticheteRanduri = $pageHeight/$this -> obj -> inaltime_eticheta;
		$er =  explode('.', $eticheteRanduri);
		$eticheteRanduri = $er[0];
		$eticheteTotal = $eticheteRanduri*$this -> obj -> numar_coloane;
		$continut = new EticheteContinut($this -> mysql);
		$nr_r = $continut -> findAllBy("eticheta_id", $this -> obj -> eticheta_id);
		if($nr_r)
			{
			$i = 0;
			$out = '<div id="content">
  					<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr>';
			foreach($continut -> objects as $obj)
				{
				for($j=0; $j<$obj -> numar_etichete; $j++)
					{
					switch(strlen($obj -> cod))
						{
						case 8:
							{
							$cod = "ean8";
							$txt = substr($obj -> cod, 0, 7);
							}break;
						case 13:
							{
							$cod = "ean13";
							$txt = substr($obj -> cod, 0, 12);
							}break;
						default:
							{
							$cod = "code39";
							$txt = $obj -> cod;
							}
						}
					if($txt) $imgCod='<img src="thirdparty/barcode/html/image.php?code='. $cod .'&o=2&t=30&r=1&text='. $txt .'&f=3&a1=&a2=">';	
					else $imgCod='';

$prod = new Produse($this->mysql, $obj -> produs_id );
					$out .= '<td class="eticheta"><div>'. $imgCod .'</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" scope="col">'. $obj -> denumire .'</td>
  </tr>
  <tr>
    <td width=""><strong>Pret</strong></td>
    <td width=""><strong>'. number_format($prod -> obj -> pret, 2) .' LEI</strong></td>
  </tr>
  <tr>
    <td>Din care<br>TVA</td>
    <td>'. number_format($prod -> obj -> pret*24/124,2) .' LEI</td>
  </tr>
</table></td>';
					$i++;
					if($i%$this -> obj -> numar_coloane==0)
						{
						
						if($i%$eticheteTotal==0)
							{
							$out .= '</tr></table></div><samp id="pagebreak"></samp><div id="content">
  									<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr>';
							}
						else
							{	
							$out .= '</tr><tr>';
							}
						}
					
					}
				}
			}
		while($i%$this -> obj -> numar_coloane!=0)
			{
			$out .= '<td class="eticheta">&nbsp;</td>';
			$i++;
			}	
		$out .= '</tr></table></div>';	
		return $out;
		}
		
}

class EticheteContinut extends AbstractDB
{
	var $useTable="etichete_continut";
	var $primaryKey="eticheta_continut_id";
	var $form = array();
	
	function EticheteContinut($mysql, $id=NULL)
		{
		$this -> AbstractDB($mysql,$id);
		}
}
?>