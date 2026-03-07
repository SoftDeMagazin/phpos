<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("evidenta.loturi.common.php");

$mysql = new MySQL;
$html = new Html;
$clickCatalogProduse = "xajax_selectProdus(<%produs_id%>,'<%denumire%>');xajax_btnRenuntaDialog();";
function genereazaLista($frmFiltre)
{
    $objResponse = new xajaxResponse();
    global $mysql;
    $filtre[] = "WHERE";
    $filtre['data'] = $mysql -> between($frmFiltre['dateStart'], $frmFiltre['dateStop']);
    if ($frmFiltre['produs_id']) {
        $filtre[] = "AND";
        $filtre['produs_id'] = $mysql -> equal($frmFiltre['produs_id']);
    }
    $filtre[] = "ORDER BY data ASC";
    $niruri = new ViewIntrariContinut($mysql);
    $nr_r = $niruri -> find($filtre);
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Data Lot", "Cantitate initiala", "Cantitate ramasa", "Pret Achizitie", "Pret Vanzare");
        $gv -> tableOptions['ColWidth'] = array("30%", "25%", "25%", "10%", "10%");
        $i=0;
        foreach ($niruri -> objects as $obj) {
            $gv -> dataTable[$i]['data'] = array($obj -> data, $obj -> cantitate, $obj -> cantitate_ramasa, $obj -> pret_intrare, $obj -> pret_vanzare);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            $gv -> dataTable[$i]['tag'] = array("class"=> $class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"xajax_infoLot(". $obj -> intrare_continut_id .")",
            );
            $i++;
        }
        $objResponse -> assign("preview", "innerHTML", $gv -> getTable());
        $objResponse -> assign("actiuni", "innerHTML", '');
    } else {
        $objResponse -> assign("preview", "innerHTML", 'NU SUNT LOTURI CE CORESPUND CRITERIILOR CAUTATE');
        $objResponse -> assign("actiuni", "innerHTML", '');
    }
    return $objResponse;
}

function infoLot($intrare_continut_id)
{
    global $html;
    global $mysql;
    $lot = new ViewIntrariContinut($mysql, $intrare_continut_id);
    switch ($lot -> obj -> tip) {
        case "nir":
        {
            $nir = new Niruri($mysql, $lot -> obj -> nir_id);
            $furnizor = new Furnizori($mysql, $nir -> obj -> furnizor_id);
            $innerHTML = '
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr class="rowhead">
    <td>Lot</td>
    <td>'. $lot -> obj -> data .' </td>
    <td>'. $lot -> obj -> denumire .'</td>
  </tr>
  <tr>
    <td><strong>Cantitate initiala </strong></td>
    <td>'. $lot -> obj -> cantitate .'</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Cantitate ramasa </strong></td>
    <td>'. $lot -> obj -> cantitate_ramasa .'</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="rowhead">
    <td>INFO NIR </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Nir nr.</strong> '. $nir -> obj -> numar_nir .' </td>
    <td><strong>Data factura</strong> '. $nir -> obj ->  data_factura .'</td>
    <td><strong>Furnizor</strong> '. $furnizor -> obj -> nume .'</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="btnVanzari" type="button" class="btnTouch" id="btnVanzari" value="Detaliere vanzari" onClick="xajax_detaliereVanzari('. $intrare_continut_id .')">
    </div></td>
    <td><div align="center">
    </div></td>
    <td><div align="center">
      <input name="btnInchide" type="button" class="btnTouch" id="btnInchide" value="INCHIDE" onClick="xajax_btnRenuntaDialog();">
    </div></td>
  </tr>
</table>
	';
        }break;
        
        case "modificare_pret":
        {
            $mp = new ModificariPret($mysql, $lot -> obj -> nir_componenta_id);
            $doc = new DocModificariPret($mysql, $mp -> obj -> doc_modificare_pret_id);
            $innerHTML = '
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr class="rowhead">
    <td>Lot</td>
    <td>'. $lot -> obj -> data .' </td>
    <td>'. $lot -> obj -> denumire .'</td>
  </tr>
  <tr>
    <td><strong>Cantitate initiala </strong></td>
    <td>'. $lot -> obj -> cantitate .'</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Cantitate ramasa </strong></td>
    <td>'. $lot -> obj -> cantitate_ramasa .'</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="rowhead">
    <td>INFO LDI </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>LDI nr.</strong> '. $doc -> obj -> numar_document .' </td>
    <td><strong>Data LDI</strong> '. $doc -> obj ->  data .'</td>
    <td>&nbsp;</td>
  <tr>
    <td><div align="center">
      <input name="btnVanzari" type="button" class="btnTouch" id="btnVanzari" value="Detaliere vanzari" onClick="xajax_detaliereVanzari('. $intrare_continut_id .')">
    </div></td>
    <td><div align="center">
    </div></td>
    <td><div align="center">
      <input name="btnInchide" type="button" class="btnTouch" id="btnInchide" value="INCHIDE" onClick="xajax_btnRenuntaDialog();">
    </div></td>
  </tr>
</table>
	';
        }break;
    }
    $objResponse = afisareDialog($innerHTML, "600px", "300px", "200px", "130px", null);
    return $objResponse;
}

