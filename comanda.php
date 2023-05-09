<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>COMANDA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/comanda.css">
<link type="text/css" rel="stylesheet" href="css/common.css">
<script type="text/javascript" src="js/ui/jquery-1.2.6.js"></script>        
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>
<?php
require_once("comanda.common.php");
$xajax->printJavascript('thirdparty/xajax/');
?>

<?php
if($cfgGui['loading'])
	{
	$load = new Loading;
	echo $load -> javaScript(); 
	}
$tn = new TastaturaNumerica;
echo $tn -> printJavaScript();
$kb = new KeyBoard;
echo $kb -> printJavaScript();
$tabView = new TabView;
$tabView -> root = "";
echo $tabView -> printCss();
echo $tabView -> printJavaScript();
$ds = new divScroll;
echo $ds -> printJavaScript();
?>
<script language="javascript" type="text/javascript">
var str = [];
count = 0;
tastatura = 1;
t = '';
function doKey($key, evt) {

if(tastatura == 1)
{
if($key == 27) {
count--;
document.getElementById('txtsearch').value = document.getElementById('txtsearch').value.substr(0, document.getElementById('txtsearch').value.length - 1);
}

  if($key != 13)
  {
  	if(($key >=48 && $key <= 57) || String.fromCharCode($key)==".") {str[count] = $key;
	document.getElementById('searchtype').selectedIndex = 0;
  	count++;  
  	t = '';
 	 i = 0;
	  for(i=0;i<count;i++)
  		{
		if(str[i] != 13) t = t + String.fromCharCode(str[i]);
		else if (str[i+1] == 13) {document.getElementById('txtsearch').value = t; count = 0;   	 
		if(t != '') xajax_btnCauta(xajax.getFormValues('frmSearch'), document.getElementById('comanda_id').value, document.getElementById('cantitate').value);
		}
			 else {document.getElementById('txtsearch').value = t;count = 0;
			 if(t != '')  xajax_btnCauta(xajax.getFormValues('frmSearch'), document.getElementById('comanda_id').value, document.getElementById('cantitate').value);
			} 
		}
 	document.getElementById('txtsearch').value = t;
 	} 
 	else if(String.fromCharCode($key)=="*") {
			document.getElementById('cantitate').value = t;
			document.getElementById('txtsearch').value = '';
			t = '';
			count = 0;
		} 
	else if($key >= 65 && $key <= 90 || $key >= 97 && $key <= 122 || $key == 32)
 		{
		
		
			document.getElementById('searchtype').selectedIndex = 2;
 			document.getElementById('txtsearch').value = document.getElementById('txtsearch').value + String.fromCharCode($key);		

		}
 }
 else
 {
 xajax_btnCauta(xajax.getFormValues('frmSearch'), document.getElementById('comanda_id').value, document.getElementById('cantitate').value);
 count = 0;
 } 	
} 
}


if (document.all){  
document.onkeydown = function (){  
var key_f5 = 116; // 116 = F5  

if (key_f5==event.keyCode){  
event.keyCode = 27;  

return false;  
}  
}  
}  

$(document).keydown(function (event) {
	if(event.keyCode == 8) {		
	if(count > 1) count--;
	else count = 0;		
	document.getElementById('txtsearch').value = document.getElementById('txtsearch').value.substr(0, document.getElementById('txtsearch').value.length - 1);
	return false;
	} 
}
);

$(document).keypress(function (event) {

	
	evt = event;var charCode = (evt.which) ? evt.which : event.keyCode;doKey(charCode, event);
}
)
</script>
</head>
<?php
$comanda_id = $_GET['comanda_id'];
if(isset($comanda_id)) echo '<body id="body" onLoad="xajax_onLoad('. $comanda_id .');" onKeyPress="evt = event;var charCode = (evt.which) ? evt.which : event.keyCode;doKey(charCode)">';
else echo '<body id="body" onLoad="xajax_onLoad();" onContextMenu="return false;">';
//onKeyPress="var key=event.keyCode || event.which; if (key==13){this.blur();xajax_btnCauta(xajax.getFormValues('frmSearch'), document.getElementById('comanda_id').value, document.getElementById('cantitate').value);this.focus()};"
?>
<div id="main" >
<div id="layout">
<div id="head"> 
<?php
echo $ds -> printHtml('categorii', 1000, 150);
echo $ds -> printHtml('produse', 1000, 100);
?>
</div>
<div id="showhide">
  <div align="center"><a href="#" onClick="$('#head').hide('fast'); $('#showhide').addClass('rowhead'); $('#continutcomanda').css({height:'500px'});return false;"><img src="files/img/go-top.png" border="0"></a> / <a href="#" onClick="$('#continutcomanda').css({height:'250px'});$('#head').show('fast'); $('#showhide').removeClass('rowhead'); return false;"><img src="files/img/go-bottom.png" border="0"></a></div>
