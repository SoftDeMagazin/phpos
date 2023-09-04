<?php
	/**
	 * tables.php
	 * 
	 * xajax test script to test xajax response commands that display alert 
	 * messages, prompt dialogs and the confirm_commands.
	 */
	
	$baseFolder = dirname(__FILE__, 3);
	$xajaxCore = $baseFolder . '/xajax_core';
	$xajaxPlugins = $baseFolder . '/xajax_plugins';
	 
	require $xajaxCore . '/xajax.inc.php';

	$xajax = new xajax();
	
	require $xajaxPlugins . '/response/tableUpdater.inc.php';

	$xajax->configure('requestURI', basename(__FILE__));
	
	require_once("./options.inc.php");

	$requestPremadeTable = $xajax->register(XAJAX_FUNCTION, 'premadeTable');
	
	$nUnique = 100000;
	
	function generateUniqueID()
	{
		global $nUnique;
		
		return ++$nUnique;
	}
	
	$objResponse = new xajaxResponse();
	
	class clsPage {
		function __construct() {
		}
		
		function generateTable() {
			global $objResponse;
			$objResponse->clear("content", "innerHTML");
			$objResponse->plugin('clsTableUpdater', 'appendTable', 'theTable', 'content');
			return $objResponse;
		}
		
		function appendRow() {
			global $objResponse;
			global $aRequests;
			$row = time();
			$row = "row_" . $row;
			$objResponse->plugin('clsTableUpdater', 'appendRow', $row, 'theTable');
			$objAnchor = new clsAnchor(['event' => ['onclick', &$aRequests['setrownumber'], [[0, XAJAX_QUOTED_VALUE, $row]]], 'child' => new clsLiteral($row)]);
			$objResponse->plugin('clsTableUpdater', 'assignRow', [$objAnchor->getHTML()], $row);
			return $objResponse;
		}
		
		function insertRow($old_row) {
			global $objResponse;
			global $aRequests;
			$row = time();
			$row = "row_" . $row;
			$id = " id='";
			$id .= $row;
			$id .= "'";
			$objResponse->plugin('clsTableUpdater', 'insertRow', $row, 'theTable', $old_row);
			$aRequests['setrownumber']->setParameter(0, XAJAX_QUOTED_VALUE, $row);
			$link = "<a href='#' onclick='" . $aRequests['setrownumber']->getScript() . "'>{$row}</a>";
			$objResponse->plugin('clsTableUpdater', 'assignRow', [$link], $row);
			return $objResponse;
		}
		
		function replaceRow($old_row) {
			global $objResponse;
			global $aRequests;
			$row = time();
			$row = "row_" . $row;
			$id = " id='";
			$id .= $row;
			$id .= "'";
			$objResponse->plugin('clsTableUpdater', 'replaceRow', $row, 'theTable', $old_row);
			$aRequests['setrownumber']->setParameter(0, XAJAX_QUOTED_VALUE, $row);
			$link = "<a href='#' onclick='" . $aRequests['setrownumber']->getScript() . "'>{$row}</a>";
			$objResponse->plugin('clsTableUpdater', 'assignRow', [$link], $row);
			$objResponse->clear("RowNumber", "value");
			return $objResponse;
		}
		
		function removeRow($row) {
			global $objResponse;
			$objResponse->plugin('clsTableUpdater', 'deleteRow', $row);
			$objResponse->clear("RowNumber", "value");
			return $objResponse;
		}
		
		function setRowNumber($row) {
			global $objResponse;
			$objResponse->assign("RowNumber", "value", $row);
			return $objResponse;
		}
		
		function appendColumn() {
			global $objResponse;
			global $aRequests;
			$column = time();
			$column = "column_" . $column;
			$id = " id='";
			$id .= $column;
			$id .= "'";
			$aRequests['setcolumnnumber']->setParameter(0, XAJAX_QUOTED_VALUE, $column);
			$link = "<a href='#' onclick='" . $aRequests['setcolumnnumber']->getScript() . "'>{$column}</a>";
			$objResponse->plugin('clsTableUpdater', 'appendColumn', ["name"=>$link, "id"=>$column], 'theTable');
			return $objResponse;
		}
		
		function insertColumn($old_column) {
			global $objResponse;
			global $aRequests;
			$column = time();
			$column = "column_" . $column;
			$id = " id='";
			$id .= $column;
			$id .= "'";
			$aRequests['setcolumnnumber']->setParameter(0, XAJAX_QUOTED_VALUE, $column);
			$link = "<a href='#' onclick='" . $aRequests['setcolumnnumber']->getScript() . "'>{$column}</a>";
			$objResponse->plugin('clsTableUpdater', 'insertColumn', ['id'=>$column, 'name'=>$link], $old_column);
			return $objResponse;
		}
		
		function replaceColumn($old_column) {
			global $objResponse;
			global $aRequests;
			$column = time();
			$column = "column_" . $column;
			$id = " id='";
			$id .= $column;
			$id .= "'";
			$aRequests['setcolumnnumber']->setParameter(0, XAJAX_QUOTED_VALUE, $column);
			$link = "<a href='#' onclick='" . $aRequests['setcolumnnumber']->getScript() . "'>{$column}</a>";
			$objResponse->plugin('clsTableUpdater', 'replaceColumn', ['id'=>$column, 'name'=>$link], $old_column);
			$objResponse->clear("ColumnNumber", "value");
			return $objResponse;
		}
		
		function removeColumn($column) {
			global $objResponse;
			$objResponse->plugin('clsTableUpdater', 'deleteColumn', $column);
			$objResponse->clear("ColumnNumber", "value");
			return $objResponse;
		}
		
		function setColumnNumber($column) {
			global $objResponse;
			$objResponse->assign("ColumnNumber", "value", $column);
			return $objResponse;
		}
		
		function setCellValue($row, $column, $value) {
			global $objResponse;
			if (0 == strlen((string) $row) || 0 == strlen((string) $column)) {
				$objResponse->alert("Please select a row and column.");
				return $objResponse;
			}
			$objResponse->plugin('clsTableUpdater', 'assignCell', $row, $column, $value);
			return $objResponse;
		}
		
		function setCellProperty($row, $column, $property, $value) {
			global $objResponse;
			if (0 == strlen((string) $row) || 0 == strlen((string) $column)) {
				$objResponse->alert("Please select a row and column.");
				return $objResponse;
			}
			$objResponse->plugin('clsTableUpdater', 'assignCellProperty', $row, $column, $property, $value);
			return $objResponse;
		}
		
		function setRowProperty($row, $property, $value) {
			global $objResponse;
			if (0 == strlen((string) $row)) {
				$objResponse->alert("Please select a row.");
				return $objResponse;
			}
			$objResponse->plugin('clsTableUpdater', 'assignRowProperty', $property, $value, $row);
			return $objResponse;
		}
		
		function setColumnProperty($column, $property, $value) {
			global $objResponse;
			if (0 == strlen((string) $column)) {
				$objResponse->alert("Please select a column");
				return $objResponse;
			}
			$objResponse->plugin('clsTableUpdater', 'assignColumnProperty', $property, $value, $column);
			return $objResponse;
		}
	}
	
	$page = new clsPage();
	
	$aRequests =& $xajax->registerCallableObject($page);
	
	// rows
	$aRequests['removerow']->setParameter(0, XAJAX_INPUT_VALUE, 'RowNumber');
	$aRequests['replacerow']->setParameter(0, XAJAX_INPUT_VALUE, 'RowNumber');
	$aRequests['insertrow']->setParameter(0, XAJAX_INPUT_VALUE, 'RowNumber');
	
	// columns
	$aRequests['removecolumn']->setParameter(0, XAJAX_INPUT_VALUE, 'ColumnNumber');
	$aRequests['replacecolumn']->setParameter(0, XAJAX_INPUT_VALUE, 'ColumnNumber');
	$aRequests['insertcolumn']->setParameter(0, XAJAX_INPUT_VALUE, 'ColumnNumber');
	
	// cells
	$aRequests['setcellvalue']->setParameter(0, XAJAX_INPUT_VALUE, 'RowNumber');
	$aRequests['setcellvalue']->setParameter(1, XAJAX_INPUT_VALUE, 'ColumnNumber');
	$aRequests['setcellvalue']->setParameter(2, XAJAX_INPUT_VALUE, 'Value');
	
	$aRequests['setcellproperty']->setParameter(0, XAJAX_INPUT_VALUE, 'RowNumber');
	$aRequests['setcellproperty']->setParameter(1, XAJAX_INPUT_VALUE, 'ColumnNumber');
	$aRequests['setcellproperty']->setParameter(2, XAJAX_INPUT_VALUE, 'Property');
	$aRequests['setcellproperty']->setParameter(3, XAJAX_INPUT_VALUE, 'PropertyValue');
	
	$aRequests['setrowproperty']->setParameter(0, XAJAX_INPUT_VALUE, 'RowNumber');
	$aRequests['setrowproperty']->setParameter(1, XAJAX_INPUT_VALUE, 'RowProperty');
	$aRequests['setrowproperty']->setParameter(2, XAJAX_INPUT_VALUE, 'RowPropertyValue');
	
	$aRequests['setcolumnproperty']->setParameter(0, XAJAX_INPUT_VALUE, 'ColumnNumber');
	$aRequests['setcolumnproperty']->setParameter(1, XAJAX_INPUT_VALUE, 'ColumnProperty');
	$aRequests['setcolumnproperty']->setParameter(2, XAJAX_INPUT_VALUE, 'ColumnPropertyValue');
	
	
	$folderCore = dirname(__FILE__, 3) . '/xajax_core';
	include $folderCore . '/xajaxControl.inc.php';
	
	$folderControls = dirname(__FILE__, 3) . '/xajax_controls';
	include $folderControls . '/table.inc.php';
	include $folderControls . '/literal.inc.php';
	include $folderControls . '/anchor.inc.php';

	$xajax->processRequest();

	include $folderControls . '/input.inc.php';
	include $folderControls . '/break.inc.php';
	include $folderControls . '/select.inc.php';
	
	function &onAddColumn($mData, $mConfiguration)
	{
		// $mData contains the text for the column header
		$column = generateUniqueID();
		$column = "column_" . $column;
		
		$objCell = new clsTableCell(['attributes' => ['id' => $column], 'child' => new clsAnchor(['events' => [['onclick', $mConfiguration['column'], [[0, XAJAX_QUOTED_VALUE, $column]]]], 'child' => new clsLiteral($mData)])]);
		
		return $objCell;
	}
	
	function &onAddCell($mData, $mConfiguration)
	{
		// $mData contains the text for the cell
		
		$objCell = new clsTableCell(['child' => new clsLiteral($mData)]);
		
		return $objCell;
	}
	
	function &onAddHeader($mData, $mConfiguration)
	{
		$objTableHeader = new clsTableHeader();
		$objTableHeader->setAttribute('id', 'theTable_header');
		$objTableHeader->setEvent_AddRow('onAddRow');
		$objTableHeader->addRows($mData, $mConfiguration);
		
		return $objTableHeader;
	}
	
	function &onAddBody($mData, $mConfiguration)
	{
		$objTableBody = new clsTableBody();
		$objTableBody->setAttribute('id', 'theTable_body');
		$objTableBody->setEvent_AddRow('onAddRow');
		$objTableBody->addRows($mData, $mConfiguration);
		
		return $objTableBody;
	}

	function &onAddRow($mData, $mConfiguration)
	{
		// $mData contains an array with three elements
		
		// if $mConfiguration has only 1 element, then
		// this is a request for a header row
		// else a request for a body row
		
		$objTableRow = new clsTableRow();
		
		if (1 == (is_countable($mConfiguration) ? count($mConfiguration) : 0)) {
			$objTableRow->setEvent_AddCell('onAddColumn');
			$objTableRow->addChild(new clsTableCell());
			$objTableRow->addCells($mData, $mConfiguration);
		} else {
			$row = generateUniqueID();
			$row = "row_" . $row;
			
			$objTableRow->setAttribute('id', $row);

			$objTableRow->setEvent_AddCell('onAddCell');
			$objTableRow->addChild(new clsTableCell(['child' => new clsAnchor(['event' => ['onclick', $mConfiguration['row'], [[0, XAJAX_QUOTED_VALUE, $row]]], 'child' => new clsLiteral('select >>')])]));
			$objTableRow->addCells($mData, $mConfiguration);
		}
		
		return $objTableRow;
	}
	
	function &onAddFooterRow($mData, $mConfiguration)
	{
		// $mData contains an array with one element
		// need to use colspan to expand to fit whole table
		$objRow = new clsTableRow(['child' => new clsTableCell(['attributes' => ['colspan' => 4], 'child' => new clsLiteral($mData[0])])]);
		
		return $objRow;
	}
	
	function premadeTable() {
		global $aRequests;
		
		$aHeader = [['Name', 'Occupation', 'Date Hired']];
		$aBody = [['John Smith', 'Operations Manager', '2000/10/20'], ['Wolfgang Mitz', 'Forklift Driver', '1999/01/25'], ['Thomas Woodhouse', 'Loading Dock Operator', '2002/04/12']];
		$aFooter = [['xajax rocks']];
		
		$objTable = new clsTable();
		$objTable->setAttribute('id', 'theTable');
		$objTable->setEvent_AddHeader('onAddHeader');
		$objTable->setEvent_AddBody('onAddBody');
		$objTable->setEvent_AddFooterRow('onAddFooterRow');
		
		$objTable->addHeader($aHeader, ['column' => &$aRequests['setcolumnnumber']]);
		$objTable->addBody($aBody, ['column' => &$aRequests['setcolumnnumber'], 'row' => &$aRequests['setrownumber']]);
		$objTable->addFooter($aFooter);
		
		$objResponse = new xajaxResponse();
		$objResponse->assign('content', 'innerHTML', $objTable->getHTML());
		return $objResponse;
	}
	
	$litPlusSpace = new clsLiteral('+--&nbsp;');
	$litSpaceBar = new clsLiteral('&nbsp;|');
	
	$anchorRemoveRow = new clsAnchor(['children' => [new clsLiteral('Remove Row')]]);
	$anchorRemoveRow->setEvent('onclick', $aRequests['removerow']);
	
	$anchorReplaceRow = new clsAnchor(['children' => [new clsLiteral('Replace Row')]]);
	$anchorReplaceRow->setEvent('onclick', $aRequests['replacerow']);
	
	$anchorInsertRow = new clsAnchor(['children' => [new clsLiteral('Insert Row Before')]]);
	$anchorInsertRow->setEvent('onclick', $aRequests['insertrow']);

	$anchorRemoveColumn = new clsAnchor(['children' => [new clsLiteral('Remove Column')]]);
	$anchorRemoveColumn->setEvent('onclick', $aRequests['removecolumn']);
	
	$anchorReplaceColumn = new clsAnchor(['children' => [new clsLiteral('Replace Column')]]);
	$anchorReplaceColumn->setEvent('onclick', $aRequests['replacecolumn']);

	$anchorInsertColumn = new clsAnchor(['children' => [new clsLiteral('Insert Column Before')]]);
	$anchorInsertColumn->setEvent('onclick', $aRequests['insertcolumn']);

	$anchorSetCellValue = new clsAnchor(['children' => [new clsLiteral('Set Value')]]);
	$anchorSetCellValue->setEvent('onclick', $aRequests['setcellvalue']);
	
	$aPropertyOptions = [new clsOption(['attributes' => ['value' => 'style.backgroundColor'], 'children' => [new clsLiteral('Background Color')]]), new clsOption(['attributes' => ['value' => 'style.padding'], 'children' => [new clsLiteral('Padding')]]), new clsOption(['attributes' => ['value' => 'style.border'], 'children' => [new clsLiteral('Border')]])];

	$anchorSetCellProperty = new clsAnchor(['children' => [new clsLiteral('Set Property')]]);
	$anchorSetCellProperty->setEvent('onclick', $aRequests['setcellproperty']);

	$anchorSetRowProperty = new clsAnchor(['children' => [new clsLiteral('Set Property')]]);
	$anchorSetRowProperty->setEvent('onclick', $aRequests['setrowproperty']);

	$anchorSetColumnProperty = new clsAnchor(['children' => [new clsLiteral('Set Property')]]);
	$anchorSetColumnProperty->setEvent('onclick', $aRequests['setcolumnproperty']);
	
	$table = new clsTable(['children' => [new clsTableHeader(['children' => [new clsTableRow(['children' => [new clsTableCell(['children' => [new clsLiteral('Click or type a row'), new clsBreak(), new clsLiteral('that is in the table')]]), new clsTableCell(['children' => [new clsLiteral('Click or type a column'), new clsBreak(), new clsLiteral('that is in the table')]]), new clsTableCell(['children' => [new clsLiteral('Select a row and column'), new clsBreak(), new clsLiteral('then enter a value')]]), new clsTableCell(['children' => [new clsLiteral('Select a row and column'), new clsBreak(), new clsLiteral('then select a property and value')]]), new clsTableCell(['children' => [new clsLiteral('Select a row'), new clsBreak(), new clsLiteral('then select a property and value')]]), new clsTableCell(['children' => [new clsLiteral('Select a column'), new clsBreak(), new clsLiteral('then select a property and value')]])]])]]), new clsTableBody(['children' => [new clsTableRow(['children' => [new clsTableCell(['attributes' => ['valign' => 'top'], 'children' => [new clsInput(['attributes' => ['id' => 'RowNumber', 'name' => 'RowNumber', 'type' => 'text']]), new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorRemoveRow, new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorReplaceRow, new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorInsertRow, new clsBreak(), new clsBreak()]]), new clsTableCell(['attributes' => ['valign' => 'top'], 'children' => [new clsInput(['attributes' => ['id' => 'ColumnNumber', 'name' => 'ColumnNumber', 'type' => 'text']]), new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorRemoveColumn, new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorReplaceColumn, new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorInsertColumn, new clsBreak(), new clsBreak()]]), new clsTableCell(['attributes' => ['valign' => 'top'], 'children' => [new clsInput(['attributes' => ['id' => 'Value', 'name' => 'Value', 'type' => 'text']]), new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorSetCellValue, new clsBreak(), new clsBreak()]]), new clsTableCell(['attributes' => ['valign' => 'top'], 'children' => [new clsSelect(['attributes' => ['id' => 'Property', 'name' => 'Property'], 'children' => [new clsOption(['attributes' => ['value' => 'style.backgroundColor'], 'children' => [new clsLiteral('Background Color')]]), new clsOption(['attributes' => ['value' => 'style.padding'], 'children' => [new clsLiteral('Padding')]]), new clsOption(['attributes' => ['value' => 'style.border'], 'children' => [new clsLiteral('Border')]])]]), new clsBreak(), new clsInput(['attributes' => ['id' => 'PropertyValue', 'name' => 'PropertyValue', 'type' => 'text']]), new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorSetCellProperty, new clsBreak(), new clsBreak()]]), new clsTableCell(['attributes' => ['valign' => 'top'], 'children' => [new clsSelect(['attributes' => ['id' => 'RowProperty', 'name' => 'RowProperty'], 'children' => $aPropertyOptions]), new clsBreak(), new clsInput(['attributes' => ['id' => 'RowPropertyValue', 'name' => 'RowPropertyValue', 'type' => 'text']]), new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorSetRowProperty, new clsBreak(), new clsBreak()]]), new clsTableCell(['attributes' => ['valign' => 'top'], 'children' => [new clsSelect(['attributes' => ['id' => 'ColumnProperty', 'name' => 'ColumnProperty'], 'children' => $aPropertyOptions]), new clsBreak(), new clsInput(['attributes' => ['id' => 'ColumnPropertyValue', 'name' => 'ColumnPropertyValue', 'type' => 'text']]), new clsBreak(), $litSpaceBar, new clsBreak(), $litPlusSpace, $anchorSetColumnProperty, new clsBreak(), new clsBreak()]])]])]]), new clsTableFooter(['children' => [new clsTableRow(['children' => [new clsTableCell(['attributes' => ['colspan' => 6], 'children' => [new clsLiteral('The table will appear below...')]])]])]])]]);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>xajax Test Suite</title>
		<?php $xajax->printJavascript(); ?>
		<style>
		table {
			border: 1px solid #8888aa;
		}
		thead {
			background: #bbbbdd;
		}
		tbody {
		}
		tfoot {
			background: #ccccee;
		}
		</style>
	</head>
	<body>
		<a href='#' onclick='<?php $aRequests['generatetable']->printScript(); ?>'>Generate the table</a>&nbsp;then&nbsp;
		<a href='#' onclick='<?php $aRequests['appendcolumn']->printScript(); ?>'>Append one or more columns</a>&nbsp;then&nbsp;
		<a href='#' onclick='<?php $aRequests['appendrow']->printScript(); ?>'>Append one or more rows</a><br />
		<br />
		Or <a href='#' onclick='<?php $requestPremadeTable->printScript(); ?>'>Use a pre-made table</a>
		<br />
		<?php $table->printHTML(); ?>
		<br />
		<div id='content'></div>
	</body>
</html>

