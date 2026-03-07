<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("evidenta.loturi.server.php");
$registerFunctions = true;
/*         HELPERS         */
include("include/helpers/helper.class.php");
include("include/helpers/forms.class.php");
include("include/helpers/html.class.php");
include("include/helpers/tabView.class.php");
include("include/helpers/gridView.class.php");
include("include/helpers/gui.class.php");
include("include/helpers/print.class.php");
include("include/helpers/divScroll.class.php");

/*         DB              */
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
include("include/models/users.class.php");
include("include/models/zileeconomice.class.php");
include("include/models/niruri.class.php");
include("include/models/niruricomponente.class.php");
include("include/models/unitatimasura.class.php");
include("include/models/furnizori.class.php");
include("include/models/intraricontinut.class.php");
include("include/models/docmodificaripret.class.php");
include("include/models/modificaripret.class.php");
include("include/xajax_global/catalog.produse.php");
include("config/config.php");



$xajax -> registerFunction("genereazaLista");
$xajax -> registerFunction("selectNir");
$xajax -> registerFunction("cautaProdus");
$xajax -> registerFunction("selectProdus");
$xajax -> registerFunction("infoLot");
$xajax -> registerFunction("detaliereVanzari");
