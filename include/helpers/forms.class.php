<?php
class Forms extends Helper
{
    var $form;
    var $fields;
    var $data;
    var $output=null;
    
    function create($name, $options = null)
    {
        $txt = '';
        $this -> append($txt, '<form name="'. $name .'" id="'. $name .'" ');
        $action = $options['action'];
        $method = $options['method'];
        $onSubmit = isset($options['onsubmit']) ? $options['onsubmit'] : null;
        
        $this -> append($txt, ' action="'. $action .'" ');
        if (!empty($method)) {
            $this -> append($txt, ' method="'. $method .'" ');
        }
        if (!empty($onSubmit)) {
            $this -> append($txt, ' onSubmit="'. $onSubmit .'" ');
        }
        $this -> append($txt, ' >');
        return $txt;
    }
    
    function input($name, $options = null)
    {
        if (!array_key_exists('type', $options)) {
            $options['type'] = 'text';
        }
        if (array_key_exists('options', $options)) {
            $options['type'] = 'select';
        }
        $txt = "";
        switch ($options['type']) {
            case "text":
                {
                $this -> append($txt, '<input type="text"');
                $this -> append($txt, ' name="'. $name .'" id="'.$name.'"');
                $this -> append($txt, ' type="text"');
                $this -> append($txt, ' value="'. $options['value'] .'"');
                $this -> append($txt, ' size="'. $options['size'] .'"');
                $this -> append($txt, '/>');
            }break;
            case "hidden":
                {
                $this -> append($txt, '<input type="hidden"');
                $this -> append($txt, ' name="'. $name .'" id="'.$name.'"');
                $this -> append($txt, ' type="text"');
                $this -> append($txt, ' value="'. $options['value'] .'"');
                $this -> append($txt, '/>');
            }break;
            case "textarea":
                {
                $this -> append($txt, '<textarea');
                $this -> append($txt, ' name="'. $name .'" id="'. $name .'"');
                (!empty($options['cols']) && isset($options['cols'])) ? $this -> append($txt, ' cols="'. $options['cols'] .'"'):'';
                (!empty($options['rows']) && isset($options['rows'])) ? $this -> append($txt, ' rows="'. $options['rows'] .'"'):'';
                $this -> append($txt, '>');
                $this -> append($txt, ''. $options['value'] .'');
                $this -> append($txt, '</textarea>');
            }break;
            case "radiogroup":
                {
            }break;
            case "select":
                {
                $this -> append($txt, '<select ');
                $this -> append($txt, 'name="'. $name .'" id="'.$name.'">');
                
                $array_vars = array_keys($options['options']);
                if (isset($array_vars)) {
                    foreach ($array_vars as $key) {
                        $this -> append($txt, '<option value="'. $key .'"');
                        if (!empty($options['selected']) && isset($options['selected']) && $options['selected'] == $key) {
                            $this -> append($txt, 'selected');
                        }
                        $this -> append($txt, '>');
                        $this -> append($txt, $options['options'][$key]);
                        $this -> append($txt, '</option>');
                    }
                }
                $this -> append($txt, '</select>');
            }break;
        }
        return $txt;
    }
    
    function formEndButton($submit = "Salveaza")
    {
        return '
		<br/>
		<input name="btnSaveForm" id="btnSaveForm" type="submit" value="'. $submit .'">
		</form>';
    }

    function formEnd()
    {
        return '
		</form>';
    }
}
