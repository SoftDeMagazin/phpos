<?php
session_start();
header("Cache-control: private"); // IE 6 Fix 

require_once("comanda.common.php");

/*         DB              */

$mysql = new MySQL;
$html = new Html;
function onLoad($comanda_id=NULL)
	{
	global $mysql;
	global $html;
	if($comanda_id == 0)
	{
	$zi = new ZileEconomice($mysql);
	$zi -> getLastDay();
	$comanda = new Comenzi($mysql);
	$comanda -> setObjId(0);
	$comanda -> setObjValue("user_id", $_SESSION['USERID']);
	$comanda -> setObjValue("data", date('Y-m-d H:i:s'));
	$comanda -> setObjValue("zi_economica_id", $zi -> obj -> zi_economica_id);
	$comanda -> setObjValue("casa_id", $_SESSION['CASAID']);
	$comanda -> save();
	}
	else
	{
	$comanda = new Comenzi($mysql, $comanda_id);
	$Bon = new Bonuri($mysql);
	$Bon -> findBy("comanda_id", $comanda_id);
		if($Bon -> obj -> avans == 'DA' && $Bon -> obj -> achitat = 'NU')
		{
		$out = "<h3>Inchidere bon avans!</h3>";
		$out .='
		Avans achitat:'. number_format($Bon -> obj -> total * $Bon -> obj -> suma_avans/100,2) .'<br />
		Rest de plata:'. number_format($Bon -> obj -> total - $Bon -> obj -> total * $Bon -> obj -> suma_avans/100,2) .'
		<div align="center"><input type="button" name="btnAchita" value="ACHITA REST PLATA" onClick="xajax_inchidereBonAvans('. $Bon -> obj -> bon_id .')"/></div>
		<div align="center"><input type="button" name="btnAchita" value="IESIRE" onClick="window.location.href = \'login.php\';"/></div>
		';
		$objResponse = afisareDialog($out, "600px", "530px", "210px", "20px", FALSE);
		return $objResponse;
		}	
	include("views/comanda/rest.php");
	$objResponse = afisareDialog($innerHTML, "600px", "530px", "210px", "20px", FALSE);
	$objResponse -> assign("comanda_id", "value", $comanda -> obj -> comanda_id);
	return $objResponse;
	}
	
	$categorii = new Categorii($mysql);
	$categorii -> find(array("ORDER BY denumire_categorie ASC")); 
	$txt = "";
	if(isset($categorii -> objects))
		{
		foreach($categorii -> objects as $objCategorie)
			{
			$onClick='xajax_afiseazaCategorie('.$objCategorie -> categorie_id.', '. $comanda -> obj -> comanda_id .');this.blur();';
			$txt = $txt.''. $categorii -> btnCategorie(array('onclick' => $onClick, 'style' => 'width:190px; height:75px','value' => $objCategorie -> denumire_categorie)) .'';
			}
		$txt = $txt."";
		}
	else
		{
		$txt = "NICI O CATEGORIE!";
		}
	
	$ModPlata = new ModuriPlata($mysql);
	$ModPlata -> findAll();
	$html -> append($innerHTML, '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>');
	$user = new Users($mysql, $_SESSION['USERID']);	
	if(isset($ModPlata -> objects))
		{
		foreach($ModPlata -> objects as $objModPlata)
			{
			$onClick = "xajax_plataRapida(document.getElementById('comanda_id').value, ". $objModPlata -> mod_plata_id .");this.disabled = true;this.blur();";
			if(($objModPlata -> nume_mod == "PROTOCOL")  || ($objModPlata -> nume_mod == "EXPIRATE") || ($objModPlata -> nume_mod == "TRANSFER")) {
			if($user  -> verificaDrept('protocol') == TRUE ) {
				$btn = $html -> button(array("name" => $objModPlata -> nume_mod, 
				"value" => $objModPlata -> nume_mod, 
				"class" => "btnModPlataRapid",
				"onClick" => $onClick,
				"style"=> "width:180px"));
				$html -> append($innerHTML, '<td align="center">');
				$html -> append($innerHTML, $btn);
				$html -> append($innerHTML, '</td>');
			}	
			}
			else if($objModPlata -> nume_mod != "PROTOCOL" &&  $objModPlata -> nume_mod != "EXPIRATE" ){
			$btn = $html -> button(array("name" => $objModPlata -> nume_mod, 
			"value" => $objModPlata -> nume_mod, 
			"class" => "btnModPlataRapid",
			"onClick" => $onClick,
			"style"=> "width:180px"
			));
			$html -> append($innerHTML, '<td align="center">');
			$html -> append($innerHTML, $btn);
			$html -> append($innerHTML, '</td>');
			}
			}
		}
	$html -> append($innerHTML, '</tr></table>');	

	
	$casa = new CaseFiscale($mysql, $_SESSION['CASAID']);
	$nr_bon = $mysql -> getRow("SELECT * FROM rpt_bonuri_emise where zi_economica_id = '". $zi -> obj -> zi_economica_id ."' and casa_id = '". $comanda -> obj -> casa_id ."'");		
	$bon_curent = $nr_bon['bonuri_emise'] + 1;
	$objResponse = loadContinutComanda($comanda -> obj -> comanda_id);
	$objResponse -> assign("categorii", "innerHTML", $txt);
	$objResponse -> assign("comanda_id", "value", $comanda -> obj -> comanda_id);
	$objResponse -> assign("nrcomanda", "innerHTML", "". $bon_curent ."");
	$objResponse -> assign("moduriplata", "innerHTML", $innerHTML);
	$objResponse -> assign("utilizator", "innerHTML", $user -> obj -> nume);
	$objResponse -> assign("nume_casa", "innerHTML", $casa -> obj -> nume_casa);
	return $objResponse;
	}

