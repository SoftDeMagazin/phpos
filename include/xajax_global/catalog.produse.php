<?php
function btnCatalogProduse()
{
    global $mysql;
    global $html;
    $categorii = new Categorii($mysql);
    $categorii -> find(array("ORDER BY", "denumire_categorie", "ASC"));
    $i=0;
    if (isset($categorii -> objects)) {
        foreach ($categorii -> objects as $objCategorie) {
            $content[$i] = array("name" => $objCategorie -> denumire_categorie, "value" => $objCategorie -> denumire_categorie,
            "class" => "btn_catalog",
            "onClick" => "$('#tabViewContent button').css({border:'2px solid #FAFAFA'});$(this).css({border:'2px solid #000000'});xajax_catalogListaProduse(". $objCategorie -> categorie_id .");"
            );
            $i++;
        }
    }

    
    $options = array("width" => 900, "height" => 30, "scroll" => 800, "content" => $content);
    $tabView = new TabView;
    $ds = new divScroll;
    $innerHTML = $tabView -> printTabView($options, 2);
    $html -> append($innerHTML, '<form action="" method="post" name="frmCatalogSearch" id="frmCatalogSearch">
  <table width=""  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><div align="center">
        <input name="txtCatalogSearch" type="text" id="txtCatalogSearch">
      </div></td>
      <td><div align="center">
        <input name="btnSearch" type="button" id="btnSearch" value=" " class="btn_search" onClick="xajax_catalogListaProduse(0, xajax.getFormValues(\'frmCatalogSearch\'));">
      </div></td>
    </tr>
  </table>
</form>');
    $html -> append($innerHTML, $ds -> printHtml('catalogLista', 900, 580));
    $objResponse = afisareDialog($innerHTML, "900px", "750px", "60px", "10px", "RENUNTA");
    return $objResponse;
}

function catalogListaProduse($categorie_id, $frmFiltre = null)
{
    global $mysql;
    global $html;
    global $clickCatalogProduse;
    if (!empty($frmFiltre)) {
        $search = array("WHERE", "denumire" => $mysql -> like($frmFiltre['txtCatalogSearch']));
    } else {
        $search = array("WHERE", "categorie_id" => "='$categorie_id'", "ORDER BY", "denumire", "ASC");
    }
    $Produse = new ViewStocuriProduse($mysql);
    $nr_r = $Produse -> find($search);
    $objResponse = new xajaxResponse();
    if ($nr_r) {
        $gv = new GridView;
        $gv -> tableOptions['tag'] = array("width" => 850, "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left");
        $gv -> tableOptions['head'] = array("class"=> "rowhead");
        $gv -> columns = array("Cod Bare", "Denumire Produs", "Pret", "Stoc");
        $gv -> tableOptions['ColWidth'] = array("150", "450", "150", "100");
        for ($i=0; $i<$nr_r; $i++) {
            $obj = $Produse -> objects[$i];
            $click = $clickCatalogProduse;
            $html -> replace($click, '<%produs_id%>', $obj -> produs_id);
            $html -> replace($click, '<%denumire%>', $obj -> denumire);

            if (empty($obj -> stoc)) {
                $obj -> stoc = 0;
            }
            $obj -> stoc = number_format($obj -> stoc, 2, '.', '');
            $gv -> dataTable[$i]['data'] = array($obj -> cod_bare, $obj -> denumire, $obj -> pret, $obj -> stoc);
            if ($i%2==0) {
                $gv -> dataTable[$i]['tag'] = array("class"=>"roweven",
            //"onMouseOver"=>"$(this).addClass('rowhover')",
            //"onMouseOut"=>"$(this).removeClass('rowhover')",
                "onClick"=>"$click"
                );
            } else {
                $gv -> dataTable[$i]['tag'] = array("class"=>"rowodd",
            //"onMouseOver"=>"$(this).addClass('rowhover')",
            //"onMouseOut"=>"$(this).removeClass('rowhover')",
                "onClick"=>"$click"
                );
            }
        }
        $objResponse -> assign("catalogLista", "innerHTML", $gv -> getTable());
    } else {
        $objResponse -> assign("catalogLista", "innerHTML", "NICI UN PRODUS IN CAEGORIE");
    }
    return $objResponse;
}
$xajax->registerFunction("btnCatalogProduse");
$xajax->registerFunction("catalogListaProduse");
