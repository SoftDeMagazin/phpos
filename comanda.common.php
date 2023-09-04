<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("comanda.server.php");
$registerFunctions = TRUE;
/*         HELPERS         */
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
include("include/models/cotetva.class.php");

include("config/config.php");
include("include/xajax_global/clienti.php");
$xajax->registerFunction("onLoad");
$xajax->registerFunction("afiseazaCategorie");
$xajax->registerFunction("marcareProdus");
$xajax->registerFunction("btnPlus");
$xajax->registerFunction("btnMinus");
$xajax->registerFunction("btnSterge");
$xajax->registerFunction("btnCauta");
$xajax->registerFunction("btnFunctii");
$xajax->registerFunction("golesteComanda");
$xajax->registerFunction("btnPlata");
$xajax->registerFunction("btnMarcajRapid");
$xajax->registerFunction("marcareRapid");
$xajax->registerFunction("plataRapida");
$xajax->registerFunction("plataAppendMod");
$xajax->registerFunction("btnResetPlata");
$xajax->registerFunction("btnSavePlata");
$xajax->registerFunction("btnCatalogProduse");
$xajax->registerFunction("catalogListaProduse");
$xajax->registerFunction("loadComandaContinut");
$xajax->registerFunction("calculeazaRest");
$xajax->registerFunction("selectClientFactura");
$xajax->registerFunction("emiteFactura");
$xajax->registerFunction("folosesteFacturier");
$xajax->registerFunction("clickProdusComanda");
$xajax->registerFunction("bonConsum");
$xajax->registerFunction("plataCuAvans");
$xajax->registerFunction("frmPlataCuAvans");
$xajax->registerFunction("inchidereBonAvans");
$xajax->registerFunction("btnCui");

?>
