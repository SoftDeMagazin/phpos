<?php
$tn = new TastaturaNumerica;
$view = '
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td width="340" height="300" valign="top">
		<div id="moduriPlata" style="width:100%; height:300px; overflow:auto;">
		<%moduriPlata%>
		</div>
		</td>
        <td width="10" valign="top">&nbsp;</td>
        <td valign="top" width="450">
		<form action="" method="post" name="frmPlata" id="frmPlata">
          <div id="divPlataHead">
		  <div id="rowModHead" style="width: 450px;">
			<div id="rowNumeMod" style="width:300px; float:left;"><strong>Mod Plata</strong></div>
			<div id="rowSuma" style="width:150px; float:right; text-align:left;"><strong>Suma</strong></div>
          </div>
		  </div>
		  <div id="divPlata" style="height: 200px;">
		  </div>
		  <div style="text-align: right;"><strong>Total Plata:</strong><input type="text" name="txtTotalPlata" id="txtTotalPlata" readonly="" value="<%varTotalPlata%>"></div>
		  <div style="text-align: right;"><strong>Total Achitat:</strong><input type="text" name="txtTotalAchitat" id="txtTotalAchitat" readonly="" value="0.00"></div>
		  <div style="text-align: right;"><strong>Ramas:</strong><input type="text" name="txtTotalRamas" id="txtTotalRamas" readonly="" value="<%varTotalPlata%>"> <input name="btnCopy" type="button" value="COPIAZA" onClick="document.getElementById(\'txtSumaPlata\').value = document.getElementById(\'txtTotalRamas\').value"></div>
        </form>
		</td>
      </tr>
	  <tr>
	  <td colspan="3">&nbsp;</td>
	  </tr>
      <tr>
        <td height="250" valign="top">
		<div style="text-align: center">
		<strong>Suma:</strong>
          <input type="text" name="txtSumaPlata" id="txtSumaPlata" readonly="" value="0">
</div>
		'. $tn -> html('txtSumaPlata', 'txtSumaPlata') .'
		'. $tn -> printCalculator('txtSumaPlata') .'
		</td>
        <td valign="top">&nbsp;</td>
        <td valign="middle"><div align="center">
          <input name="btnSavePlata" type="submit" id="btnSavePlata" value="Plata" onClick="xajax_btnSavePlata(xajax.getFormValues(\'frmPlata\'), document.getElementById(\'comanda_id\').value, document.getElementById(\'cui_value\').value);this.disabled=true; this.blur();" disabled>
          <input name="btnResetPlata" type="button" id="btnResetPlata" value="Reset" onClick="xajax_btnResetPlata(xajax.getFormValues(\'frmPlata\'));">
        </div></td>
      </tr>
    </table>
	</td>
  </tr>
</table>';

$rowMod = '
		  <div id="rowMod" style="width: 450px;">
			<div id="rowNumeMod" style="width:300px; float:left;"><input name="mod_plata_id[]" type="hidden" value="<%varIdMod%>"><%varNumeMod%></div>
			<div id="rowSuma" style="width:150px; float:right; text-align:right;"><input name="suma[]" type="hidden" value="<%varSuma%>"><%varSuma%></div>
          </div>

';
?>
