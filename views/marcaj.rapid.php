<?php
$tn = new TastaturaNumerica;

$view = '
<div id="divwindowhead">Marcaj Rapid</div>
<form action="" method="post" name="frmMarcajRapid" id="frmMarcajRapid">
<table width="760" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="280" rowspan="2"><div align="center">
      <input name="txtCantitateRapid" type="text" id="txtCantitateRapid" style="width:240px;" value="0">
    </div>
	'. $tn -> html('txtCantitateRapid', 'txtCodRapid') .'
	</td>
    <td width="477"><div id="ultimulProdus" style="margin-bottom: 20px;"></div></td>
  </tr>
  <tr>
    <td>	
	<div align="center">
      <input name="txtCodRapid" type="text" id="txtCodRapid" onKeyPress="var key=event.keyCode || event.which; if (key==13){ xajax_marcareRapid(xajax.getFormValues(\'frmMarcajRapid\'), document.getElementById(\'comanda_id\').value)};" size="50">
    </div></td>
  </tr>
</table>
</form>';
