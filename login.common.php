<?php
require_once("thirdparty/xajax/xajax_core/xajax.inc.php");

$xajax = new xajax("comanda.server.php");
$registerFunctions = true;
/*         HELPERS         */
include("include/helpers/helper.class.php");
include("include/helpers/forms.class.php");
include("include/helpers/html.class.php");
include("include/helpers/tabView.class.php");
include("include/helpers/gridView.class.php");
include("include/helpers/gui.class.php");