</div>
<div id="varcomanda">
<input name="comanda_id" id="comanda_id" type="hidden" value="">
</div>
<div id="cautare">
<form action="" method="get" name="frmSearch" id="frmSearch" onSubmit="return false;">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="44%"><div align="center">
	    <input name="btnCalc" type="button" value=" " class="tn_btnCalc" onClick="xajax_tnPopup('cantitate', 'txtsearch');"> &nbsp;<input name="cantitate" type="text" id="cantitate"  size="10" onFocus="this.blur();" readonly="" >
	    x
          <input name="txtsearch" type="text" size="70" id="txtsearch"  accesskey="b" onFocus="this.blur(); tastatura = 1;">
          <input name="Button" type="button" class="kb_buttonTastatura" value=" " onClick="xajax_kbPopup('txtsearch');">
          <select name="searchtype" id="searchtype">
            <option value="codbare" selected>Cod bare</option>
            <option value="codintern">Cod intern</option>
            <option value="denumire">Denumire</option>
          </select>
          <input name="btnCauta" type="button" id="btnCauta" value="Cauta" onFocus="this.blur()" onClick="xajax_btnCauta(xajax.getFormValues('frmSearch'), document.getElementById('comanda_id').value, document.getElementById('cantitate').value);">
      </div></td>
      </tr>
  </table>
 </form> 
</div>
<form action="" method="get" name="frmContinutComanda" id="frmContinutComanda" onSubmit="return false;">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="270"><?php echo $tn -> html('cantitate', 'cantitate') ?></td>
	<td><div id="continutcomanda">
		</div>
	</td>
  </tr>
</table>


</form>
<div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="rowhead">
      <td>Nr. Bon.</td>
      <td>Total</td>
      <td>Casier</td>
      <td>Casa</td>
      <td>CUI</td>
    </tr>
    <tr>
      <td width="20%"><div id="nrcomanda" align="center">
      </div></td>
      <td width="20%"><div id="comandatotal" align="center"></div></td>
      <td width="20%"><div id="utilizator" style="text-align:center; font-weight:bold; "></div></td>
      <td width="20%"><div id="nume_casa"></div></td>
      <td width="20%"><input type="text" id="cui_value" /></td>
    </tr>
  </table>
</div>
<div id="moduriplata"></div>
<div id="footer">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="60%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div align="center">
            <input name="btnFunctii" type="button" id="btnFunctii" value="Functii"  onClick="xajax_btnFunctii();this.blur();">
          </div></td>
          <td><div align="center">
            <input name="btnIesireComanda" type="button" id="btnIesireComanda" value="IESIRE" onClick="window.location.href = 'login.php'" accesskey="s">
          </div></td>
          <td><div align="center">
            <input name="btnPlata" type="button" id="btnPlata" value="Plata" onClick="xajax_btnPlata(document.getElementById('comanda_id').value,xajax.getFormValues('frmContinutComanda'));this.blur();">
          </div></td>
          <td><div align="center">
            <input name="btnCui" type="button" id="btnCui" value="CUI" onClick="xajax_btnCui();this.blur();">
          </div></td>
          </tr>
      </table></td>
    </tr>
  </table>
</div>
<div id="infocomanda" style="margin-top:5px; ">
<?php
if($cfgMarcaj['Facturare'])
{
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="rowhead">
      <td>Facturare<input name="factura_client_id" type="hidden" value="0" id="factura_client_id"></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td width="25%">
      <div id="div_client_factura"></div></td>
      <td width="25%"><input name="btnClientFactura" type="button" class="btnTouch" id="btnClientFactura" onClick="xajax_btnCatalogClienti('xajax_selectClientFactura(<%client_id%>)');tastatura = 0;" value="CLIENT"></td>
      <td width="25%"></td>
      <td width="25%"></td>
    </tr>
  </table>
<?php
}
?> 
</div>

<div id="overlay">
</div>
<div id="obiecte" class="flora">
</div>
</div>
</div>
<?php 
if($cfgGui['loading'])
	{
	$load = new Loading;
	echo $load -> div(); 
	}
?>
</body>
</html>
