<?php
/*
insert into intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, data, activ, tip) (
select -1, -1,produse.produs_id, 0, 0, 0, '1900-01-01' as data, 1 as activ, 'init' as tip from bonuri_continut
inner join produse using(produs_id)
where bon_continut_id not in (select bon_continut_id from iesiri where tip = 'vanzare') and produse.tip_produs = 'serviciu'
)

dupa scriptul !!!
*/

include("include/helpers/helper.class.php");
include("include/helpers/forms.class.php");
include("include/helpers/html.class.php");
include("include/helpers/tabView.class.php");
include("include/helpers/gridView.class.php");
include("include/helpers/divScroll.class.php");

include("include/helpers/gui.class.php");

include("include/db/abstractdb.class.php");
include("include/db/mysql.class.php");


include("include/models/produse.class.php");
include("include/models/categorii.class.php");
include("include/models/comenzi.class.php");
include("include/models/comenzicontinut.class.php");
include("include/models/bonuri.class.php");
include("include/models/bonuricontinut.class.php");
include("include/models/bonuriplata.class.php");
include("include/models/moduriplata.class.php");
include("include/models/fiscal.class.php");
include("include/models/zileeconomice.class.php");
include("include/models/users.class.php");
include("include/models/casefiscale.class.php");
include("include/models/clienti.class.php");
include("include/models/facturiere.class.php");
include("include/models/facturi.class.php");
include("include/models/bonuriconsum.class.php");
include("include/models/bonuriconsumcontinut.class.php");
include("include/models/retetar.class.php");

include("config/config.php");

$mysql = new MySQL();
$rows = $mysql -> getRows("select bon_continut_id from bonuri_continut 
inner join produse using(produs_id)
where bon_continut_id not in (select bon_continut_id from iesiri where tip = 'vanzare') and produse.tip_produs = 'serviciu'");
$Produs = new Produse($mysql);

foreach ($rows as $row) {
    $bc = new BonuriContinut($mysql, $row['bon_continut_id']);
    $objBonContinut = $bc -> obj;

                $Produs -> get($objBonContinut -> produs_id);
    if ($Produs -> obj -> tip_produs == "reteta") {
        $Produs -> scadereStoc($objBonContinut -> cantitate, $objBonContinut -> bon_continut_id, "vanzare_reteta");
    } else {
        $Produs -> scadereStoc($objBonContinut -> cantitate, $objBonContinut -> bon_continut_id, "vanzare");
    }
}
