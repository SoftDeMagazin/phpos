<?php
require("../models/class.print.php");
require("../../config/config.php");

$toPrint[0] = array(
                'type' => 'text',
                'value' => 'BON CASA',
                'coordX' => 130,
                'coordY' => 0,
                'font' => array('face' => 'Times New Roman', 'width' => '10', 'height' => '14', 'weight' => PRINTER_FW_BOLD, 'italic' => true, 'underline' => true )
                );
$toPrint[1] = array(
                'type' => 'line',
                'value' => 450,
                'coordX' => 0,
                'coordY' => 'add:25'
                );
$toPrint[2] = array(
                'type' => 'text',
                'value' => 'PRODUS',
                'coordX' => 0,
                'coordY' => 'add:1',
                );
                
$toPrint[3] = array(
                'type' => 'text',
                'value' => '| CANT ',
                'coordX' => 'add:220',
                'coordY' => 'equal',
                );

$toPrint[4] = array(
                'type' => 'text',
                'value' => '| PRET ',
                'coordX' => 'add:80',
                'coordY' => 'equal',
                );

$toPrint[5] = array(
                'type' => 'line',
                'value' => 450,
                'coordX' => 0,
                'coordY' => 'add:14',
                );
                
$toPrint[6] = array(
                'type' => 'line',
                'value' => 450,
                'coordX' => 0,
                'coordY' => 'add:70',
                );

$toPrint[7] = array(
                'type' => 'cut',
                'coordX' => 1,
                'coordY' => 1,
                );


$toPrint[8] = array(
                'type' => 'text',
                'value' => 'Total:',
                'coordX' => 0,
                'coordY' => 0,
                'font' => 'h1'
                );
$toPrint[9] = array(
                'type' => 'text',
                'value' => '350.00',
                'coordX' => 'add:150',
                'coordY' => 'equal',
                'font' => 'h1'
                );




$printJob = new Printer("NOTE");
$printJob -> printDoc($toPrint, "Bon Comanda");
