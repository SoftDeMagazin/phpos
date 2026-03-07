<?php
    $zi = new ZileEconomice($mysql);
    $zi -> getLastDay();
$lista = '<br>
<br>
<br>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="550" width="450" valign="top">
	<div id="preview" style="width:450px; height: 500px; overflow:auto; border:1px solid #000">
	</div>
	</td>
    <td valign="top" width="550">
	<div align="center">
	<form action="" method="post" name="frmFiltre" id="frmFiltre">
	  <p>
	      <input name="dateStart" id="dateStart" type="text" value="'. $zi -> obj -> data .'" readonly="" onClick="xajax_calPopup(\'\',\'\', \'dateStart\')"> 
	    -----
	    <input name="dateStop" type="text" id="dateStop" value="'. $zi -> obj -> data .'" readonly="" onClick="xajax_calPopup(\'\',\'\', \'dateStop\')"><br>
<br>
	    <input name="btnGenereaza" type="button" class="btnTouch" id="btnGenereaza" value="Genereaza Lista" onClick="xajax_loadListaIntrari(xajax.getFormValues(\'frmFiltre\'))">
	  </form>
	</div>
	<div id="grafic" style="margin-top: 20px; text-align:center">
	</div></td>
  </tr>
  </table>
<div align="center">
      <input name="btnIesire" type="button" class="btnTouch" id="btnIesire" value="Iesire" onClick="xajax_meniu();">
    </div>
';
