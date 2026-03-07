<?php
class RegistruCasa extends AbstractDB
{
    var $useTable = "registru_casa";
    var $primaryKey = "inregistrare_id";
    var $form = array();
    function __construct($mysql, $id = null)
    {
        parent::__construct($mysql, $id);
    }
}
