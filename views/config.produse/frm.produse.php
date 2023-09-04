<?php
$frmProdus = '
<form action="" method="post" name="frmProdus" id="frmProdus">
<fieldset style="padding: 5px 5px 5px 5px;"><legend><%titlu%></legend>
<input name="produs_id" type="hidden" id="produs_id" value="<%produs_id%>">
<label>Denumire produs</label><br>
<input name="denumire" type="text" id="denumire" value="<%denumire%>" size="40">
<br><label>Cod produs</label>
<br>
<input name="cod" type="text" id="cod" value="<%cod%>">
<br> 
Cod bare<br>
<input name="cod_bare" type="text" id="cod_bare" value="<%cod_bare%>" size="50">
<br> 
Departament<br>
<select name="categorie_id" id="categorie_id">
  <%categorie_id%>
</select>
<br>
Pret<br>
<input name="pret" type="text" id="pret" value="<%pret%>">
<br> 
Activ<br>
<select name="la_vanzare" id="la_vanzare">
  <%la_vanzare%>
</select>
<br>
Tip produs<br>
<select name="tip_produs" id="tip_produs">
  <%tip_produs%>
</select>
</br>
Cota tva </br>
<select name="cotatva_id" id="cotatva_id">
  <%cotatva_id%>
</select>
<br>
<div style="text-align:right"><input name="btnSave" id="btnSave" type="button" value="Salveaza" onClick="xajax_frmSave(xajax.getFormValues(\'frmProdus\'));" accesskey="s"> <input name="btnCancel" id="btnCancel" type="button" value="Abandon" onClick="xajax_btnRenuntaDialog();" accesskey="c"></div>
</fieldset>
</form>';
?>