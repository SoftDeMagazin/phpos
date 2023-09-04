<?php
session_start();
header("Cache-control: private"); // IE 6 Fix 

require_once("config.produse.common.php");


$mysql = new MySQL;
$html = new Html;

function btnCatalogProduse()
	{
	global $mysql;
	global $html;
	$categorii = new Categorii($mysql);
	$categorii -> find(array("ORDER BY", "denumire_categorie", "ASC"));
	$i=0;
	if(isset($categorii -> objects))
		{
		foreach($categorii -> objects as $objCategorie)
			{
			$content[$i] = array("name" => $objCategorie -> denumire_categorie, "value" => $objCategorie -> denumire_categorie, 
			"class" => "btn_catalog",
			"onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_catalogListaProduse(". $objCategorie -> categorie_id .");"
			);
			$i++;
			}
		}

	
	$options = array("width" => 900, "height" => 30, "scroll" => 800, "content" => $content);
	$tabView = new TabView;
	$innerHTML = $tabView -> printTabView($options, 2);
	$html -> append($innerHTML, '<div id="catalogLista" style="height:620px; overflow:auto; padding-left:10px; margin-top:10px;"></div>');
	$objResponse = afisareDialog($innerHTML, "900px", "750px", "60px", "10px", "RENUNTA");
	return $objResponse;
	}									

function catalogListaProduse($categorie_id, $frmFiltre=NULL)
	{
	global $mysql;
	global $html;
	$Produse = new ViewStocuriProduse($mysql);;
	
	if(!empty($frmFiltre['txtCodBare']) || !empty($frmFiltre['txtDenumire']))
	{
	$search = array("WHERE");
	if(!empty($frmFiltre['txtCodBare']))
		{
		$search["cod_bare"] = " = '". $frmFiltre['txtCodBare'] ."'";
		$used = true;
		}
	if(!empty($frmFiltre['txtDenumire']))
		{
		if($used) $search[$frmFiltre['radMod']] = "";
		$search['denumire'] = " LIKE '%". $frmFiltre['txtDenumire'] ."%'";
		}		
	}
	else
	{
	unset($search);
	$search = array("WHERE", "categorie_id" => " = '$categorie_id'");
	}
	$search['ORDER BY'] = $frmFiltre['orderBy'];
	$search[$frmFiltre['ordMod']] = "";		
	$nr_r = $Produse -> find($search);
	$objResponse = new xajaxResponse();
	if($nr_r)
		{
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => 850, "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Cod Bare", "Denumire Produs", "Pret", "Stoc");
		$gv -> tableOptions['ColWidth'] = array("150", "450", "150", "100");
		for($i=0;$i<$nr_r;$i++)
			{
			$obj = $Produse -> objects[$i];
			if(empty($obj -> stoc) || $obj -> tip_produs == "reteta" || $obj -> tip_produs == "serviciu") $obj -> stoc = 0;
			$obj -> stoc = number_format($obj -> stoc, 2, '.', '');
			$gv -> dataTable[$i]['data'] = array($obj -> cod_bare, $obj -> denumire, $obj -> pret, $obj -> stoc);
			if($i%2==0) $class = "roweven";
			else $class = "rowodd";
			
			if($obj -> la_vanzare == 'NU')
				{
				$class = "rowred";
				}
			
			$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
			"onMouseOver"=>"$(this).addClass('rowhover')", 
			"onMouseOut"=>"$(this).removeClass('rowhover')",
			"onClick"=>"$('#catalogLista tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_selectProdus('". $obj -> produs_id ."','". $obj -> denumire ."');",
			"onDblClick"=>"xajax_frmProdus(". $obj -> produs_id .");"
			);
			}
		$objResponse -> assign("catalogLista", "innerHTML", $gv -> getTable());
		}
	else
		{
		$objResponse -> assign("catalogLista", "innerHTML", "NICI UN PRODUS GASIT");
		}
	$objResponse -> assign("frm_categorie_id", "value", $categorie_id);		
	return $objResponse;	
	}
