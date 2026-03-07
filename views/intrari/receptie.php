<?php
$nir = '<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="18%"><strong>Furnizor</strong>'.$obj -> input('nir_id').'</td>
    <td width="33%">'.$obj -> input('furnizor_id').'</td>
	<td width="18%"></td>
	<td width="33%"></td>
  </tr>
  <tr>
    <td><strong>Factura/Aviz nr</strong></td>
    <td>'.$obj -> input('numar_factura').'</td>
	<td></td>
	<td></td>
  </tr>
  <tr>
    <td><strong>Data factura </strong></td>
    <td>'.$obj -> input('data_factura').' <input type="button" name="a" value="cal" onClick="xajax_calPopup(\'\', \'\', \'data_factura\');">   </td>
  	<td><strong>Data scadenta</strong> </td>
	<td>'.$obj -> input('data_scadenta').'<input type="button" name="b" value="cal" onClick="xajax_calPopup(\'\', \'\', \'data_scadenta\');"> </td>
  </tr>
  <tr>
    <td><strong>Total Factura </strong></td>
    <td>'.$obj -> input('total_fara_tva').' <strong>*fara tva</strong></td>
  	<td><strong>Total TVA </strong></td>
	<td>'.$obj -> input('total_tva').'</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
	<td></td>
    <td></td>
  </tr>
</table>';
