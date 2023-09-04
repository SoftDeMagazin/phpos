<?php
class Printer
{
	var $handle;
	var $printerName;
	
	function __construct($printerName=NULL)
		{
		$this -> printerName = $printerName;
		}
	
	function openPrinter()
		{
		$handle = printer_open($this -> printerName);
		if($handle) $this -> handle = $handle;
		else die("Eroare imprimanta!");
		}
	function closePrinter()
		{
		printer_close($this -> handle);
		}	

function arrayToPrint($toPrint)
		{
		global $fonts;
		$handle = $this -> handle;
		foreach($toPrint as $print)
			{
			
					if(is_numeric($print['coordX'])) 
						{
						$coordX = $print['coordX'];
						}
					else
						{
						$coord = explode(':', $print['coordX']);
						switch($coord[0])
							{
							case "add":
								{
								$coordX = $lastX + $coord[1];
								}break;
							case "equal":
								{
								$coordX = $lastX;
								}break;	
							}
						}	
					if(is_numeric($print['coordY']))
						{
						$coordY = $print['coordY'];
						}
					else
						{
						$coord = explode(':', $print['coordY']);
						switch($coord[0])
							{
							case "add":
								{
								$coordY = $lastY + $coord[1];
								}break;
							case "equal":
								{
								$coordY = $lastY;
								}break;	
							}

						}

			
			switch($print['type'])
				{
				case "text":
					{
						
					if(!isset($print['font'])) $print['font'] = 'default';
					if($print['font'] != false)
					{
					if(is_array($print['font']))
						{
						$font = $this -> create_font($print['font']);
						}
					else
						{
						$font = $this -> create_font($fonts[$print['font']]);
						}	
					printer_select_font($handle, $font);
					printer_delete_font($font);
					}
					
					printer_draw_text($handle, $print['value'], $coordX, $coordY);	
					}break;
				case "line":
					{
					printer_draw_line($handle, $coordX, $coordY, $coordX+$print['value'], $coordY);
					}break;	
				case "cut":
					{
					$this -> closePage();
					$this -> openPage();
					$coordX = 0;
					$coordY = 0;
					}break;	
				}
			$lastX = $coordX;
			$lastY = $coordY;
			}
		}


function openDoc($docName)
	{
	printer_start_doc($this -> handle, $docName);
	}

function closeDoc()
	{
	printer_end_doc($this -> handle);
	}	

function openPage()
	{
	printer_start_page($this -> handle);
	}

function closePage()
	{
	printer_end_page($this -> handle);
	}	
	
function printDoc($toPrint, $docName)
	{
	$this -> openPrinter();
	$this -> openDoc($docName);
	$this -> openPage();

	$this -> arrayToPrint($toPrint);

	$this -> closePage();
	$this -> closeDoc($docName);
	$this -> closePrinter();
	}

		
function create_font($array)
	{
	if(!isset($array['italic'])) $array['italic'] = false;
	if(!isset($array['underline'])) $array['underline'] = false;
	if(!isset($array['strikeout'])) $array['strikeout'] = false;
	if(!isset($array['orientation'])) $array['orientation'] = 0;
	$font = printer_create_font($array['face'], $array['height'], $array['width'], $array['weight'], $array['italic'], $array['underline'], $array['strikeout'], $array['orientation']);
	return $font;
	}


}
?>