function selectProdus($produs_id, $denumire)
	{
	$objResponse = new xajaxResponse();
	$objResponse -> assign("frm_produs_id", "value", $produs_id);
	$objResponse -> assign("produsDenumire", "innerHTML", $denumire);
	return $objResponse;
	}

function stergeProdus($produs_id)
	{
	global $mysql;
	$produs = new Produse($mysql, $produs_id);
	$objResponse = btnRenuntaDialog();
	$categorie_id = $produs -> obj -> categorie_id;
	$produs -> delete();
	$objResponse -> script("xajax_catalogListaProduse(". $categorie_id .", xajax.getFormValues('frmFiltre'))");
	return $objResponse;
	}

function frmProdus($produs_id)
	{
	global $mysql;
	global $html;
	include("views/config.produse/frm.produse.php");
	$innerHTML = '';
	if($produs_id == 0)
		{
		$Produs = new Produse($mysql);
		$html -> replace($frmProdus, '<%titlu%>', 'Adauga produs');
		}
	else
		{
		$Produs = new Produse($mysql, $produs_id);
		$html -> replace($frmProdus, '<%titlu%>', 'Editare produs');
		}
	$Categorii = new Categorii($mysql);
	$Categorii -> find(array("ORDER BY", "denumire_categorie", "ASC"));
	if(isset($Categorii -> objects))
		{
		$cat = "";
		foreach($Categorii -> objects as $objCategorie)
			{
			if($objCategorie -> categorie_id == $Produs -> obj -> categorie_id) $selected = "selected";
			else $selected = "";
			$html -> append($cat,
				sprintf($html -> tags['option'], $html -> tagElements(array("value"=> $objCategorie -> categorie_id, $selected)), $objCategorie -> denumire_categorie) 
				);
			}
		$html -> replace($frmProdus, '<%categorie_id%>', $cat);	
		}
    $Cotetva = new CoteTva($mysql);
    $Cotetva->find(array("ORDER BY", "valoare", "DESC"));
    if(isset($Cotetva->objects)) {
        $cote = "";
        foreach ($Cotetva->objects as $objCotatva) {
            $selected = "";
            if($objCotatva->cotatva_id === $Produs->obj->cotatva_id) {
                $selected = "selected";
            }
            $html->append($cote,
                sprintf($html -> tags['option'], $html -> tagElements(array("value"=> $objCotatva -> cotatva_id, $selected)), $objCotatva -> valoare)
            );
        }
        $html -> replace($frmProdus, '<%cotatva_id%>', $cote);
    }
	if($Produs -> obj -> la_vanzare == 'NU')
		{
		$html -> replace($frmProdus, '<%la_vanzare%>', '<option value="DA">DA</option><option value="NU" selected>NU</option>');	
		}
	else
		{
		$html -> replace($frmProdus, '<%la_vanzare%>', '<option value="DA" selected>DA</option><option value="NU">NU</option>');	
		}	
	
	switch($Produs -> obj -> tip_produs)
	{
		case "marfa":
		{
		$html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa" selected>Marfa</option><option value="reteta">Reteta</option>
		<option value="mp">Materie prima</option>
		<option value="serviciu">Serviciu</option>');	
		}break;
		case "reteta":
		{
				$html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa">Marfa</option><option value="reteta" selected>Reteta</option>
		<option value="mp">Materie prima</option>
		<option value="serviciu">Serviciu</option>');	

		}break;
		case "mp":
		{
				$html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa">Marfa</option><option value="reteta">Reteta</option>
		<option value="mp" selected>Materie prima</option>
		<option value="serviciu">Serviciu</option>');	

		}break;
		case "serviciu":
		{
				$html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa" selected>Marfa</option><option value="reteta">Reteta</option>
		<option value="mo">Materie prima</option>
		<option value="serviciu" selected>Serviciu</option>');	

		}break;
		default:
		{
				$html -> replace($frmProdus, '<%tip_produs%>', '<option value="marfa" selected>Marfa</option><option value="reteta">Reteta</option>
		<option value="mp">Materie prima</option>
		<option value="serviciu">Serviciu</option>');	

		}break;
	}	
		
	$Produs -> frmReplace($frmProdus, $html);	
	$html -> append($innerHTML, $frmProdus);
	if($produs_id != 0) $html -> append($innerHTML, '<input type="button" value="STERGE PRODUS" onClick="r = confirm(\'Sigur sterg produs?\'); if(r) xajax_stergeProdus('. $produs_id .')">');	
		if($produs_id != 0 && $Produs -> obj -> tip_produs == "reteta") $html -> append($innerHTML, '<input type="button" value="RETETAR" onClick="xajax_retetar('. $produs_id .')">');		
	$objResponse = afisareDialog($innerHTML, "600px", "450px", "210px", "80px", NULL);
	$objResponse -> script("document.getElementById('denumire').focus();");
	return $objResponse;
	}	
	
function retetar($produs_id)
{
	global $mysql;
	global $html;
	$produs = new Produse($mysql, $produs_id);
	$innerHTML = '
	<div>'. $produs -> obj -> denumire .'</div>
	';
	$retetar = $mysql -> getRows("select * from retetar where produs_id = '$produs_id'");
	$html -> append($innerHTML, '<div id="componente" style="width::100%;height:300px;overflow:auto">'.componente($produs_id).'</div>');
	$html -> append($innerHTML, '<input type="button" value="ADAUGA COMPONENTA" onClick="xajax_retetarComponenta('. $produs_id .');">');
	$objResponse = window("comp".time(), $innerHTML, 620, 400);
	$objResponse -> script("xajax_btnRenuntaDialog()");
	return $objResponse;
}	


function retetarComponenta($produs_id, $retetar_id = 0)
	{
	$name = "add_comp".time();
	global $mysql;
	global $html;
	if($retetar_id == 0)
	{
	$out = '
	<form id="frmComp" name="frmComp">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="29%"><strong>Componenta</strong></td>
            <td width="71%"><input name="sursa_denumire" id="sursa_denumire" type="text" size="40" onKeyUp="if(event.keyCode == 13) { if(this.value == \'\') {xajax_cautaProdus(\'\', \'sursa\');} else {xajax_cautaProdus(this.value,\'sursa\');}}" onFocus="this.value = \'\';xajax.$(\'sursa_produs_id\').value=\'\';this.style.backgroundColor=\'#CCCCCC\';this.select();" onDblClick="xajax_cautaProdus();" onBlur="this.style.backgroundColor=\'#FFFFFF\'"></td>
          </tr>
          <tr>
            <td><input name="sursa_produs_id" type="hidden" id="sursa_produs_id" value="0">
			<input name="produs_id" type="hidden" id="produs_id" value="'. $produs_id .'"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Cantitate</strong></td>
            <td><input name="cantitate" type="text" id="sursa_cantitate"></td>
          </tr>
        </table>
	</form>	
	';
	$html -> append($out, '<input type="button" value="SALVEAZA" onClick="xajax_salveazaComponenta('. $produs_id .', xajax.getFormValues(\'frmComp\'));xajax_close_window(\''.$name.'\')">');
	}
	else
	{
	$retetar = new Retetar($mysql,$retetar_id);
	$prod = new Produse($mysql, $retetar -> obj -> componenta_id);
	$out = '
	<form id="frmComp" name="frmComp">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="29%"><strong>Componenta</strong></td>
            <td width="71%"><input name="sursa_denumire" value="'. $prod -> obj -> denumire .'" id="sursa_denumire" type="text" size="40" readonly></td>
          </tr>
          <tr>
            <td><input name="sursa_produs_id" type="hidden" id="sursa_produs_id" value="'. $prod -> obj -> produs_id .'">
			<input name="produs_id" type="hidden" id="produs_id" value="'. $produs_id .'">
			<input name="retetar_id" type="hidden" id="retetar_id" value="'. $retetar_id .'"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Cantitate</strong></td>
            <td><input name="cantitate" type="text" id="sursa_cantitate" value="'. $retetar -> obj -> cantitate .'"></td>
          </tr>
        </table>
	</form>	
	';
	$html -> append($out, '<input type="button" value="SALVEAZA" onClick="xajax_salveazaComponenta('. $produs_id .', xajax.getFormValues(\'frmComp\'));xajax_close_window(\''.$name.'\')">');
	$html -> append($out, '<br><br><br><input type="button" value="STERGE" onClick="r = confirm(\'Sigur sterg componenta?\'); if(r){xajax_stergeComponenta('. $retetar_id .');xajax_close_window(\''.$name.'\');}">');
	}
	$objResponse = window($name, $out, 620, 400);
	return $objResponse;
	}

function stergeComponenta($retetar_id)
{
	global $mysql;
	$retetar = new Retetar($mysql, $retetar_id);
	$retetar -> delete();
	$objResponse = new xajaxResponse();
	$objResponse -> assign("componente", "innerHTML", componente($retetar -> obj -> produs_id));
	return $objResponse;
}

function componente($produs_id)
{
	global $mysql;
	$retetar = $mysql -> getRows("select * from retetar where produs_id = '$produs_id'");
	if($retetar)
	{
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left", "id" => "componenteReteta");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Denumire Produs", "Cantitate");
		$gv -> tableOptions['ColWidth'] = array();

		foreach($retetar as $ret)
			{
			$prod = new Produse($mysql, $ret['componenta_id']);
			$gv -> dataTable[$i]['data'] = array($prod -> obj -> denumire, $ret['cantitate']);
			if($i%2==0) $class = "roweven";
			else $class = "rowodd";
						
			$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
			"onMouseOver"=>"$(this).addClass('rowhover')", 
			"onMouseOut"=>"$(this).removeClass('rowhover')",
			"onClick"=>"$('#componenteReteta tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_retetarComponenta('". $ret['produs_id'] ."','". $ret['retetar_id'] ."');"
			);
			$i++;
			}
		return $gv -> getTable(); 
		}
	else 
	{
	return "NU SUNT COMPONENTE";
	}					

}
	
