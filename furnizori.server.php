<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("furnizori.common.php");

$mysql = new MySQL();
$html = new Html;
function lista($litera = null, $filtre = array())
{
    global $mysql;
    $objResponse = new xajaxResponse();
            $cls = new Furnizori($mysql);
    if (!$filtre) {
        if (!$litera) {
            $nr_r = $cls -> find(array("ORDER BY", "nume", "ASC"));
        } else {
            $nr_r = $cls -> find(array("WHERE", "nume like '$litera%'", "ORDER BY", "nume", "ASC"));
        }
    } else {
        $nr_r = $cls -> find(array("WHERE", $filtre['dupa'], "like", "'%". $filtre['txtSearch'] ."%'", "ORDER BY", "nume", "ASC"));
    }
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Denumire", "Cod Fiscal", "Sold Furnizor");
        $gv -> tableOptions['ColWidth'] = array("50%", "25%", "25%");
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $cls -> objects[$i];
            $cls -> obj = $obj;
            $gv -> dataTable[$i]['data'] = array($obj -> nume, $obj -> cod_fiscal, $cls -> soldFurnizor());
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"$('#listaObiecte tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_loadForm(". $obj -> furnizor_id .")",
            );
        }
        $objResponse -> assign("listaObiecte", "innerHTML", $gv -> getTable());
    } else {
        $objResponse -> assign("listaObiecte", "innerHTML", "NU SUNT INREGISTRARI");
    }
    $objResponse -> assign("divForm", "innerHTML", "");
    return $objResponse;
}

function loadForm($id)
{
    global $mysql;
    $objResponse = new xajaxResponse();
    $obj = new Furnizori($mysql, $id);
    $objResponse -> assign("divForm", "innerHTML", '<form action="" method="post" name="frmClient" id="frmClient">'.$obj -> buildForm(true, false).'</form>');
    $objResponse -> assign("btnSave", "disabled", false);
    $objResponse -> assign("btnAdauga", "disabled", false);
    return $objResponse;
}
function btnSave($frmValues)
{
    global $mysql;
    $obj = new Furnizori($mysql);
    $obj -> saveForm($frmValues);
    $objResponse = lista(substr($frmValues['nume'], 0, 1));
    $objResponse -> assign("divForm", "innerHTML", "");
    return $objResponse;
}

