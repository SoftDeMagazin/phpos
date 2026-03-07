<?php
class BonuriConsum extends AbstractDB
{
    /*  */
    var $useTable = "bonuri_consum";
    var $primaryKey = "bon_consum_id";
    var $form = array();

    /* form processing */
    function adaugaBonConsum($comanda_id)
    {
        $row = $this -> mysql -> getRow("SELECT MAX(numar_document) as nr from bonuri_consum");
        $this -> obj -> bon_consum_id = 0;
        $this -> obj -> data_ora = date("Y-m-d H:i:s");
        $this -> obj -> data = date("Y-m-d");
        $this -> obj -> numar_document = $row['nr']+1;
        $this -> obj -> user_id = $_SESSION['USERID'];
        $this -> save();
        
        $this -> mysql -> query("
		insert into bonuri_consum_continut (
		bon_consum_id, produs_id, cantitate)
		(select '". $this -> obj -> bon_consum_id ."' as bon_consum_id, produs_id, cantitate from comenzi_continut
		 where comanda_id = '$comanda_id')");
    }
        
    function scadereProduse()
    {
        $continut = new BonuriConsumContinut($this -> mysql);
        $nr_r = $continut -> findAllBy("bon_consum_id", $this -> obj -> bon_consum_id);
        if ($nr_r) {
            foreach ($continut -> objects as $obj) {
                $produs = new Produse($this -> mysql, $obj -> produs_id);
                $produs -> scadereStoc($obj -> cantitate, $obj -> bon_consum_continut_id, "bon_consum");
            }
        }
    }
        
    function printDoc()
    {
        $out = '<h3 align="center">Bon consum nr. '. $this -> obj -> numar_document .'</h3>';
        $out .='<div align="center">Data: '. date('d/m/Y H:i:s', strtotime($this -> obj -> data_ora)) .'</div>';
        $mp = new IesiriBonConsum($this -> mysql);
        $nr_r =  $mp -> find(array("where bon_consum_id = '". $this -> obj -> bon_consum_id ."'"));
        if ($nr_r) {
            $total = 0;
                    $gv = new GridView;
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding"=>0, "align"=>"left", "id" => "componente");
                    $gv -> tableOptions['head'] = array("class"=> "rowhead");
                    $gv -> columns = array("Articol", "Pret achizitie", "Cantitate", "Valoare");
                    $gv -> tableOptions['ColWidth'] = array();
            for ($i=0; $i<count($mp -> objects); $i++) {
                $obj = $mp -> objects[$i];
                $gv -> dataTable[$i]['data'] = array($obj -> denumire, $obj -> pret_intrare, $obj -> cantitate, number_format($obj -> pret_intrare*$obj -> cantitate, 2, '.', ''));
                if ($i%2==0) {
                    $class = "";
                } else {
                    $class = "";
                }
                $total += number_format($obj -> pret_intrare*$obj -> cantitate, 2, '.', '');
            
                $gv -> dataTable[$i]['tag'] = array(
                );
            }
            $gv -> dataTable[$i]['data'] = array("Total", "&nbsp;", "&nbsp;", $total);
            $d = $gv -> getTable();
        } else {
            $d = "";
        }
        $out .= $d;
        return $out;
    }
        
    function BonuriConsum($mysql, $id = null)
    {
        $this -> AbstractDB($mysql, $id);
    }
}

class IesiriBonConsum extends AbstractDB
{
    /*  */
    var $useTable = "iesiri_bon_consum";
    var $primaryKey = "iesire_id";
    var $form = array();

    /* form processing */
    
    function IesiriBonConsum($mysql, $id = null)
    {
        $this -> AbstractDB($mysql, $id);
    }
}
