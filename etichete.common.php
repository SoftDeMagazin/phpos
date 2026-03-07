<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("etichete.server.php");
$registerFunctions = true;
/*         HELPERS         */
include("include/helpers/helper.class.php");
include("include/helpers/forms.class.php");
include("include/helpers/html.class.php");
include("include/helpers/tabView.class.php");
include("include/helpers/gridView.class.php");
include("include/helpers/gui.class.php");
include("include/helpers/print.class.php");

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
include("include/models/etichete.class.php");

include("config/config.php");
$xajax -> registerFunction("componente");
$xajax -> registerFunction("cautaProdus");
$xajax -> registerFunction("selectProdus");
$xajax -> registerFunction("calculeaza");
$xajax -> registerFunction("searchCodBare");
$xajax -> registerFunction("calculatorPachete");
$xajax -> registerFunction("calculeazaPachete");
$xajax -> registerFunction("salveazaAntet");
$xajax -> registerFunction("adaugaComponenta");
$xajax->registerFunction("frmProdus");
$xajax->registerFunction("frmSave");
$xajax->registerFunction("inchideNir");
$xajax->registerFunction("onLoad");
$xajax->registerFunction("editComponenta");
$xajax->registerFunction("stergeComponenta");
$xajax->registerFunction("verificare");
$xajax->registerFunction("stergeIesire");
