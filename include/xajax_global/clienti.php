<?php
function catalogListaClienti($filtre = array(), $click = "")
{
    global $mysql;
            $cls = new Clienti($mysql);
    if (!$filtre) {
        $nr_r = $cls -> find(array("ORDER BY", "denumire", "ASC"));
    } else {
        $nr_r = $cls -> find(array("WHERE", $filtre['dupa'], "like", "'%". $filtre['txtSearch'] ."%'", "ORDER BY", "denumire", "ASC"));
    }
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Denumire", "REG COM");
        $gv -> tableOptions['ColWidth'] = array("50%", "50%");
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $cls -> objects[$i];
            $gv -> dataTable[$i]['data'] = array($obj -> denumire, $obj -> reg_com);
            if ($i%2==0) {
                $class = "roweven";
            } else {
                $class = "rowodd";
            }
            $ck = $cls -> stringReplace($click, $obj);
            $gv -> dataTable[$i]['tag'] = array("class"=>$class,
            "onMouseOver"=>"$(this).addClass('rowhover')",
            "onMouseOut"=>"$(this).removeClass('rowhover')",
            "onClick"=>"$ck",
            );
        }
        return $gv -> getTable();
    } else {
        return false;
    }
}

function listaClienti($filtre, $click)
{
    $r = catalogListaClienti($filtre, $click);
    $objResponse = new xajaxResponse();
    $objResponse -> assign("catalogLista", "innerHTML", $r);
    return $objResponse;
}

function btnCatalogClienti($click = "")
{
    global $mysql;
    global $html;
    $innerHTML = '';
    $ds = new divScroll;
    $html -> append($innerHTML, '<form action="" method="post" name="frmCauta" id="frmCauta" onSubmit="return false;">
      <table width="100%"  border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="17%"><strong>Caut</strong></td>
          <td width="83%"><input name="txtSearch" type="text" id="txtSearch" size="40" onKeyPress="if(event.keyCode == 13) xajax_listaClienti(xajax.getFormValues(\'frmCauta\'), \''.$click.'\')">
		  <input name="btnAdauga" type="button" id="btnAdauga" value="CLIENT NOU"  onClick="xajax_frmGlobalAdaugaClient(\''.$click.'\');tastatura = 0;">
		  </td>
        </tr>
        <tr>
          <td><strong>Dupa</strong></td>
          <td><select name="dupa" id="dupa" onKeyPress="if(event.keyCode == 13) xajax_listaClienti(xajax.getFormValues(\'frmCauta\'), \''.$click.'\')">
            <option value="denumire" selected>Denumire</option>
            <option value="reg_com">Reg Com</option>
            <option value="cif">Cod fiscal</option>
            <option value="judetul">Judet</option>
            <option value="sediul">Sediul</option>
          </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="btnCauta" type="button" class="btn_search" id="btnCauta" value=" " onClick="xajax_listaClienti(xajax.getFormValues(\'frmCauta\'), \''.$click.'\')"></td>
        </tr>
      </table>
    </form>');
    $html -> append($innerHTML, $ds -> printHtml('catalogLista', 900, 580));
    $objResponse = afisareDialog($innerHTML, "900px", "750px", "60px", "10px", "RENUNTA");
    return $objResponse;
}

function frmGlobalAdaugaClient($click = "")
{
    global $mysql;
    global $html;
    $client = new Clienti($mysql);
    $innerHTML = '';
    $html -> append($innerHTML, '<form action="" method="post" name="frmAdaugaClient" id="frmAdaugaClient">');
    $html -> append($innerHTML, $client -> frmClient());
    $html -> append($innerHTML, '</form>');
    $html -> append($innerHTML, '<div align="center"><input type="button" value="SALVEAZA" name="btnSalveazaClient" id="btnSalveazaClient" class="btnTouch" onClick="xajax_globalSalveazaClient(xajax.getFormValues(\'frmAdaugaClient\'), \''. $click .'\')"></div>');
    $objResponse = afisareDialog($innerHTML, "900px", "750px", "60px", "10px", "RENUNTA");
    return $objResponse;
}

function globalSalveazaClient($frmValues, $click = "")
{
    global $mysql;
    global $mysql;
    $client = new Clienti($mysql);
    $client -> salveazaClient($frmValues);
    $objResponse = btnRenuntaDialog();
    $click = $client -> stringReplace($click, $client -> obj);
    if ($click) {
        $objResponse -> script("$click");
    }
    return $objResponse;
}

$xajax -> registerFunction("frmGlobalAdaugaClient");
$xajax -> registerFunction("globalSalveazaClient");
$xajax -> registerFunction("btnCatalogClienti");
$xajax -> registerFunction("listaClienti");