function afiseazaCategorie($categorie_id, $comanda_id)
	{
	global $mysql;
	$produse = new Produse($mysql);
	$produse -> find(array("where", "categorie_id" => "=".$categorie_id, "ORDER BY denumire ASC")); 
	$txt = "";
	if(isset($produse -> objects))
		{
		foreach($produse -> objects as $objProdus)
			{
			$onClick="xajax_marcareProdus($comanda_id, ". $objProdus -> produs_id .", document.getElementById('cantitate').value); this.blur()";
			$txt = $txt.''. $produse -> btnProdus(array('onclick' => $onClick, 'style' => 'width:237px; height:50px; background-color:#FAFAFA; padding:0','value' => $objProdus -> denumire)) .'';
			}
		$txt = $txt."";
		}
	else
		{
		$txt = "NICI UN PRODUS IN CATEGORIE";
		}	
	$objResponse = new xajaxResponse();
	$objResponse -> assign("produse", "innerHTML", $txt);
	return $objResponse;
	}

function loadContinutComanda($comanda_id)
	{
	global $mysql;
	$comanda = new Comenzi($mysql, $comanda_id);
	$objResponse = new xajaxResponse();
	$objResponse -> assign("continutcomanda", "innerHTML", $comanda -> comandaContinut(FALSE));
	$objResponse -> assign("comandatotal", "innerHTML", "".$comanda -> calculeazaTotal());
	return $objResponse; 
	}	

function marcareProdus($comanda_id, $produs_id, $cantitate)
	{
	global $mysql;
	$comanda = new Comenzi($mysql, $comanda_id);
	if(!$cantitate) $cantitate = 1;
	$comanda -> adaugaProdus($produs_id, $cantitate);
	$objResponse = new xajaxResponse();
	$objResponse -> assign("continutcomanda", "innerHTML", $comanda -> comandaContinut(FALSE));
	$objResponse -> assign("comandatotal", "innerHTML", "".$comanda -> calculeazaTotal());
	$objResponse -> assign("cantitate", "value", "");
	$objResponse -> script("document.getElementById('continutcomanda').scrollTop = document.getElementById('continutcomanda').scrollHeight;");
	$objResponse -> assign("btnIesireComanda", "disabled", true);
	return $objResponse; 
	}

function clickProdusComanda($comanda_continut_id)
	{
	global $mysql;
	$innerHTML = '
<form name="frmSelect" id="frmSelect">
<input type="hidden" name="comanda_continut" id="comanda_continut" value="'. $comanda_continut_id .'">
<table width="100%"  border="0" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td align="center"><input type="button" class="btnTouch" value=" " style="height:75px;background-image:url(files/img/gtk-add.png); background-position:center; background-repeat:no-repeat;" onClick="xajax_btnPlus('. $comanda_continut_id .');this.blur()" onDblClick="xajax_btnPlus('. $comanda_continut_id .');this.blur()"></td>';
    /*<td align="center"><input type="button" class="btnTouch" value=" " style="height:75px;background-image:url(files/img/gtk-remove.png); background-position:center; background-repeat:no-repeat;" onClick="xajax_btnMinus('. $comanda_continut_id .');this.blur()" onDblClick="xajax_btnMinus('. $comanda_continut_id .');this.blur()"></td>*/
	$innerHTML .= '
    <td align="center"><input type="button" name="btnStergeProdus" id="btnStergeProdus" class="btnTouch" value=" " style="height:75px;background-image:url(files/img/gtk-close.png); background-position:center; background-repeat:no-repeat;"  onClick="xajax_btnSterge('. $comanda_continut_id .');this.blur();xajax_btnRenuntaDialog();" onDblClick="xajax_btnSterge('. $comanda_continut_id .');this.blur();xajax_btnRenuntaDialog();"></td>
  </tr>
</table>
</form>
';
	$objResponse = afisareDialog($innerHTML, "550px", "200px", "250px", "30px", "IESIRE");
	return $objResponse;
	}

