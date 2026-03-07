<?php
class GridView extends Helper
{
    var $rowOptions;
    var $cellOptions;
    var $tableOptions;
    var $dataTable;
    var $columns;
    
    function head($options = array())
    {
        $head = '<table %s>';
        $head = sprintf($head, $this -> tagElements($this -> tableOptions['tag']));
        if (isset($this -> columns)) {
            $txt = "";
            foreach ($this -> columns as $key => $column) {
                $width = isset($this -> tableOptions['colWidth'][$key]) ? $this -> tableOptions['colWidth'][$key] : '';
                $this -> append($txt, $this -> gridCell($column, array("width" => $width)));
            }
            $this -> append($head, $this -> gridRow($txt, $this -> tableOptions['head']));
        }
        return $head;
    }
    
    function gridCell($text, $options = array())
    {
        $txt = $this -> tagElements($options);
        return sprintf('<td %s>%s</td>', $txt, $text);
    }
    
    function content()
    {
        if (isset($this -> dataTable)) {
            $content = "";
            $i = 0;
            foreach ($this -> dataTable as $dataRow) {
                $row = "";
                foreach ($dataRow['data'] as $dataCell) {
                    $this -> append($row, $this -> gridCell($dataCell, $this -> cellOptions));
                }
                ($i%2==0) ? $this -> append($content, $this -> gridRow($row, $dataRow['tag'])) : $this -> append($content, $this -> gridRow($row, $dataRow['tag']));
                $i++;
            }
        }
        return $content;
    }
    
    function gridRow($text, $options = array())
    {
        $txt = $this -> tagElements($options);
        return sprintf('<tr %s>%s</tr>', $txt, $text);
    }
    
    function footer()
    {
        return '</table>';
    }
    function getTable()
    {
        $this -> append($txt, $this -> head());
        $this -> append($txt, $this -> content());
        $this -> append($txt, $this -> footer());
        return $txt;
    }
}
/*
$gv = new GridView;
$gv -> columns = array("Cod produs", "Denumire produs", "Pret produs");
$gv -> tableOptions['colWidth'] = array("100", "500", "100");
$gv -> tableOptions['head'] = array("class" => "rowHead");
$gv -> tableOptions['tag'] = array("width" => 700, "border" => 0, "cellspacing" => 0, "cellpadding"=>0);
$gv -> cellOptions = array("style"=>"border-bottom:1px solid #000000;");
$gv -> rowOptions[0] = array("style" => "border-bottom:1px solid #000000");
$gv -> rowOptions[1] = array("style" => "border-bottom:1px solid #000000");
$gv -> dataTable = array(
0 => array("data"=>array("produs_id" => "1", "denumire" => "Pantaloni barbatesti", "pret" => "15.55"), "tag" => array("onDblClick"=>"alert('row 1');")),
1 => array("data"=>array("2", "Mixer", "200.50"))
);
echo $gv -> head(NULL);
echo $gv -> content();
echo $gv -> footer();
*/
