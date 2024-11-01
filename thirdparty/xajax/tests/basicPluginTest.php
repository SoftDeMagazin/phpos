<?php

$core = dirname(__FILE__, 2) . '/xajax_core';
require_once $core . '/xajax.inc.php';

$xajax = new xajax();

//$xajax->configure('debug', true);
$xajax->configure('javascript URI', '../');

require_once $core . '/xajaxPlugin.inc.php';
require_once $core . '/xajaxPluginManager.inc.php';

class testPlugin extends xajaxResponsePlugin
{
	public $sDefer = '';
	
	function __construct()
 {
 }
	
	function getName()
	{
		return 'testPlugin';
	}
	
	function generateClientScript()
	{
		echo "\n<script type='text/javascript' " . $this->sDefer . "charset='UTF-8'>\n";
		echo "/* <![CDATA[ */\n";

		echo "xajax.commands['testPlg'] = function(args) { \n";
		echo "\talert('Test plugin command received: ' + args.data);\n";
		echo "}\n";

		echo "/* ]]> */\n";
		echo "</script>\n";
	}
	
	function testMethod()
	{
		$this->addCommand(['n'=>'testPlg'], 'abcde]]>fg');	
	}
}

$objPluginManager = &xajaxPluginManager::getInstance();
$objPluginManager->registerPlugin(new testPlugin());

function showOutput()
{
	$testResponse = new xajaxResponse();
	$testResponse->alert("Edit this test and uncomment lines in the showOutput() method to test plugin calling");
	// PHP4 & PHP5
	$testResponse->plugin("testPlugin", "testMethod");
	
	// PHP5 ONLY - Uncomment to test
	//$testResponse->plugin("testPlugin")->testMethod();
	
	// PHP5 ONLY - Uncomment to test
	//$testResponse->testPlugin->testMethod();
	
	$testResponseOutput = htmlspecialchars((string) $testResponse->getOutput());
	
	$objResponse = new xajaxResponse();
	$objResponse->assign("submittedDiv", "innerHTML", $testResponseOutput);
	$objResponse->plugin('testPlugin', 'testMethod');
	return $objResponse;
}

$reqShowOutput =& $xajax->register(XAJAX_FUNCTION, "showOutput");

$xajax->processRequest();

include_once($core . '/xajaxControl.inc.php');

$controls = dirname(__FILE__, 2) . '/xajax_controls';
include_once($controls . '/button.inc.php');
include_once($controls . '/literal.inc.php');

$buttonShowOutput = new clsButton(['attributes' => ['id' => 'btnShowOutput', 'name' => 'btnShowOutput'], 'children' => [new clsLiteral('Show Response XML')]]);
$buttonShowOutput->setEvent('onclick', $reqShowOutput);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Basic Plugin Test | xajax Tests</title>
<?php $xajax->printJavascript() ?>
</head>
<body>

<h2><a href="index.php">xajax Tests</a></h2>
<h1>Basic Plugin Test</h1>

<form id="testForm1" onsubmit="return false;">
<p><?php $buttonShowOutput->printHTML(); ?></p>
</form>

<div id="submittedDiv"></div>

</body>
</html>