<?php
$view = '
<div id="comandafunctii">
<div id="divwindowhead">Functii</div>
  <table width="604" border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td width="150"><div align="center">
        <input name="btnGolesteComanda" type="button" class="btnrosu" id="btnGolesteComanda" value="Goleste Comanda" onClick="xajax_golesteComanda(document.getElementById(\'comanda_id\').value)" disabled>
      </div></td>
      <td width="150"><input name="btnCatalogProduse" type="button" class="btnrosu" id="btnCatalogProduse" value="Catalog Produse" onClick="xajax_btnCatalogProduse();"></td>
      <td width="150"><input name="btnCatalogProduse" type="button" class="btnrosu" id="btnCatalogProduse" value="BON CONSUM" onClick="r = confirm(\'Sunteti sigur ca emiteti bon consum?\');if(r)xajax_bonConsum(xajax.$(\'comanda_id\').value);" disabled></td>
	  <td width="150"><input name="btnCatalogProduse" type="button" class="btnrosu" id="btnCatalogProduse" value="PLATA AVANS" onClick="xajax_frmPlataCuAvans(xajax.$(\'comanda_id\').value);" disabled></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>';