function btnPlus($comanda_continut_id)
	{
	global $mysql;
	$ComandaContinut = new ComenziContinut($mysql, $comanda_continut_id);
	$ComandaContinut -> plusCantitate();
	$objResponse = new xajaxResponse();
	$comanda  = new Comenzi($mysql, $ComandaContinut -> obj -> comanda_id);
	$objResponse -> assign("continutcomanda", "innerHTML", $comanda -> comandaContinut(FALSE));
	$objResponse -> assign("comandatotal", "innerHTML", "".$comanda -> calculeazaTotal());
	return $objResponse; 
	}
function btnMinus($comanda_continut_id)
	{
	global $mysql;
	$ComandaContinut = new ComenziContinut($mysql, $comanda_continut_id);
	$ComandaContinut -> minusCantitate();
	$objResponse = new xajaxResponse();
	$comanda  = new Comenzi($mysql, $ComandaContinut -> obj -> comanda_id);
	$objResponse -> assign("continutcomanda", "innerHTML", $comanda -> comandaContinut(FALSE));
	$objResponse -> assign("comandatotal", "innerHTML", "".$comanda -> calculeazaTotal());
	return $objResponse; 
	}

function btnSterge($comanda_continut_id)
	{
	global $mysql;
	$ComandaContinut = new ComenziContinut($mysql, $comanda_continut_id);
	$ComandaContinut -> stergeProdus();
	$objResponse = new xajaxResponse();
	$mysql -> query("
insert into retururi 
	( 
	produs_id, 
	utilizator_id, 
	cantitate, 
	valoare, 
	data, 
	ora
	)
	values
	('". $ComandaContinut -> obj -> produs_id ."', 
	'". $_SESSION['USERID'] ."', 
	'". $ComandaContinut -> obj -> cantitate ."', 
	'". $ComandaContinut -> obj -> valoare ."', 
	'". date("Y-m-d") ."', 
	'". date("H:i:s") ."'
	)
	");
	$comanda  = new Comenzi($mysql, $ComandaContinut -> obj -> comanda_id);
	$objResponse -> assign("continutcomanda", "innerHTML", $comanda -> comandaContinut(FALSE));
	$objResponse -> assign("comandatotal", "innerHTML", "".$comanda -> calculeazaTotal());
	if($comanda -> countProduse() == 0) {
		$objResponse -> assign("btnIesireComanda", "disabled", false);
	}
	return $objResponse; 
	}

function btnCauta($frmValues, $comanda_id, $cantitate)	
	{
	global $mysql;
	if(!$cantitate) $cantitate = 1; 
if($frmValues['txtsearch'] == '01031980') {
$objResponse = new xajaxResponse();
$objResponse -> assign("txtsearch", "value", "");
$objResponse -> assign("btnStergeProdus", "disabled", false);
$objResponse -> assign("btnStergeProdus", "style.backgroundColor", "#FAFAFA");
return $objResponse;
}
	switch($frmValues['searchtype'])
		{
		case "codbare":
			{
			if(!empty($frmValues['txtsearch']))
			{
			$start = substr($frmValues['txtsearch'],0,2);
			$Produs = new Produse($mysql);
			if($start != '27')
			{
			$cod = $frmValues['txtsearch'];	
			}
			else
			{
			$cod = substr($frmValues['txtsearch'],2, 5);
			$cantitate = substr($frmValues['txtsearch'], 7, 5);
			$cantitate = number_format($cantitate/1000, 3, '.', '');
			}		
			$nr_r = $Produs -> find(array("where", "cod_bare" => $mysql -> equal($cod), "and", "la_vanzare <> 'NU'"));
			if($nr_r)
				{
				if($nr_r == 1)
					{
					$objResponse = marcareProdus($comanda_id, $Produs -> objects[0] -> produs_id, $cantitate);
					$objResponse -> assign("cantitate", "value", "");		
					}
				else
					{
					$objResponse = new xajaxResponse();
					$objResponse -> alert("Codul $cod exista de mai multe ori in sistem! Anuntati administrator program!");	
					}	
				}
			else
				{
				$objResponse = new xajaxResponse();
				$objResponse -> alert("Cod neidentificat".$cod);
				}
			}
			else
			{
			$objResponse = new xajaxResponse();
			$objResponse -> alert("Nu ati introdus cod!");
			}	
			}break;
		case "codintern":
			{
			$Produs = new Produse($mysql);
			$Produs -> findBy("cod", $frmValues['txtsearch']);
			if(!empty($Produs -> obj))
				{
				$objResponse = marcareProdus($comanda_id, $Produs -> obj -> produs_id, $cantitate);
				$objResponse -> assign("cantitate", "value", "");		
				}
			else
				{
				$objResponse = new xajaxResponse();
				$objResponse -> alert("Cod neidentificat");
				}	

			}break;
		case "denumire":
			{
			$Produs = new Produse($mysql);
			$nr_r = $Produs -> find(array("WHERE", "denumire" => $mysql -> like($frmValues['txtsearch']), "and", "la_vanzare <> 'NU'"));
			if($nr_r)
				{
				$gv = new GridView;
				$gv -> tableOptions['tag'] = array("width" => "90%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
				$gv -> tableOptions['head'] = array("class"=> "rowhead");
				$gv -> columns = array("Cod Bare", "Denumire Produs", "Pret");
				$gv -> tableOptions['ColWidth'] = array("25%", "50%", "25%");
				for($i=0;$i<$nr_r;$i++)
					{
					$obj = $Produs -> objects[$i];
					$gv -> dataTable[$i]['data'] = array($obj -> cod_bare, $obj -> denumire, $obj -> pret);
					if($i%2==0) $gv -> dataTable[$i]['tag'] = array("class"=>"roweven", "style"=>"height:30px;", 
					"onClick"=>"xajax_marcareProdus(document.getElementById('comanda_id').value, ". $obj -> produs_id .", ". $cantitate .");xajax_btnRenuntaDialog();"
					);
					else $gv -> dataTable[$i]['tag'] = array("class"=>"rowodd", "style"=>"height:30px;",
					"onClick"=>"xajax_marcareProdus(document.getElementById('comanda_id').value, ". $obj -> produs_id .", ". $cantitate .");xajax_btnRenuntaDialog();"
					);
				}
				$objResponse = 	afisareDialog('<div style="height:400px; overflow:scroll;" >'.$gv -> getTable().'</div>', "500px", "500px", "250px", "100px", "RENUNTA");
				}
			else
				{
				$objResponse = new xajaxResponse();
				}		
			}		
		}
	$objResponse -> assign("txtsearch", "value", "");
	//$objResponse -> script("document.getElementById('txtsearch').focus()");
	return $objResponse;	
	}

/* functii  */

function golesteComanda($comanda_id)
	{
	global $mysql;
	$comanda  = new Comenzi($mysql, $comanda_id);
	$comanda -> golesteComanda();
	$objResponse = btnRenuntaDialog();
	$objResponse -> assign("continutcomanda", "innerHTML", $comanda -> comandaContinut());
	$objResponse -> assign("comandatotal", "innerHTML", $comanda -> calculeazaTotal());
	return $objResponse; 
	}

function btnMarcajRapid()
	{
	global $mysql;
	include("views/marcaj.rapid.php");
	$objResponse = afisareDialog($view, "800px", "350px", "100px", "150px", "IESIRE");
	$objResponse -> script("setTimeout(function(){document.getElementById('txtCodRapid').focus();}, 0);");
	return $objResponse;
	}

function marcareRapid($frmValues, $comanda_id)
	{
	global $mysql;
	if(!empty($frmValues['txtCantitateRapid']) && is_numeric($frmValues['txtCantitateRapid']) && $frmValues['txtCantitateRapid'] != 0)
		{
		$cantitate = $frmValues['txtCantitateRapid'];
		}
	else 
		{
		$cantitate = 1;
		}	
			$Produs = new Produse($mysql);
			$Produs -> findBy("cod_bare", $frmValues['txtCodRapid']);
			if(!empty($Produs -> obj))
				{
				$objResponse = marcareProdus($comanda_id, $Produs -> obj -> produs_id, $cantitate);
				$comanda = new Comenzi($mysql, $comanda_id);
				$ComandaContinut = new ViewComenziContinut($mysql);
				$ComandaContinut -> findLast(array("WHERE", "comanda_id" => " = '$comanda_id'"));
				$txt .= $comanda -> comandaContinutHead(FALSE);
				$txt .= $comanda -> comandaContinutRow($ComandaContinut -> obj, "rowodd", FALSE);
				$txt .= $comanda -> comandaContinutFooter(FALSE);
				$objResponse -> assign("ultimulProdus", "innerHTML", $txt);
				}
			else
				{
				$objResponse = new xajaxResponse();
				$objResponse -> assign("ultimulProdus", "innerHTML", "<h3>Cod neidentificat</h3>");
				}	
	$objResponse -> assign("txtCantitateRapid", "value", 0);	
	$objResponse -> assign("txtCodRapid", "value", "");
	return $objResponse;
	}		

function btnFunctii()
	{
	include("views/comanda.functii.php");
	$objResponse = afisareDialog($view, "800px", "450px", "100px", "200px");
	return $objResponse;
	}

function btnPlata($comanda_id, $frmValues)
	{
	global $mysql;
	global $html; 
	
	include("views/plata.php");
	$comanda = new Comenzi($mysql, $comanda_id);
	
	if(!$comanda -> countProduse())
	{
	$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu sunt produse marcate!<h3>', "400px", "150px", "350px", "250px", "OK");
	return $objResponse;
	}
	global $cfgStocuri;
	if($cfgStocuri['verific_stoc'])
			{
		if($comanda -> verificareStocuriPlus() == FALSE)
		{
			$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Stoc insuficient pentru inchiderea bonului!<h3>', "400px", "150px", "350px", "250px", "OK");
			return $objResponse;
		}
		}
	$innerHTML = '';
	$html -> append($innerHTML, '<div id="divwindowhead">Plata</div>');
	$ModPlata = new ModuriPlata($mysql);
	$ModPlata -> findAll();
	$html -> append($moduriPlata, '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">');
	
	if(isset($ModPlata -> objects))
		{
		foreach($ModPlata -> objects as $objModPlata)
			{
			$onClick = "xajax_plataAppendMod(xajax.getFormValues('frmPlata'), ". $objModPlata -> mod_plata_id .", document.getElementById('txtSumaPlata').value); ";
			$btn = $html -> button(array("name" => $objModPlata -> nume_mod, 
			"value" => $objModPlata -> nume_mod, 
			"class" => "btnModPlata",
			"onClick" => $onClick));
			$html -> append($moduriPlata, '<tr><td align="center">');
			$html -> append($moduriPlata, $btn);
			$html -> append($moduriPlata, '</td></tr>');
			}
		}
	$html -> append($moduriPlata, '</tr></table>');	
	$html -> replace($view, '<%moduriPlata%>', $moduriPlata);
	$html -> replace($view, '<%varTotalPlata%>', number_format($comanda -> calculeazaTotal(), 2, '.', ''));
	$html -> append($innerHTML, $view);
	/*$innerHTML .= $comanda -> comandaContinutHead(FALSE);
	if(isset($frmValues['chkContinutComanda']))
		{
		$i = 0;
		foreach($frmValues['chkContinutComanda'] as $comanda_continut_id)
			{
			if(!empty($comanda_continut_id))
				{
				if($i % 2 == 0 ) $rowClass = "roweven";
				else $rowClass = "rowodd";
				$ComandaContinut = new ComenziContinut($mysql, $comanda_continut_id);
				$innerHTML .= $comanda -> comandaContinutRow($ComandaContinut -> obj, $rowClass, FALSE);
				$i++;
				}
			}
		}
	$innerHTML .= $comanda -> comandaContinutFooter();*/	
	$objResponse = afisareDialog($innerHTML, "900px", "730px", "50px", "20px", "RENUNTA");
	return $objResponse;
	}

function btnCui() {
	$keyboard = new KeyBoard();
	$innerHTML = $keyboard -> printHtml('', '');
	$innerHTML .= $keyboard->printCss();
	$innerHTML .= $keyboard->printJavaScript();
	$objResponse = afisareDialog($innerHTML, '800px', '450px', '100px', '200px', null);
	return $objResponse;
}	

function plataAppendMod($frmPlata, $mod_plata_id, $suma)
	{
	global $mysql;
	global $html;
	include("views/plata.php");
	
	$total = 0;
	if(isset($frmPlata['suma']))
		{
		foreach($frmPlata['suma'] as $sumaMod)
			{
			$total += $sumaMod;
			}
		}
	$total += $suma;
	$ramas = number_format($frmPlata['txtTotalPlata'], 2, '.', '')-number_format($total, 2,'.','') ;	
	$objResponse = new xajaxResponse();
	if($suma > 0 && $ramas >= 0)
	{
	$ModPlata = new ModuriPlata($mysql, $mod_plata_id);
	$html -> replace($rowMod, '<%varIdMod%>', $mod_plata_id);
	$html -> replace($rowMod, '<%varNumeMod%>', $ModPlata -> obj -> nume_mod);
	$html -> replace($rowMod, '<%varSuma%>', number_format($suma, 2, '.', ''));
	$objResponse -> append("divPlata", "innerHTML", $rowMod);
	$objResponse -> assign("txtTotalAchitat", "value", number_format($total, 2, '.', ''));
	$objResponse -> assign("txtTotalRamas", "value", number_format($ramas, 2, '.', ''));
	$objResponse -> assign("txtSumaPlata", "value", 0);
	if($ramas == 0) $objResponse -> assign("btnSavePlata", "disabled", false);
	}
	else
	{
	$objResponse -> assign("txtSumaPlata", "value", 0);
	}
	return $objResponse;
	}

function btnSavePlata($frmPlata, $comanda_id)
	{
	global $mysql;
	global $html;
	$objResponse = new xajaxResponse();
	$Bon = new Bonuri($mysql);
	if(!$Bon -> plata($frmPlata, $comanda_id))
		{
		$objResponse = btnResetPlata($frmPlata);
		return $objResponse;
		}
	//include("views/comanda/rest.php");
	//$objResponse = afisareDialog($innerHTML, "600px", "530px", "210px", "20px", FALSE);
	$objResponse -> script("window.location.href='comanda.php'");
	return $objResponse;
	}

function calculeazaRest($numerar, $txtSumaPlata)
	{
	$objResponse = new xajaxResponse();
	$objResponse -> assign("rest_datorat", "innerHTML", number_format($txtSumaPlata - $numerar, 2, '.', ''));
	return $objResponse;
	}

function btnResetPlata($frmPlata)
	{
	$objResponse = new xajaxResponse();
	$objResponse -> assign("divPlata", "innerHTML", "");
	$objResponse -> assign("txtTotalAchitat", "value", number_format(0, 2, '.', ''));
	$objResponse -> assign("txtTotalRamas", "value", number_format($frmPlata['txtTotalPlata'], 2, '.', ''));
	$objResponse -> assign("txtSumaPlata", "value", 0);
	$objResponse -> assign("btnSavePlata", "disabled", true);
	return $objResponse;
	}			

function plataRapida($comanda_id, $mod_plata_id)
	{
	global $mysql;
	global $html;
	global $cfgStocuri;
	$comanda = new Comenzi($mysql, $comanda_id);
	if($comanda -> countProduse())
	{
if($cfgStocuri['verific_stoc'])
			{
		if($comanda -> verificareStocuriPlus() == TRUE)
		{
		$objResponse = new xajaxResponse();
		$Bon = new Bonuri($mysql);
		$Bon -> plataRapida($comanda_id, $mod_plata_id);
		//include("views/comanda/rest.php");
		//$objResponse = afisareDialog($innerHTML, "600px", "530px", "210px", "20px", FALSE);
		$objResponse -> script("window.location.href='comanda.php'");
		return $objResponse;
		}
		else
		{
		$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Stoc insuficient pentru inchiderea bonului!<h3>', "400px", "150px", "350px", "250px", "OK");
		}
}
else
{
	$objResponse = new xajaxResponse();
	$Bon = new Bonuri($mysql);
	$Bon -> plataRapida($comanda_id, $mod_plata_id);
	//include("views/comanda/rest.php");
	//$objResponse = afisareDialog($innerHTML, "600px", "530px", "210px", "20px", FALSE);
	$objResponse -> script("window.location.href='comanda.php'");
	return $objResponse;
}
	}
	else
	{
	$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu sunt produse marcate!<h3>', "400px", "150px", "350px", "250px", "OK");
	}
	return $objResponse;
	}

function inchidereBonAvans($bon_id)
{
	global $mysql;
	global $html;
	global $cfgStocuri;
	$bon = new Bonuri($mysql, $bon_id);
	$comanda = new Comenzi($mysql, $bon -> obj -> comanda_id);
	if($cfgStocuri['verific_stoc'])
		{
		if($comanda -> verificareStocuriPlus() == FALSE)
			{
			$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Stoc insuficient pentru inchiderea bonului!<h3><div align="center"><input type="button" name="btnAchita" value="IESIRE" onClick="window.location.href = \'login.php\';"/></div>', "400px", "150px", "350px", "250px", NULL);
			return $objResponse;
			}
		}
	$bon -> obj -> achitat = 'DA';
	$bon -> save();
	$bon -> emiteBonFiscal();
	$bon -> scadereStocuri();	
	$objResponse = new xajaxResponse();
	$objResponse -> alert('Am inchis bon!');
	$objResponse -> script("window.location.href = 'comanda.php'");
	return $objResponse;	
}	
	
function frmPlataCuAvans($comanda_id)
{
	global $mysql;
	global $html;
	global $cfgStocuri;
	$comanda = new Comenzi($mysql, $comanda_id);
	if($comanda -> countProduse())
	{
	if($cfgStocuri['verific_stoc'])
		{
		if($comanda -> verificareStocuriPlus() == FALSE)
			{
			$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Stoc insuficient pentru inchiderea bonului!<h3>', "400px", "150px", "350px", "250px", "OK");
		return $objResponse;
			}
		}	

	$innerHTML = '<strong>Procent avans</strong>
	<input type="text" id="suma_avans" name="suma_avans">';
	$ModPlata = new ModuriPlata($mysql);
	$ModPlata -> findAll();
	$html -> append($innerHTML, '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>');
	if(isset($ModPlata -> objects))
		{
		foreach($ModPlata -> objects as $objModPlata)
			{
			$onClick = "xajax_plataCuAvans($comanda_id, document.getElementById('suma_avans').value, ". $objModPlata -> mod_plata_id .");this.blur();";
			$btn = $html -> button(array("name" => $objModPlata -> nume_mod, 
			"value" => $objModPlata -> nume_mod, 
			"class" => "btnModPlataRapid",
			"onClick" => $onClick));
			$html -> append($innerHTML, '<td align="center">');
			$html -> append($innerHTML, $btn);
			$html -> append($innerHTML, '</td>');
			}
		}
	$html -> append($innerHTML, '</tr></table>');	
	return afisareDialog($innerHTML, "800px", "600px", "100px", "75px", "OK");
	}
	else
	{
	$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu sunt produse marcate!<h3>', "400px", "150px", "350px", "250px", "OK");
	return $objResponse;
	}
}	

function plataCuAvans($comanda_id, $suma_avans, $mod_plata_id)
{
	global $mysql;
	global $html;
	global $cfgStocuri;
	$comanda = new Comenzi($mysql, $comanda_id);
	if($comanda -> countProduse())
	{
	if($cfgStocuri['verific_stoc'])
		{
		if($comanda -> verificareStocuriPlus() == FALSE)
			{
			$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Stoc insuficient pentru inchiderea bonului!<h3>', "400px", "150px", "350px", "250px", "OK");
		return $objResponse;
			}
		}	
	$Bon = new Bonuri($mysql);
	$Bon -> adaugaBon($comanda_id);
	$Bon -> obj -> avans = 'DA';
	$Bon -> obj -> suma_avans = $suma_avans;
	$Bon -> obj -> achitat = 'NU';
	$Bon -> save();
	$Bon -> adaugaModPlata($Bon -> obj -> total, $mod_plata_id);
	$Bon -> emiteBonFiscal();	
	$objResponse = new xajaxResponse();
	$objResponse -> script("window.location.href = 'comanda.php';");
	return $objResponse;
	}
	else
	{
	$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu sunt produse marcate!<h3>', "400px", "150px", "350px", "250px", "OK");
	}
	return $objResponse;
}

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

	
	$options = array("width" => 500, "height" => 30, "scroll" => 400, "content" => $content);
	$tabView = new TabView;
	$ds = new divScroll;
	$innerHTML = $tabView -> printTabView($options, 2);
	$html -> append($innerHTML, $ds -> printHtml('catalogLista',600, 602));
	$objResponse = afisareDialog($innerHTML, "600px", "750px", "10px", "10px", "RENUNTA");
	return $objResponse;
	}									

function catalogListaProduse($categorie_id)
	{
	global $mysql;
	global $html;
	$Produse = new ViewStocuriProduse($mysql);
	$nr_r = $Produse -> find(array("WHERE", "categorie_id" => "='$categorie_id'", "ORDER BY", "denumire", "ASC"));
	$objResponse = new xajaxResponse();
	if($nr_r)
		{
		$gv = new GridView;
		$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
		$gv -> tableOptions['head'] = array("class"=> "rowhead");
		$gv -> columns = array("Denumire Produs", "Pret");
		$gv -> tableOptions['ColWidth'] = array("75%", "25%");
		for($i=0;$i<$nr_r;$i++)
			{
			$obj = $Produse -> objects[$i];
			if(empty($obj -> stoc)) $obj -> stoc = 0;
			$obj -> stoc = number_format($obj -> stoc, 2, '.', '');
			$gv -> dataTable[$i]['data'] = array($obj -> denumire, $obj -> pret);
			if($i%2==0) $gv -> dataTable[$i]['tag'] = array("class"=>"roweven", 
			//"onMouseOver"=>"$(this).addClass('rowhover')", 
			//"onMouseOut"=>"$(this).removeClass('rowhover')",
			"onDblClick"=>"xajax_marcareProdus(document.getElementById('comanda_id').value, ". $obj -> produs_id .", 1);"
			);
			else $gv -> dataTable[$i]['tag'] = array("class"=>"rowodd",
			//"onMouseOver"=>"$(this).addClass('rowhover')",
			//"onMouseOut"=>"$(this).removeClass('rowhover')",
			"onDblClick"=>"xajax_marcareProdus(document.getElementById('comanda_id').value, ". $obj -> produs_id .", 1);"
			);
			}
		$objResponse -> assign("catalogLista", "innerHTML", $gv -> getTable());
		}
	else
		{
		$objResponse -> assign("catalogLista", "innerHTML", "NICI UN PRODUS IN CAEGORIE");
		}	
	return $objResponse;	
	}

function emiteFactura($client_id)
	{
	global $mysql;
	global $html;
	if(!$client_id)
		{
		$objResponse = btnCatalogClienti("xajax_emiteFactura(<%client_id%>);");
		}
	else
		{
		$innerHTML = '';
		$client = new Clienti($mysql, $client_id);
		$html -> append($innerHTML, $client -> displayClient());
		$facturiere = new Facturiere($mysql);
		$nr_r = $facturiere -> find(array("where" , "inchis" => $mysql -> equal('NU')));
		if($nr_r)
			{
					$gv = new GridView;
					$gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
					$gv -> tableOptions['head'] = array("class"=> "rowhead");
					$gv -> columns = array("Serie", "Start", "Stop", "Curent");
					$gv -> tableOptions['ColWidth'] = array("25%", "25%","25%", "25%");
					for($i=0; $i<$nr_r;$i++)
						{
						$obj = $facturiere -> objects[$i];
						$gv -> dataTable[$i]['data'] = array($obj -> serie, $obj -> start, $obj -> stop, $obj -> curent);
						if($i%2==0) $class = "roweven";
						else $class = "rowodd";
						$gv -> dataTable[$i]['tag'] = array("class"=>$class, 
						"onMouseOver"=>"$(this).addClass('rowhover')", 
						"onMouseOut"=>"$(this).removeClass('rowhover')",
						"onClick"=>"xajax_folosesteFacturier(". $client -> obj -> client_id .", ". $obj -> facturier_id .", document.getElementById('comanda_id').value)",
						);
						}
			$html -> append($innerHTML, $gv -> getTable());
			$objResponse = afisareDialog($innerHTML, "900px", "750px", "60px", "10px", "RENUNTA");			
			}
		else
			{
			}	
		}
	return $objResponse;
	}

function folosesteFacturier($client_id, $facturier_id, $comanda_id)
	{
	global $mysql;
	$bon = new Bonuri($mysql);
	$bon -> findBy("comanda_id", $comanda_id);
	$facturier = new Facturiere($mysql, $facturier_id);
	$client = new Clienti($mysql, $client_id);
	$factura = new Facturi($mysql);
	$frmValues['factura_id'] = 0;
	$frmValues['client_id'] = $client_id;
	$frmValues['bon_id'] = $bon -> obj -> bon_id;
	$frmValues['serie'] = $facturier -> obj -> serie;
	$frmValues['numar'] = $facturier -> obj -> curent + 1;
	$frmValues['facturier_id'] = $facturier -> obj -> facturier_id;
	$facturier -> obj -> curent = $facturier -> obj -> curent + 1;
	$facturier -> save();
	$frmValues['data'] = date("Y-m-d");
	$frmValues['data_emitere'] = date("Y-m-d H:i:s");
	$factura -> tableToForm();
	$factura -> saveForm($frmValues);
	$objResponse = new xajaxResponse();
	$objResponse -> script("window.location.href = 'print.factura.php?factura_id=". $factura -> obj -> factura_id ."';");
	return $objResponse;
	}

function selectClientFactura($client_id)
	{
	global $mysql;
	$client = new Clienti($mysql, $client_id);
	$objResponse = btnRenuntaDialog();
	$objResponse -> assign("div_client_factura", "innerHTML", $client -> obj -> denumire);
	$objResponse -> assign("factura_client_id", "value", $client -> obj -> client_id);
	return $objResponse;
	}

function bonConsum($comanda_id)
	{
	global $mysql;
	$comanda = new Comenzi($mysql, $comanda_id);
	global $cfgStocuri;
	if($cfgStocuri['verific_stoc'])
	{
		if($comanda -> verificareStocuriPlus() == FALSE)
		{
			$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Stoc insuficient pentru inchiderea bonului!<h3>', "400px", "150px", "350px", "250px", "OK");
			return $objResponse;
		}
	}

	if($comanda -> countProduse())
	{
	$bonconsum = new BonuriConsum($mysql);
	$bonconsum -> adaugaBonConsum($comanda_id);
	$bonconsum -> scadereProduse();
	$objResponse = new xajaxResponse();
	$objResponse -> alert('Am adaugat bon consum nr. '. $bonconsum -> obj -> numar_document .'');
	$objResponse -> script("window.location.href = 'comanda.php'");
	}
	else
	{
	$objResponse = afisareDialog('<div id="divwindowhead">Info!</div><h3 align="center">Nu sunt produse marcate!<h3>', "400px", "150px", "350px", "250px", "OK");
	}
	return $objResponse;
	}

$xajax->processRequest();
?>