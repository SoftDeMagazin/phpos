<?php
session_start();
header("Cache-control: private"); // IE 6 Fix

require_once("registru.casa.common.php");

$mysql = new MySQL();
$html = new Html;

function incarcaRegistru($frmFiltre)
{
    global $mysql;
    global $html;
    $plati = '';
    
    $registru = new RegistruCasa($mysql);
    $nr_r = $registru -> find(array(
    "where",
    "data_document" => $mysql -> between($frmFiltre['dataStart'], $frmFiltre['dataStop']),
    "and",
    "tip" => $mysql -> equal("plata"),
    "order by data_document asc"
    ));
    if ($nr_r) {
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Data", "Document", "Nr. Doc", "Explicatie", "Suma");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $registru -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> data_document, $obj -> tip_document, $obj -> numar_document, $obj -> explicatie_document, $obj -> suma);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onDblClick"=>"r=confirm('Sterg aceasta inregistrare?'); if(r) xajax_sterge(". $obj -> inregistrare_id .");"
            );
        }
        $plati = $gv -> getTable();
    } else {
        $plati = "NU SUNT INREGISTRARI";
    }
        
        $nr_r = $registru -> find(array(
    "where",
    "data_document" => $mysql -> between($frmFiltre['dataStart'], $frmFiltre['dataStop']),
    "and",
    "tip" => $mysql -> equal("incasare"),
    "order by data_document asc"
        ));
    if ($nr_r) {
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Data", "Document", "Nr. Doc", "Explicatie", "Suma");
                    $gv -> tableOptions['ColWidth'] = array();
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $registru -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> data_document, $obj -> tip_document, $obj -> numar_document, $obj -> explicatie_document, $obj -> suma);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onDblClick"=>"r=confirm('Sterg aceasta inregistrare?'); if(r) xajax_sterge(". $obj -> inregistrare_id .");"
            );
        }
        $incasari = $gv -> getTable();
    } else {
        $incasari = "NU SUNT INREGISTRARI";
    }

    
    $objResponse = new xajaxResponse();
    $objResponse -> assign("div_plati", "innerHTML", $plati);
    $objResponse -> assign("div_incasari", "innerHTML", $incasari);
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

function btnAdaugaPlata()
{
    $innerHTML = '
	<table width="90%" align="center"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="button" value="FACTURA FURNIZOR" name="btnFacturaFurnizor" class="btnTouch" onClick="xajax_facturaFurnizor()"></td>
    <td><input type="button" value="PLATI DIVERSE" name="btnPlatiDiverse" class="btnTouch" onClick="xajax_platiDiverse()"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
	';
    $objResponse = afisareDialog($innerHTML, "800px", "700px", "110px", "50px", "RENUNTA");
    return $objResponse;
}

function btnAdaugaIncasare()
{
    $innerHTML = '
	<table width="90%" align="center"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="button" value="FACTURA CLIENT" name="btnFacturaFurnizor" class="btnTouch" onClick=""></td>
    <td><input type="button" value="INCASARI DIVERSE" name="btnPlatiDiverse" class="btnTouch" onClick="xajax_incasariDiverse()"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
	';
    $objResponse = afisareDialog($innerHTML, "800px", "700px", "110px", "50px", "RENUNTA");
    return $objResponse;
}

function facturaFurnizor()
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
        <input name="dataStartFacturi" type="text" id="dataStartFacturi" value="'. date("Y-m-d") .'">
      </div></td>
      <td><div align="center">
        <input name="dataStopFacturi" type="text" id="dataStopFacturi" value="'. date("Y-m-d") .'">
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
<div id="div_facturi" style="width:90%;height:300px;"></div>
');
    $objResponse = afisareDialog($out, "800px", "700px", "110px", "50px", "RENUNTA");
    $objResponse -> script("$('#dataStartFacturi').datepicker()");
    $objResponse -> script("$('#dataStopFacturi').datepicker()");
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
     "data_factura" => $mysql -> between($frmCautareFacturi['dataStartFacturi'], $frmCautareFacturi['dataStopFacturi'])
     ));
    $objResponse = new xajaxResponse();
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
            "onClick"=>"$('#divForm tr').removeClass('rowclick');$(this).addClass('rowclick');xajax_adaugaPlataFacturaFurnizor(". $obj -> nir_id .");",
            );
        }
        $out .= $gv -> getTable();
    } else {
        $out .= "NU SUNT FACTURI IN PERIOADA SELECTATA";
    }
    $objResponse -> assign("div_facturi", "innerHTML", $out);
    return $objResponse;
}

