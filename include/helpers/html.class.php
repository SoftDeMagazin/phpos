<?php
class Html extends Helper
{
    function __construct()
    {
    }
    var $tags = array(
    "option" => "<option %s>%s</option>"
    );
        
    function button($options = array())
    {
        $txt = "";
        $this -> append($txt, '<input type="button"');
        if (isset($options)) {
            foreach ($options as $key => $option) {
                if (!is_numeric($key)) {
                    $this -> append($txt, ''. $key .'="'. $option .'"');
                    if ($key == "name") {
                        $this -> append($txt, 'id="'. $option .'"');
                    }
                } else {
                    $this -> append($txt, $option);
                }
            }
        }
        $this -> append($txt, '>');
        return $txt;
    }
    
    function buttonTag($options = array())
    {
        $txt = "";
        $this -> append($txt, '<button ');
        if (isset($options)) {
            foreach ($options as $key => $option) {
                if (!is_numeric($key)) {
                    if ($key != "value") {
                        $this -> append($txt, ''. $key .'="'. $option .'"');
                        if ($key == "name") {
                            $this -> append($txt, 'id="'. $option .'"');
                        }
                    }
                } else {
                    $this -> append($txt, $option);
                }
            }
        }
        $this -> append($txt, '>');
        $this -> append($txt, $options['value']);
        $this -> append($txt, '</button>');
        return $txt;
    }
}