function salveazaComponenta($produs_id,$frmValues)
	{
	global $mysql;
	global $html;
	$objResponse = new xajaxResponse();
	$comp = new Retetar($mysql);
	$form['produs_id'] = $produs_id;
	$form['componenta_id'] = $frmValues['sursa_produs_id'];
	$form['cantitate'] = $frmValues['cantitate'];
	$form['retetar_id'] = $frmValues['retetar_id'];
	$comp -> tableToForm();
	$comp -> saveForm($form);
	$objResponse -> assign("componente", "innerHTML", componente($produs_id));
	return $objResponse;
	}	
	
function cautaProdus($filtre = "", $action="")
	{
	global $mysql;
	global $html;
	
	$produse = new Produse($mysql);
	if($filtre)
	{
	$produse -> findBy('cod_bare', $filtre);
	if($produse -> obj)
		{
		$objResponse = selectProdus($produse -> obj -> produs_id, $action);
		return $objResponse;
		}
	$f[] = "where denumire like '%$filtre%'";
	}
	$f[] = "order by denumire asc";
	$nr_r = $produse -> find($f);
	$out = '';
	$windowName = "cautaProdus".time();
	if($nr_r)
		{
		$select = '<select name="cauta_produs_id" id="cauta_produs_id"  size="20" multiple style="width:300px;" onChange="fn_loadProdus(this.options[this.selectedIndex].text, this.options[this.selectedIndex].title)"  accesskey="b" onKeyUp="if(event.keyCode == 13) {xajax_selectProdusRetetar(this.options[this.selectedIndex].value, \''. $action .'\'); xajax_close_window(\''. $windowName .'\')}" onDblClick="xajax_selectProdusRetetar(this.options[this.selectedIndex].value,\''. $action .'\'); xajax_close_window(\''. $windowName .'\')">';
		$html -> append($out, $select);
		foreach($produse -> objects as $obj)
			{
			if($obj -> la_vanzare == 'NU') $class = "rowred";
			else $class = "";
				
			$html -> append($out, '<option value="'. $obj -> produs_id .'" title="'. $obj -> pret .'" class="'.$class.'">'. $obj -> denumire .'</option>');
			}
		$html -> append($out, '</select>');	
		}
	$innerHTML = '<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="47%" rowspan="4" valign="top">'. $out .'</td>
    <td width="23%"><strong>PRODUS</strong></td>
    <td width="30%"><div id="div_denumire"></div></td>
  </tr>
  <tr>
    <td><strong>PRET VANZARE </strong></td>
    <td><div id="div_pret"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
';	
	$objResponse = window($windowName, $innerHTML, 800, 400); 
	$objResponse -> script("xajax.$('cauta_produs_id').focus();");
	$objResponse -> script("xajax.$('cauta_produs_id').selectedIndex=0;");
	return $objResponse;
	}

