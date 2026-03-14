<?php
class CaseFiscale extends AbstractDB
{
    /*  */
    var $useTable = "case_fiscale";
    var $primaryKey = "casa_id";
    var $form = array(
    "casa_id" => array(
        "input" => array("type" => "hidden"),
        "label" => false
        ),
    "serie_fiscala" => array(
        "input" => array("type" => "text", "size" => 25),
        "label" => "Serie Fiscala"
        ),
    "id" => array(
        "input" => array("type" => "text", "size" => 15),
        "label" => "Id Casa"
        ),
    "nume_casa" => array(
        "input" => array("type" => "text", "size" => 25),
        "label" => "Nume Casa"
        ),
    "tip_casa" => array(
        "input" => array("type" => "select"),
        "data_source" => array(
            "FiscalDatecs" => "Datecs",
            "FiscalNet" => "Net",
            "FiscalZeka" => "Zeka",
            "FiscalElka" => "Elka",
            "FiscalSapel" => "Sapel"
        ),
        "label" => "Tip Casa Fiscala"
        ),
    "cale_fisiere" => array(
        "input" => array("type" => "text", "size" => 50),
        "label" => "Cale Fisiere"
        ),
    );
    /* form processing */
    function __construct($mysql, $id = null)
    {
        parent::__construct($mysql, $id);
    }

    function saveForm($frmValues)
    {
        if (isset($frmValues['cale_fisiere'])) {
            $frmValues['cale_fisiere'] = str_replace('\\', '/', $frmValues['cale_fisiere']);
        }
        parent::saveForm($frmValues);
    }
}
