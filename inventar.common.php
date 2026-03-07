<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("inventar.server.php");
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
include("include/models/inventar.class.php");

include("config/config.php");
$xajax -> registerFunction("deschideInventar");
$xajax -> registerFunction("searchProduse");
$xajax -> registerFunction("searchCodBare");
$xajax -> registerFunction("addComponenta");
$xajax -> registerFunction("removeComponenta");
$xajax -> registerFunction("save");
$xajax -> registerFunction("tools");
$xajax -> registerFunction("cod_bare");
$xajax -> registerFunction("recalculare");
$xajax -> registerFunction("editComponenta");
$xajax -> registerFunction("saveComponenta");
$xajax->registerFunction("frmProdus");
$xajax->registerFunction("frmSave");
/* tools */
$xajax -> registerFunction("toolCategorii");
$xajax -> registerFunction("toolAdaugaCategorie");
$xajax -> registerFunction("stergeInventar");
$xajax -> registerFunction("cautaProdus");
$xajax -> registerFunction("selectProdus");
