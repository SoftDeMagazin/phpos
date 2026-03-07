<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("registru.casa.server.php");
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
include("include/models/registrucasa.class.php");
include("config/config.php");
$xajax -> registerFunction("incarcaRegistru");
$xajax -> registerFunction("btnAdaugaPlata");
$xajax -> registerFunction("platiDiverse");
$xajax -> registerFunction("salveazaPlatiDiverse");
$xajax -> registerFunction("btnAdaugaIncasare");
$xajax -> registerFunction("incasariDiverse");
$xajax -> registerFunction("salveazaIncasariDiverse");
$xajax -> registerFunction("facturaFurnizor");
$xajax -> registerFunction("afiseazaFacturi");
$xajax -> registerFunction("adaugaPlataFacturaFurnizor");
$xajax -> registerFunction("salveazaPlataFacturaFurnizor");
$xajax -> registerFunction("sterge");
