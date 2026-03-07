<?php
        $tn = new TastaturaNumerica();
    $BonModuri = new ViewBonuriModuri($mysql);
    $BonModuri -> findAllBy("bon_id", $Bon -> obj -> bon_id);
    $innerHTML = "";
    $info = '
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr class="rowhead">
          <td>Total</td>
          <td>Casa</td>
          <td>Casier</td>
        </tr>
        <tr>
          <td>'. number_format($BonModuri -> objects[0] -> total, 2, '.', '').'</td>
          <td>'. $BonModuri -> objects[0] -> nume_casa.'</td>
          <td>'. $BonModuri -> objects[0] -> nume .'</td>
        </tr>
      </table>
	';
    $moduri = '
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" class="rowhead">Achitat</td>
          </tr>
        <tr class="rowhead">
          <td>Mod Plata </td>
          <td>Suma</td>
        </tr>
	';
    $numerar = 0;
foreach ($BonModuri -> objects as $objBon) {
    $html -> append($moduri, '<tr>
          <td>'. $objBon -> nume_mod .'</td>
          <td>'. number_format($objBon -> suma, 2, '.', '') .'</td>
        </tr>');
    if ($objBon -> cash == "DA") {
        $numerar += $objBon -> suma;
    }
}
    $html -> append($moduri, '<tr>
          <td>Total</td>
          <td>'. number_format($BonModuri -> objects[0] -> total, 2, '.', '') .'</td>
        </tr>');
    $platitNumerar = '
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr class="rowhead">
          <td>Platit cash</td>
          <td>'. number_format($numerar, 2, '.', '') .'</td>
        </tr>
	</table>';
    $calculatorRest = '
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
    <td><div style="text-align: center">
		<strong>Suma:</strong>
          <input type="text" name="txtSumaPlata" id="txtSumaPlata" readonly="" value="0">
	</div>
		'. $tn -> html('txtSumaPlata', 'txtSumaPlata') .'</td>
    <td><div align="right"></div></td>
  </tr>
  <tr class="rowhead">
    <td><strong>Rest Datorat</strong></td>
    <td><div align="right" id="rest_datorat"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">
      <input name="btnComandaNoua" type="button" id="btnComandaNoua" value="Comanda Urmatoare" class="btnTouch" accesskey="c" onClick="window.location.href = \'comanda.php\'" >
    </div></td>
    <td><div align="center">
      <input name="btnCalculeazaRest" type="button" id="btnCalculeazaRest" value="Calculeaza Rest" class="btnTouch" onClick="xajax_calculeazaRest('. number_format($numerar, 2, '.', '') .',document.getElementById(\'txtSumaPlata\').value)">
    </div></td>
  </tr>
    <tr>
    <td colspan="2"><div align="center">
      <input name="btnEmiteFactura" type="button" id="btnEmiteFactura" value="EMITE FACTURA" class="btnTouch" accesskey="r" onClick="xajax_emiteFactura(document.getElementById(\'factura_client_id\').value);" >
    </div></td>
  </tr>
</table>
	';
    $html -> append($moduri, '</table>');
    $html -> append($innerHTML, $info);
    $html -> append($innerHTML, $moduri);
    $html -> append($innerHTML, $platitNumerar);
    $html -> append($innerHTML, $calculatorRest);
