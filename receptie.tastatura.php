<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>INTRARI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css" >
<link type="text/css" rel="stylesheet" href="css/config.produse.css">
<script type="text/javascript" src="js/ui/jquery-1.2.6.js"></script>
<style type="text/css">
<!--
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
#loading {

    padding: 20px;
    border: 0px solid green;
    display: none; /* hidden */
    position: absolute;    
    left: 50%;
    margin-left: -100px;
    top: 25%;
    width: 200px;
    /*height: 100px;*/
    /*margin-top: -50;*/
    font-weight: bold;
    font-size: large;
    }

-->
</style>
<?php
require_once("receptie.tastatura.common.php");

require("config/config.php");
$xajax->printJavascript('thirdparty/xajax/');
?>

<?php
if ($cfgGui['loading']) {
    $load = new Loading();
    echo $load -> javaScript();
}
$tn = new TastaturaNumerica();
echo $tn -> printJavaScript();
$kb = new KeyBoard();
echo $kb -> printJavaScript();
$tabView = new TabView();
$tabView -> root = "";
echo $tabView -> printCss();
echo $tabView -> printJavaScript();
?>
</head>
<?php
/*         DB              */
$mysql = new MySQL();
?>
<Script Language=JavaScript>

function getRealLeft(el){
xPos = document.getElementById(el).offsetLeft;
tempEl = document.getElementById(el).offsetParent;
while (tempEl != null) {
xPos += tempEl.offsetLeft;
tempEl = tempEl.offsetParent;
}
return xPos;
}

function getRealTop(el){
yPos = document.getElementById(el).offsetTop;
tempEl = document.getElementById(el).offsetParent;
while (tempEl != null) {
yPos += tempEl.offsetTop;
tempEl = tempEl.offsetParent;
}
return yPos;
}

function getRealLeftSec(el){
xPos = el.offsetLeft;
tempEl = el.offsetParent;
while (tempEl != null) {
xPos += tempEl.offsetLeft;
tempEl = tempEl.offsetParent;
}
return xPos;
}

function getRealTopSec(el){
yPos = el.offsetTop;
tempEl = el.offsetParent;
while (tempEl != null) {
yPos += tempEl.offsetTop;
tempEl = tempEl.offsetParent;
}
return yPos;
}

function dispTruePos(isID){
trueX = getRealLeft(isID);
trueY = getRealTop(isID);
alert('True Xpos is: '+trueX+'nTrue Ypos is: '+trueY)
}

</Script>
<script type="text/javascript">
function fn_loadProdus(denumire, pret_vanzare)
    {
    xajax.$('div_denumire').innerHTML = denumire;
    xajax.$('div_pret').innerHTML = pret_vanzare;
    }
function fn_focus(strId)
    {
    document.getElementById(strId).focus();
    }   
</script>
<script type="text/javascript">
var OnKeyRequestBuffer = 
    {
        bufferText: false,
        bufferTime: 500,
        
        modified : function(strId)
        {
                setTimeout('OnKeyRequestBuffer.compareBuffer("'+strId+'","'+xajax.$(strId).value+'");', this.bufferTime);
        },
        
        compareBuffer : function(strId, strText)
        {
            if (strText == xajax.$(strId).value && strText != this.bufferText)
            {
                this.bufferText = strText;
                OnKeyRequestBuffer.makeRequest(strId);
            }
        },
        
        makeRequest : function(strId)
        {
            xajax_searchProduse(document.getElementById('txtDenumire').value);
        }
    }
</script>
<?php
$nir_id = $_GET['nir_id'];
if (!empty($nir_id)) {
    echo '<body id="body" onLoad="xajax_onLoad(' . $nir_id . ');$(\'#data_factura\').datepicker()">';
} else {
    echo '<body id="body" onLoad="$(\'#data_factura\').datepicker()">';
}
?>
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">
<form action="frmNir" id="frmNir" method="post">
  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td>&nbsp;</td>
      <td><strong>NIR NR 
        <input name="nir_id" type="hidden" id="nir_id">
      </strong></td>
      <td><div id="div_numar_nir"></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="13%"><strong>Furnizor</strong></td>
      <td width="44%">        <div id="div_furnizor_id">
        <select name="furnizor_id"  id="select" style="width:350px" onKeyUp="if(event.keyCode==13) document.getElementById('numar_factura').focus();">
          <?php
            $mysql = new MySQL();
            $furnizori = new Furnizori($mysql);
            $furnizori -> find(array("ORDER BY", "nume", "ASC"));
            if (isset($furnizori -> objects)) {
                foreach ($furnizori -> objects as $obj) {
                    echo '<option value="' . $obj -> furnizor_id . '">' . $obj -> nume . '</option>';
                }
            }
            ?>
              </select>
      </div></td>
      <td width="7%"><strong>Data</strong></td>
      <td width="36%"><input name="data_factura" type="text" id="data_factura" onKeyUp="if(event.keyCode==13) {fn_focus('total_fara_tva')}"></td>
    </tr>
    <tr>
      <td><strong>Factura</strong></td>
      <td><input name="numar_factura" type="text" id="numar_factura" onKeyUp="if(event.keyCode==13) document.getElementById('data_factura').focus();" ></td>
      <td><strong>Tip nir</strong></td>
      <td><label></label>
        <div id="div_tip_nir">
          <select name="tip_nir" id="tip_nir">
            <option value="marfa" selected>Marfa</option>
            <option value="mp">Materii prime</option>
          </select>
        </div></td>
    </tr>
    <tr>
      <td><strong>Total</strong></td>
      <td><input name="total_fara_tva" type="text" id="total_fara_tva" onKeyUp="if(event.keyCode==13) {fn_focus('total_tva')}">
        *fara tva </td>
      <td><strong>Total tva </strong></td>
      <td><input name="total_tva" type="text" id="total_tva" onKeyUp="if(event.keyCode==13) {fn_focus('btnSalveazaNir')}"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="btnSalveazaNir" type="button" id="btnSalveazaNir" value="SALVEAZA" onClick="xajax_salveazaAntet(xajax.getFormValues('frmNir'));"></td>
      <td>&nbsp;</td>
      <td><input name="btnComponente" type="button" id="btnComponente" value="COMPONENTE" onClick="this.blur();xajax_componente();"></td>
    </tr>
  </table>