function selectProdusRetetar($produs_id, $action="")
	{
	global $mysql;
	global $html;
	$produs = new Produse($mysql, $produs_id);
	$objResponse = new xajaxResponse();
	$objResponse -> assign($action."_produs_id", "value", $produs -> obj -> produs_id);
	$objResponse -> assign($action."_denumire", "value", $produs -> obj -> denumire);
	return $objResponse;
	}
	
	
function frmSave($frmValues)
	{
	global $mysql;
	global $html;
	global $cfgStocuri;
	$Produs = new Produse($mysql, $frmValues['produs_id']);
	$nr_r = $Produs -> findAllBy("cod_bare", $frmValues['cod_bare']);
	if($nr_r != 0) 
		{
		if(!$frmValues['produs_id'] && $nr_r==1)
			{
			$objResponse = new xajaxResponse();
			$objResponse -> alert('Cod bare existent la produsul:'.$Produs -> objects[0] -> denumire);
			return $objResponse;
			}
			
		}
	
	if($frmValues['produs_id'] && $cfgStocuri['modificari_pret'])
		{
		if($frmValues['pret'] <> $Produs -> obj -> pret)
			{
			$mysql -> query("delete from modificari_pret where produs_id = '". $frmValues['produs_id'] ."' and activat = 'NU'");
			$mp = new ModificariPret($mysql);
			$mp -> obj -> produs_id = $frmValues['produs_id'];
			$mp -> obj -> pret_vechi = $Produs -> obj -> pret;
			$mp -> obj -> pret_nou = $frmValues['pret'];
			$frmValues['pret'] = $Produs -> obj -> pret;
			$mp -> obj -> data_modificare = date("Y-m-d H:i:s");
			$mp -> obj -> user_id = $_SESSION['USERID'];
			$mp -> obj -> stoc = $Produs -> getStoc();
			$mp -> save();
			}
		}
		
	$Produs -> frmGetValues($frmValues);
	$Produs -> save();
	$objResponse = btnRenuntaDialog();
	$objResponse -> script("xajax_catalogListaProduse(". $Produs -> obj -> categorie_id .",xajax.getFormValues('frmFiltre'))");
	if(empty($frmValues['produs_id']))
		{
		switch($frmValues['produs_id'])
		{
		case "reteta": {}break;
		case "serviciu":
			{
$sql = "INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, activ, tip, data) 
							   VALUES ('-1', '-1', '". $Produs -> obj -> produs_id ."', '0', '0', 0, 1, 'init', '". date("Y-m-d") ."');
						";
			$mysql -> query($sql);
			}break;
		case "mp":
		case "marfa":
			{
			$sql = "INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, activ, tip, data) 
							   VALUES ('-1', '-1', '". $Produs -> obj -> produs_id ."', '0', '0', 0, 1, 'init', '". date("Y-m-d") ."');
						";
			$mysql -> query($sql);
			}break;	
		}
		}
	return $objResponse;	
	}
$xajax->processRequest();
?>