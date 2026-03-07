<?php
$document = '
<form action="" method="post" name="frmDocument" id="frmDocument" style="padding:0px 0px 0px 0px;">
<fieldset ><legend>Document intrare</legend>

<table width="100%"  border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="23%"><strong>Numar document </strong></td>
    <td width="77%"><input type="hidden" value="<%IntrareId%>" name="txtIntrareId" id="txtIntrareId"><input name="txtNumarDocument" type="text" id="txtNumarDocument" value="<%NumarDocument%>"></td>
  </tr>
  <tr>
    <td><strong>Data</strong></td>
    <td><input name="txtData" type="text" id="txtData" value="<%Data%>">
      <input name="cal_btnCalendar" type="button" id="cal_btnCalendar" value=" " onClick="xajax_calPopup(\'\',\'\', \'txtData\')"></td>
  </tr>
</table>

</fieldset>
</form>';

$addComponenta ='
<form action="" method="get" name="frmAddComponenta" id="frmAddComponenta">
<fieldset ><legend>Introducere componente</legend>

<table width="100%"  border="0" cellspacing="0" cellpadding="1">
  <tr class="rowhead">
    <td><strong>Cod Bare </strong></td>
    <td><strong>Denumire Produs</strong></td>
    <td><strong>Pret Achizitie </strong></td>
    <td><strong>Cantitate</strong></td>
    <td><strong>Salveaza</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="text" name="txtCodBare" id="txtCodBare" onKeyPress="var key=event.keyCode || event.which; if (key==13){this.blur();xajax_searchCodBare(document.getElementById(\'txtCodBare\').value);};">
      <input name="Button" type="button" class="btn_search" value=" " onClick="xajax_searchCodBare(document.getElementById(\'txtCodBare\').value);"></td>
    <td><input name="txtDenumire" id="txtDenumire" onKeyUp="xajax_searchProduse(document.getElementById(\'txtDenumire\').value)" type="text" size="40" onFocus="d = document.getElementById(\'div\'); d.style.left= getRealLeft(\'txtDenumire\'); d.style.top= getRealTop(\'txtDenumire\')+25; div.style.display=\'block\';">
	<input name="txtProdusId" type="hidden" value="" id="txtProdusId">	</td>
    <td><input type="text" name="txtPretAchizitie" id="txtPretAchizitie"></td>
    <td><input type="text" name="txtCantitate" id="txtCantitate"></td>
    <td><div align="center">
      <input name="btnAddComponenta" type="button" id="btnAddComponenta" value="Adauga" onClick="xajax_addComponenta(xajax.getFormValues(\'frmAddComponenta\'))">
    </div></td>
    <td><div align="right">
      <input type="reset" name="Reset" value="Reset">
    </div></td>
  </tr>
</table>

</fieldset>
</form>';

$componente = '
<fieldset ><legend>Componente</legend>
<form action="" method="get" name="frmComponente" id="frmComponente">
<div id="intrare_componente" style="height:450px; overflow:auto; ">
	<div>
  <table width="90%"  border="0" cellspacing="0" cellpadding="0">
    <tr class="rowhead">
      <td width="35%"><strong>Denumire Produs </strong></td>
      <td width="23%"><strong>Pret Achizitie </strong></td>
      <td width="21%"><strong>Cantitate</strong></td>
      <td width="21%"><div align="center"><strong>Stergere</strong></div></td>
    </tr>
  </table>
  </div>
  <%componente%>
</div>
</form>
</fieldset>

<div align="center">
  <input name="btnSave" type="button" class="btnTouch" id="btnSave" value="Salveaza" onClick="xajax_save(xajax.getFormValues(\'frmDocument\'), xajax.getFormValues(\'frmComponente\'));">
</div>';