function detaliereVanzari($intrare_continut_id)
{
    global $html;
    global $mysql;
    $lot = new ViewIntrariContinut($mysql, $intrare_continut_id);
    $iesiri = new ViewIesiriVanzari($mysql);
    $iesiri -> findAllBy("intrare_continut_id", $intrare_continut_id);
    $innerHTML = '
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr class="rowhead">
    <td>Lot</td>
    <td>'. $lot -> obj -> data .' </td>
    <td>'. $lot -> obj -> denumire .'</td>
  </tr>
  <tr>
    <td><strong>Cantitate initiala </strong></td>
    <td>'. $lot -> obj -> cantitate .'</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Cantitate ramasa </strong></td>
    <td>'. $lot -> obj -> cantitate_ramasa .'</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="rowhead">
    <td colspan="3"><div style="height: 400px; overflow:scroll;">';
    if (isset($iesiri -> objects)) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Numar Bon", "Cantitate", "Pret vanzare", "Data", "Utilizator");
        $gv -> tableOptions['ColWidth'] = array("20%", "20%", "20%", "20%", "20%");
        foreach ($iesiri -> objects as $obj) {
            $gv -> dataTable[$i]['data'] = array($obj -> numar_bon, $obj -> cantitate, $obj -> valoare, $obj -> data, $obj -> nume);
            if ($i%2==0) {
                $gv -> dataTable[$i]['tag'] = array("class"=>"roweven");
            } else {
                $gv -> dataTable[$i]['tag'] = array("class"=>"rowodd");
            }
            $i++;
        }
        $html -> append($innerHTML, $gv -> getTable());
    }
    $html -> append($innerHTML, '</div>
	</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="btnVanzari" type="button" class="btnTouch" id="btnVanzari" value="Info lot" onClick="xajax_infoLot('. $intrare_continut_id .')">
    </div></td>
    <td><div align="center">
    </div></td>
    <td><div align="center">
      <input name="btnInchide" type="button" class="btnTouch" id="btnInchide" value="INCHIDE" onClick="xajax_btnRenuntaDialog();">
    </div></td>
  </tr>
</table>
	');
    $objResponse = afisareDialog($innerHTML, "600px", "600px", "200px", "30px", null);
    return $objResponse;
}

function cautaProdus($filtre = "", $action = "")
{
    global $mysql;
    global $html;
    
    $produse = new Produse($mysql);
    if ($filtre) {
        $produse -> findBy('cod_bare', $filtre);
        if ($produse -> obj) {
            $objResponse = selectProdus($produse -> obj -> produs_id, $action);
            return $objResponse;
        }
        $f[] = "where denumire like '%$filtre%'";
    }
    $f[] = "order by denumire asc";
    $nr_r = $produse -> find($f);
    $out = '';
    $windowName = "cautaProdus".time();
    if ($nr_r) {
        $select = '<select name="cauta_produs_id" id="cauta_produs_id"  size="20" multiple style="width:300px;" onChange="fn_loadProdus(this.options[this.selectedIndex].text, this.options[this.selectedIndex].title)"  accesskey="b" onKeyUp="if(event.keyCode == 13) {xajax_selectProdus(this.options[this.selectedIndex].value, \''. $action .'\'); xajax_close_window(\''. $windowName .'\')}" onDblClick="xajax_selectProdus(this.options[this.selectedIndex].value,\''. $action .'\'); xajax_close_window(\''. $windowName .'\')">';
        $html -> append($out, $select);
        foreach ($produse -> objects as $obj) {
            if ($obj -> la_vanzare == 'NU') {
                $class = "rowred";
            } else {
                $class = "";
            }
                
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

function selectProdus($produs_id, $action = "")
{
    global $mysql;
    global $html;
    $produs = new Produse($mysql, $produs_id);
    $objResponse = new xajaxResponse();
    $objResponse -> assign($action."produs_id", "value", $produs -> obj -> produs_id);
    $objResponse -> assign($action."denumire", "value", $produs -> obj -> denumire);
    return $objResponse;
}
$xajax->processRequest();
