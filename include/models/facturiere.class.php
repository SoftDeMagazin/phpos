<?php
class Facturiere extends AbstractDB
{
    var $useTable="facturiere";
    var $primaryKey="facturier_id";
    var $form = array();
    
    function __construct($mysql, $id = null)
    {
        parent::__construct($mysql, $id);
    }
}
