<?php

class xajaxScriptPlugin extends xajaxRequestPlugin
{
	public $sRequest = '';
	public $sHash = null;
	public $sRequestURI = '';
	public $bDeferScriptGeneration = false;
	public $bValidateHash = true;
	
	public $bWorking = false;
	
	function __construct()
	{
		if (isset($_GET['xjxGenerateJavascript'])) {
			$this->sRequest = 'script';
			$this->sHash = $_GET['xjxGenerateJavascript'];
		}
		
		if (isset($_GET['xjxGenerateStyle'])) {
			$this->sRequest = 'style';
			$this->sHash = $_GET['xjxGenerateStyle'];
		}
	}

	/*
		Function: configure
		
		Sets/stores configuration options used by this plugin.
	*/
	function configure($sName, $mValue)
	{
		if ('requestURI' == $sName) {
			$this->sRequestURI = $mValue;
		} else if ('deferScriptGeneration' == $sName) {
			if (true === $mValue || false === $mValue)
				$this->bDeferScriptGeneration = $mValue;
		} else if ('deferScriptValidateHash' == $sName) {
			if (true === $mValue || false === $mValue)
				$this->bValidateHash = $mValue;
		}
	}
	
	function generateClientScript()
	{
		if ($this->bWorking)
			return;
		
		if (true === $this->bDeferScriptGeneration)
		{
			$this->bWorking = true;
			
			$sQueryBase = '?';
			if (0 < strpos((string) $this->sRequestURI, '?'))
				$sQueryBase = '&';
			
			$aScripts = $this->_getSections('script');
			if (0 < (is_countable($aScripts) ? count($aScripts) : 0))
			{
//				echo "<!--" . print_r($aScripts, true) . "-->";
			
				$sHash = md5(implode($aScripts));
				$sQuery = $sQueryBase . "xjxGenerateJavascript=" . $sHash;
				
				echo "\n<script type='text/javascript' src='" . $this->sRequestURI . $sQuery . "' charset='UTF-8'></script>\n";
			}
			
			$aStyles = $this->_getSections('style');
			if (0 < (is_countable($aStyles) ? count($aStyles) : 0))
			{
//				echo "<!--" . print_r($aStyles, true) . "-->";
			
				$sHash = md5(implode($aStyles));
				$sQuery = $sQueryBase . "xjxGenerateStyle=" . $sHash;
				
				echo "\n<link href='" . $this->sRequestURI . $sQuery . "' rel='Stylesheet' />\n";
			}
			
			$this->bWorking = false;
		}
	}
	
	function canProcessRequest()
	{
		return ('' != $this->sRequest);
	}
	
	function &_getSections($sType)
	{
		$objPluginManager = xajaxPluginManager::getInstance();
		
		$objPluginManager->configure('deferScriptGeneration', 'deferred');
		
		$aSections = [];
		
		// buffer output
		
		ob_start();
		$objPluginManager->generateClientScript();
		$sScript = ob_get_clean();
		
		// parse out blocks
		
		$aParts = explode('</' . $sType . '>', $sScript);
		foreach ($aParts as $sPart)
		{
			$aValues = explode('<' . $sType, $sPart, 2);
			if (2 == count($aValues))
			{
				[$sJunk, $sPart] = $aValues;
				
				$aValues = explode('>', $sPart, 2);
				if (2 == count($aValues))
				{
					[$sJunk, $sPart] = $aValues;
			
					if (0 < strlen($sPart))
						$aSections[] = $sPart;
				}
			}
		}

		$objPluginManager->configure('deferScriptGeneration', $this->bDeferScriptGeneration);
		
		return $aSections;
	}
	
	function processRequest()
	{
		if ($this->canProcessRequest())
		{
			$aSections = $this->_getSections($this->sRequest);
			
//			echo "<!--" . print_r($aSections, true) . "-->";
			
			// validate the hash
			$sHash = md5(implode($aSections));
			if (false == $this->bValidateHash || $sHash == $this->sHash)
			{
				$sType = 'text/javascript';
				if ('style' == $this->sRequest)
					$sType = 'text/css';
					
				$objResponse = new xajaxCustomResponse($sType);
				
				foreach ($aSections as $sSection)
					$objResponse->append($sSection . "\n");
				
				$objResponseManager = xajaxResponseManager::getInstance();
				$objResponseManager->append($objResponse);
				
				header ('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*24)) . ' GMT');

				return true;
			}
			
			return 'Invalid script or style request.';
			trigger_error('Hash mismatch: ' . $this->sRequest . ': ' . $sHash . ' <==> ' . $this->sHash, E_USER_ERROR);
		}
	}
}

$objPluginManager = xajaxPluginManager::getInstance();
$objPluginManager->registerPlugin(new xajaxScriptPlugin(),9999);
