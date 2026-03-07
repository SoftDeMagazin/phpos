) <div style="margin-top:75px ">
<table width="900" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td width="25%"><div align="center">
      <input name="btnMarcaj" type="button" id="btnMarcaj" class="btnTouch" value="Marcaj" onClick="window.location.href = 'comanda.php'" <?php echo $user -> verificaDreptButton('marcaj');?>>
    </div></td>
    <td width="25%"><div align="center">
      <input name="btnConfigurari" type="button" id="btnConfigurari" class="btnTouch" value="Configurari" onClick="window.location.href = 'configurari.php'" <?php echo $user -> verificaDreptButton('configurari');?>>
    </div></td>
    <td width="25%"><div align="center">
      <input name="btnRapoarte" type="button" id="btnRapoarte" class="btnTouch" value="Rapoarte" onClick="window.location.href = 'rapoarte.php'" <?php echo $user -> verificaDreptButton('rapoarte');?>>
    </div></td>
    <td width="25%"><div align="center">
      <input name="btnInchidereZi" type="button" id="btnInchidereZi" class="btnTouch" value="Inchidere zi" onClick="window.location.href = 'inchidere.zi.php'" <?php echo $user -> verificaDreptButton('inchidere_zi');?>>
    </div></td>
  </tr>
  <tr>
    <td><div align="center">
        <input name="btnEvidentaVanzari" type="button" id="btnEvidentaVanzari4" class="btnTouch" value="Evidenta Vanzari" onClick="window.location.href = 'evidenta.vanzari.php'" <?php echo $user -> verificaDreptButton('evidenta_bonuri');?>>
    </div></td>
    <td><div align="center">
      <input name="btnConfigurariProduse" type="button" id="btnConfigurariProduse"  class="btnTouch"value="Configurari Produse" onClick="window.location.href = 'config.produse.php'" <?php echo $user -> verificaDreptButton('configurari_produse');?>>
    </div></td>
    <td><div align="center">
      <input name="btnEvidantaBonuri" type="button" id="btnEvidantaBonuri" class="btnTouch" value="Evidenta Bonuri" onClick="window.location.href = 'evidenta.bonuri.php'" <?php echo $user -> verificaDreptButton('evidenta_bonuri');?>>
    </div></td>
    <td><div align="center">
      <input name="btnLogOut" type="button" id="btnLogOut" class="btnTouch" value="Log Out" onClick="window.location.href = 'logout.php'" >
    </div></td>
  </tr>
<tr>
    <td><div align="center">
           </div></td>
    <td><div align="center">
       </div></td>
    <td><div align="center">
    </div></td>
    <td><div align="center">
      <input name="btnLogOut" type="button" id="btnLogOut" class="btnTouch" value="IESIRE" onClick="window.location.href = 'exit.php'" >
    </div></td>
  </tr>

</table>
</div>
