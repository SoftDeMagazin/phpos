<?php
class ZileEconomice extends AbstractDB
{
    var $useTable = "zile_economice";
    var $primaryKey = "zi_economica_id";

    function __construct($mysql, $id = NULL)
    {
        parent::__construct($mysql, $id);
    }

    function getLastDay()
    {
        $this->findLast(array("WHERE", "inchis" => " = 'NU'"));
    }

    function closeDay($user_id)
    {
        $this->getLastDay();
        $closed = $this->obj;
        
        $this->setObjValue("inchis", "DA");
        $this->setObjValue("ora_inchidere", date("H:i:s"));
        $this->setObjValue("user_id", $user_id);
        $this->save();

        $this->resetObj();
        $this->setObjId(0);

        // Folosim DateTime pentru a adăuga o zi și a evita problemele de fus orar
        $date = new DateTime($closed->data);
        $date->modify('+1 day');
        $this->setObjValue("data", $date->format('Y-m-d'));

        $this->setObjValue("inchis", "NU");
        $this->save();

        $this->getLastDay();
        return $closed;
    }
}
?>
