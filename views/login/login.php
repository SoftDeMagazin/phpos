      <form action="" method="post" name="login">
      <table border="0" align="center" cellpadding="4" cellspacing="2">
        <tr>
          <td  style="vertical-align: top; font-weight: bold;" class="nume">Autentificare</td>
        </tr>
        <tr>
          <td  style="vertical-align: top" class="nume">
            <label for="name"><strong>User</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <?php
            $user = new Users($mysql);
            $user -> find(array("WHERE", "activ" => " = 'DA'", "ORDER BY", "nume", "ASC"));
            if (isset($user -> objects)) {
                foreach ($user -> objects as $objUser) {
                    $options[$objUser -> user_id] = $objUser -> nume;
                }
            }
            $frm = new Forms();
            echo $frm -> input("user_id", array("options" => $options));
            $options = null;
            $case = new CaseFiscale($mysql);
            $case -> find(array("ORDER BY nume_casa ASC"));
            if (isset($user -> objects)) {
                foreach ($case -> objects as $objCasa) {
                    $options[$objCasa -> casa_id] = $objCasa -> nume_casa;
                }
            }
            echo $frm -> input("casa_id", array("options" => $options));
            ?>
            </td>
        </tr>
        <tr>
          <td  style="vertical-align: top" class="nume"><label for="subject"><strong>Parola</strong>&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input name="parola" type="password"  id="parola" size="30"  >        </td>
        </tr>
        <tr>
          <td >
            <input name="autentifica" type="submit" class="button" id="autentif" value="Autentifica" style="background-color:#00CC33; font-size:18px; font-weight:bold; width:100%; padding:10px 0px 10px 0px ">
          </td>
        </tr>
   </table>
    </form>
