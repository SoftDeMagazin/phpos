<?php
/* FONTS DEFINITION */
define('PRINTER_FW_NORMAL', 10);
define('PRINTER_FW_BOLD', 10);
$fonts = array( 
				'default' => array ( 
					'face' => "Arial",
					'width' => 9,
					'height' => 10,
					'weight' => PRINTER_FW_NORMAL,
					'italic' => false,
					'underline' => false,
					'strikeout' => false,
					'orientation' => 0
					),
					
				'bold' => array ( 
					'face' => "Arial",
					'width' => 9,
					'height' => 12,
					'weight' => PRINTER_FW_BOLD,
					'italic' => false,
					'underline' => false,
					'strikeout' => false,
					'orientation' => 0
					),

				'h1' => array ( 
					'face' => "Arial",
					'width' => 12,
					'height' => 15,
					'weight' => PRINTER_FW_BOLD,
					'italic' => false,
					'underline' => false,
					'strikeout' => false,
					'orientation' => 0
					)
				);

?>