function facturi($furnizor_id = null)
{
    global $mysql;
    global $html;
    $out = '<form action="" method="post" name="frmCautareFacturi" id="frmCautareFacturi">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><strong>Furnizor</strong></td>
    </tr>
    <tr>';
    if ($furnizor_id) {
        $furnizor = new Furnizori($mysql, $furnizor_id);
        $html -> append($out, '<td colspan="2">');
        $html -> append($out, $furnizor -> input("furnizor_id"));
        $html -> append($out, $furnizor -> obj -> nume);
        $html -> append($out, '</td>
    	</tr>');
    } else {
        $html -> append($out, '<td colspan="2">');
        $html -> append($out, '<select name="furnizor_id"  id="furnizor_id" style="width:350px" tabindex="1" >');
        $furnizori = new Furnizori($mysql);
        $furnizori -> find(array("ORDER BY", "nume", "ASC"));
        if (isset($furnizori -> objects)) {
            foreach ($furnizori -> objects as $obj) {
                $html -> append($out, '<option value="'. $obj -> furnizor_id .'">'.$obj -> nume.'</option>');
            }
        }
              $html -> append($out, '</select>');
        $html -> append($out, '</td>
    	</tr>');
    }
    $html -> append($out, '<tr>
      <td width="50%"><strong>Perioada</strong></td>
      <td width="55%">&nbsp;</td>
    </tr>
    <tr>
      <td><div align="center">
        <input name="dataStart" type="text" id="dataStart" value="'. date("Y-m-d") .'">
      </div></td>
      <td><div align="center">
        <input name="dataStop" type="text" id="dataStop" value="'. date("Y-m-d") .'">
      </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
    </tr>    <tr>
      <td>&nbsp;</td>
      <td><div align="right">
        <input name="btnAfiseazaFacturi" type="button" id="btnAfiseazaFacturi" value="Afiseaza Facturi" onClick="xajax_afiseazaFacturi(xajax.getFormValues(\'frmCautareFacturi\'))">
      </div></td>
    </tr>
  </table>
</form>
');
    $objResponse = new xajaxResponse();
    $objResponse -> assign("divForm", "innerHTML", $out);
    $objResponse -> assign("btnSave", "disabled", true);
    $objResponse -> assign("btnAdauga", "disabled", true);
    $objResponse -> script("$('#dataStart').datepicker()");
    $objResponse -> script("$('#dataStop').datepicker()");
    return $objResponse;
}

function afiseazaFacturi($frmCautareFacturi)
{
    global $mysql;
    $nir = new Niruri($mysql);
    $furnizor = new Furnizori($mysql, $frmCautareFacturi['furnizor_id']);
    $nr_r = $nir -> find(array("WHERE",
     "furnizor_id" => $mysql -> equal($frmCautareFacturi['furnizor_id']),
     "and",
     "data_factura" => $mysql -> between($frmCautareFacturi['dataStart'], $frmCautareFacturi['dataStop'])
     ));
    $objResponse = new xajaxResponse();
    $totalFacturiEmise = $furnizor -> totalFacturiEmise();
    $totalPlatiAsociate = $furnizor -> totalPlatiAsociate();
    $soldFurnizor = number_format($totalFacturiEmise - $totalPlatiAsociate, 2, '.', '');
    $out = '<table width="100%" borde="0" cellspacing="0" cellpadding="0">
	<tr>
	<td colspan="2"><strong>'. $furnizor -> obj -> nume .'</strong> '. $furnizor -> input("furnizor_id") .'</td>
	</tr>
	<tr>
	<td><strong>Total facturi emise</strong></td>
	<td>'. $totalFacturiEmise .'</td>
	</tr>
	<tr>
	<td><strong>Total plati asociate</strong></td>
	<td>'. $totalPlatiAsociate .'</td>
	</tr>
	<tr>
	<td><strong>Sold Furnizor</strong></td>
	<td>'. $soldFurnizor .'</td>
	</tr>
	</table>
	';
    if ($nr_r) {
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Numar Factura", "Data", "Total", "Rest Plata");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $nir -> objects[$i];
            $nir -> obj = $obj;
            $gv -> dataTable[$i]['data'] = array($obj -> numar_factura, $obj -> data_factura, $obj -> total_factura, $nir -> facturaRestPlata());
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"$('#divForm tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_afiseazaFactura(". $obj -> nir_id .");",
            );
        }
        $out .= $gv -> getTable();
    } else {
        $out .= "NU SUNT FACTURI IN PERIOADA SELECTATA";
    }
    $objResponse -> assign("divForm", "innerHTML", $out);
    return $objResponse;
}

function afiseazaFactura($nir_id)
{
    global $mysql;
    global $html;
    $nir = new Niruri($mysql, $nir_id);
    $furnizor = new Furnizori($mysql, $nir -> obj -> furnizor_id);
    $out = '
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td ><strong>Furnizor</strong></td>
    <td >'. $furnizor -> obj -> nume .'</td>
  </tr>
  <tr>
    <td><strong>Factura</strong></td>
    <td>'. $nir -> obj -> numar_factura .'</td>
  </tr>
  <tr>
    <td><strong>Data factura </strong></td>
    <td>'. date("d/m/Y", strtotime($nir -> obj -> data_factura)) .'</td>
  </tr>
  <tr>
    <td><strong>Total factura </strong></td>
    <td>'. $nir -> obj -> total_factura .'</td>
  </tr>
  <tr>
    <td><strong>Total tva </strong></td>
    <td>'. $nir -> obj -> total_tva .'</td>
  </tr>
    <tr>
    <td><strong>Tota fara tva </strong></td>
    <td>'. $nir -> obj -> total_fara_tva .'</td>
  </tr>
    <tr>
    <td><strong>Plati</strong></td>
    <td>&nbsp;</td>
  </tr>
    <tr>';
    $html -> append($out, '<td colspan=2>');
    $html -> append($out, '<div id="plati" style="width:100%;height:250px; overflow:auto;">'.$nir -> facturaDetalierePlati("alert(<%document_id%>)").'</div>');
    $html -> append($out, '</td></tr>');
    $html -> append($out, '<tr>
    <td><strong>Total achitat</strong></td>
    <td>'. $nir -> facturaTotalAchitat() .'</td>
  </tr>');
    $html -> append($out, '<tr>
    <td><strong>Rest plata</strong></td>
    <td>'. $nir -> facturaRestPlata().'</td>
  	</tr>');
    if ($nir -> facturaRestPlata() != 0) {
        $html -> append($out, '<tr>
    <td><strong></strong></td>
    <td><input type="button" name="btnAdaugaPlata" value="Adauga Plata" class="btnTouch" onClick="xajax_adaugaPlataFacturaFurnizor('. $nir -> obj -> nir_id .')"></td>
  	</tr>');
    }
    $html -> append($out, '</table>');
    $objResponse = afisareDialog($out, "800px", "700px", "110px", "50px");
    return $objResponse;
}

function adaugaPlataFacturaFurnizor($nir_id)
{
    global $mysql;
    global $html;
    $nir = new Niruri($mysql, $nir_id);
    $furnizor = new Furnizori($mysql, $nir -> obj -> furnizor_id);
    $out = '
<form action="" method="post" name="frmPlataFacturaFurnizor" id="frmPlataFacturaFurnizor">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td ><strong>Furnizor</strong></td>
    <td >'. $furnizor -> obj -> nume .'<input type="hidden" value="'. $nir -> obj -> nir_id .'" name="document_id" id="document_id"></td>
  </tr>
  <tr>
    <td><strong>Factura</strong></td>
    <td>'. $nir -> obj -> numar_factura .'</td>
  </tr>
  <tr>
    <td><strong>Data factura </strong></td>
    <td>'. date("d/m/Y", strtotime($nir -> obj -> data_factura)) .'</td>
  </tr>';
    $html -> append($out, '<tr>
    <td><strong>Tip Document</strong></td>
    <td><select name="tip_document" id="tip_document">
  <option value="Bilet la ordin">Bilet la ordin</option>
  <option value="CEC">CEC</option>
  <option value="Chitanta" selected>Chitanta</option>
  <option value="Dispozitie plata">Dispozitie plata</option>
  <option value="Factura">Factura</option>
  <option value="Monetar">Monetar</option>
  <option value="Ordin de plata">Ordin de plata</option>
</select>
</td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Numar Document</strong></td>
    <td><input type="text" value="" name="numar_document" id="numar_document"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Data Document</strong></td>
    <td><input type="text" value="" name="data_document" id="data_document" onClick=""></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Suma</strong></td>
    <td><input type="text" value="'. $nir -> facturaRestPlata() .'" name="suma" id="suma"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Explicatii</strong></td>
    <td><input type="text" value="" name="explicatie_document" id="explicatie_document"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Salveaza</strong></td>
    <td><input type="button" value="" name="btnSalveazaPlata" id="btnSalveazaPlata" value="SALVEAZA PLATA" onClick="xajax_salveazaPlataFacturaFurnizor(xajax.getFormValues(\'frmPlataFacturaFurnizor\'));"></td>
 	 </tr>');
    $html -> append($out, '</table></form>');
    $objResponse = afisareDialog($out, "800px", "700px", "110px", "50px", "RENUNTA");
    $objResponse -> script("$('#data_document').datepicker()");
    return $objResponse;
}
function salveazaPlataFacturaFurnizor($frmValues)
{
    global $mysql;
    $nir = new Niruri($mysql, $frmValues['document_id']);
    $registru = new RegistruCasa($mysql);
    $frmValues['tip'] = "plata";
    $frmValues['tip_inregistrare'] = "factura_furnizor";
    $frmValues['data_adaugare'] = date("Y-m-d H:i:s");
    $registru -> tableToForm();
    $registru -> saveForm($frmValues);
    if ($nir -> facturaRestPlata() == 0) {
        $nir -> obj -> achitat = 'DA';
        $nir -> save();
    }
    $objResponse = afiseazaFactura($nir -> obj -> nir_id);
    return $objResponse;
}
    
function sterge($inregistrare_id)
{
    global $mysql;
    global $html;
    $plati = '';
    
    $registru = new RegistruCasa($mysql, $inregistrare_id);
    $registru -> delete();
    $objResponse = new xajaxResponse();
    $objResponse -> script("xajax_incarcaRegistru(xajax.getFormValues('frmFiltreRegistru'))");
    return $objResponse;
}
$xajax->processRequest();
