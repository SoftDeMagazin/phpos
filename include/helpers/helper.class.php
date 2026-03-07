<?php
class Helper
{
    function append(&$txt, $text)
    {
        $txt .= ''.$text;
    }
    
    function replace(&$unde, $cum, $ce)
    {
        $unde = str_replace($cum, $ce, $unde);
    }
    
    function tagElements($options = array())
    {
        $txt = '';
        if (isset($options)) {
            foreach ($options as $key => $option) {
                if (!is_numeric($key)) {
                    $txt .= ' '.$key.'="'.$option.'"';
                } else {
                    $txt .= " ".$option;
                }
            }
        }
        return $txt;
    }
}
