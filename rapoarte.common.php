<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("rapoarte.server.php");
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

include("include/rapoarte/rapoarte.class.php");
include("include/rapoarte/rptModuriPlata.class.php");
include("include/rapoarte/rptVanzari.class.php");
include("include/rapoarte/rptVanzariTigari.class.php");
include("include/rapoarte/rptUtilizatoriModuri.class.php");
include("include/rapoarte/rptBonuriEmise.class.php");
include("include/rapoarte/rptCaseModuri.class.php");

include("include/libchart/classes/libchart.php");
include("config/config.php");
$xajax -> registerFunction("loadRaport");
$xajax -> registerFunction("printRaport");