</form>
<hr>
<fieldset><legend>Componente</legend>
<div style="height:500px; width:980px; overflow:auto; ">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:10px; "> 
    <tr>
      <td width="129" nowrap><input type="text" name="data['.$time.'][denumire]" style="border:1px solid #000;border-right: 0px solid #000; width:129px; font-size:10px; text-align:center; " readonly="" value="DENUMIRE"></td>
      <td width="20" nowrap><input type="text" name="data['.$time.'][um]" style="border:1px solid #000;border-right: 0px solid #000; width:20px; font-size:10px;text-align:center; " readonly="" value="UM"></td>
      <td width="36" nowrap><input type="text" name="data['.$time.'][cant]" style="border:1px solid #000;border-right: 0px solid #000; width:36px; font-size:10px;text-align:center; " value="CANT"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][pret_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center; " value="PRET ACH"></td>
      <td width="68" nowrap><input type="text" name="data['.$time.'][val_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:68px; font-size:10px;text-align:center; " readonly="" value="VAL ACH"></td>
      <td width="71" nowrap><input type="text" name="data['.$time.'][tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:71px; font-size:10px;text-align:center;" value="TVA UNIT"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_ach]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="TOTAL TVA"></td>
      <td width="86" nowrap><input type="text" name="data['.$time.'][adaos_unit]" style="border:1px solid #000;border-right: 0px solid #000; width:86px; font-size:10px;text-align:center; " readonly="" value="ADAOS UNIT"></td>
      <td width="100" nowrap><input type="text" name="data['.$time.'][total_adaos]" style="border:1px solid #000;border-right: 0px solid #000; width:100px; font-size:10px;text-align:center; " readonly="" value="TOTAL ADAOS"></td>
      <td width="61" nowrap><input type="text" name="data['.$time.'][tva_vanare]" style="border:1px solid #000;border-right: 0px solid #000; width:61px; font-size:10px;text-align:center; " readonly="" value="TVA VANZARE"></td>
      <td width="82" nowrap><input type="text" name="data['.$time.'][total_tva_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:82px; font-size:10px;text-align:center; " readonly="" value="TOTAL TVA"></td>
      <td width="104" nowrap><input type="text" name="data['.$time.'][pret_vanzare]" style="border:1px solid #000;border-right: 0px solid #000; width:104px; font-size:10px;text-align:center; " readonly="" value="PRET VNZ"></td>
      <td width="90" nowrap><input type="text" name="data['.$time.'][val_total]" style="border:1px solid #000;border-right: 0px solid #000; width:90px; font-size:10px;text-align:center; " readonly="" value="VAL TOTAL"></td>
    </tr>
  </table>  
<form action="" method="post" name="frmComponente" id="frmComponente" style="margin:0px 0px 0px 0px;">
<div id="intrare_componente">
</div>
</form>
</div>
</fieldset>
<table width="800"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><div align="center">
        <input name="Button" type="button" class="btnTouch" value="Inchide NIR" onClick="xajax_inchideNir(xajax.getFormValues('frmNir'));" accesskey="i">
    </div></td>
    <td><div align="center">
        <input name="Button" type="button" class="btnTouch" value="Verificare" onClick="xajax_verificare(xajax.getFormValues('frmNir'), xajax.getFormValues('frmComponente'));" accesskey="v">
    </div></td>
    <td><div align="center">
        <input name="Button" type="button" class="btnTouch" value="Anuleaza" onClick="c = confirm('Doriti sa stergeti aceasta factura?'); if(c) xajax_stergeIesire(document.getElementById('nir_id').value);" accesskey="a">
    </div></td>
    <td><input name="Button2" type="button" class="btnTouch" value="Iesire" onClick="c = confirm('Apasati Inchide Nir pentru a actualiza stocul! Daca doriti sa iesiti fara a actualiza stocul apasati Ok!'); if(c) window.location.href='evidenta.niruri.php'"  accesskey="e"></td>
  </tr>
</table>
</div>
<div id="overlay">
</div>
<div id="obiecte" class="flora" style="z-index:500 ">
</div>
</div>
<?php
if ($cfgGui['loading']) {
    $load = new Loading();
    echo $load -> div();
}
?>
<div id="windows">
</div>
</body>
</html>