function platiDiverse()
{
    global $html;
    global $mysql;
    $out = '
<form action="" method="post" name="frmPlatiDiverse" id="frmPlatiDiverse">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td ><strong>Adauga Plata</strong></td>
    <td ><input type="hidden" value="0" name="document_id" id="document_id"></td>
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
    <td><input type="text" value="'. date("Y-m-d") .'" name="data_document" id="data_document"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Suma</strong></td>
    <td><input type="text" name="suma" id="suma"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Explicatii</strong></td>
    <td><input type="text" value="" name="explicatie_document" id="explicatie_document"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Salveaza</strong></td>
    <td><input type="button" value="SALVEAZA PLATA" name="btnSalveazaPlata" id="btnSalveazaPlata" value="SALVEAZA PLATA" onClick="xajax_salveazaPlatiDiverse(xajax.getFormValues(\'frmPlatiDiverse\'));xajax_incarcaRegistru(xajax.getFormValues(\'frmFiltreRegistru\'))"></td>
 	 </tr>');
    $html -> append($out, '</table></form>');
    $objResponse = afisareDialog($out, "800px", "700px", "110px", "50px", "RENUNTA");
    $objResponse -> script("$('#data_document').datepicker()");
    return $objResponse;
}
function salveazaPlatiDiverse($frmValues)
{
    global $mysql;
    $registru = new RegistruCasa($mysql);
    $frmValues['tip'] = "plata";
    $frmValues['tip_inregistrare'] = "diverse";
    $frmValues['data_adaugare'] = date("Y-m-d H:i:s");
    $registru -> tableToForm();
    $registru -> saveForm($frmValues);
    $objResponse = btnRenuntaDialog();
    return $objResponse;
}
    
function incasariDiverse()
{
    global $html;
    global $mysql;
    $out = '
<form action="" method="post" name="frmIncasariDiverse" id="frmIncasariDiverse">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td ><strong>Adauga Plata</strong></td>
    <td ><input type="hidden" value="0" name="document_id" id="document_id"></td>
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
    <td><input type="text" value="'. date("Y-m-d") .'" name="data_document" id="data_document"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Suma</strong></td>
    <td><input type="text" name="suma" id="suma"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Explicatii</strong></td>
    <td><input type="text" value="" name="explicatie_document" id="explicatie_document"></td>
 	 </tr>');
    $html -> append($out, '<tr>
    <td><strong>Salveaza</strong></td>
    <td><input type="button" value="SALVEAZA INCASARE" name="btnSalveazaPlata" id="btnSalveazaPlata" value="SALVEAZA PLATA" onClick="xajax_salveazaIncasariDiverse(xajax.getFormValues(\'frmIncasariDiverse\'));xajax_incarcaRegistru(xajax.getFormValues(\'frmFiltreRegistru\'))"></td>
 	 </tr>');
    $html -> append($out, '</table></form>');
    $objResponse = afisareDialog($out, "800px", "700px", "110px", "50px", "RENUNTA");
    $objResponse -> script("$('#data_document').datepicker()");
    return $objResponse;
}
function salveazaIncasariDiverse($frmValues)
{
    global $mysql;
    $registru = new RegistruCasa($mysql);
    $frmValues['tip'] = "incasare";
    $frmValues['tip_inregistrare'] = "diverse";
    $frmValues['data_adaugare'] = date("Y-m-d H:i:s");
    $registru -> tableToForm();
    $registru -> saveForm($frmValues);
    $objResponse = btnRenuntaDialog();
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
    <td><input type="button" value="SALVEAZA PLATA" name="btnSalveazaPlata" id="btnSalveazaPlata" value="SALVEAZA PLATA" onClick="xajax_salveazaPlataFacturaFurnizor(xajax.getFormValues(\'frmPlataFacturaFurnizor\'));"></td>
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
    $objResponse = btnRenuntaDialog();
    $objResponse -> script("xajax_incarcaRegistru(xajax.getFormValues('frmFiltreRegistru'))");
    return $objResponse;
}
    
    
$xajax->processRequest();
