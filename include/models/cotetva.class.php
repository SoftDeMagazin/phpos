<?php
class CoteTva extends AbstractDB
{
    var $useTable = "cotetva";
    var $primaryKey = "cotatva_id";
    function __construct($mysql, $id = null)
    {
        parent::__construct($mysql, $id);
    }
}
