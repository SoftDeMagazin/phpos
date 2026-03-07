<?php
require_once("tastaturaNumerica.class.php");
require_once("keyBoard.class.php");
require_once("calendar.class.php");
class Loading
{
    function javaScript()
    {
        return '
		<script type="text/javascript">
  xajax.callback.global.onRequest = function() {xajax.$(\'loading\').style.display = \'block\';}
  xajax.callback.global.beforeResponseProcessing = function() {xajax.$(\'loading\').style.display=\'none\';}
</script>
		';
    }
    function div()
    {
        return '<div id="loading" align="center"><img src="files/img/loadingev4.gif"></div>';
    }
}
function calPopup($luna = null, $an = null, $dest = null, $window = "")
{
    $cal = new Calendar;
    $window = "datepicker".time();
    $objResponse = window($window, $cal -> genereazaCalendar($luna, $an, $dest, $window), 370, 370);
    return $objResponse;
}

function calChangeDate($luna = null, $an = null, $dest = null, $window)
{
    $objResponse = new xajaxResponse();
    $cal = new Calendar;
    $objResponse -> assign("window-content-".$window, "innerHTML", $cal -> genereazaCalendar($luna, $an, $dest, $window));
    return $objResponse;
}

function tnPopup($dest, $focus)
{
    $tn = new TastaturaNumerica;
    $objResponse = afisareDialog($tn -> printHTML($dest, $focus), "240px", "300px", "400px", "150px", null);
    return $objResponse;
}

function kbPopup($dest, $focus = '')
{
    $kb = new KeyBoard;
    $objResponse = afisareDialog($kb -> printHTML($dest, $focus), "750px", "350px", "100px", "150px", null);
    return $objResponse;
}


function afisareDialog($innerHTML, $width = "600px", $height = "300px;", $left = "200px", $top = "150px", $btnOk = "Ok")
{
    $txt = "";
    $txt = $txt.'
	<div id="objInfo" style="filter: alpha(opacity=100); opacity:1; position: relative; width:'. $width .'; height:'. $height .'; z-index:500; left: '. $left .'; top: '. $top .'; display:block; background-color: #CCFFFF; layer-background-color: #CCFFFF; border: 1px solid #000000;">
  	<div style="width:100%; text-align:right; background-image:url(i/dialog-title.gif); border-bottom:1px solid #000"><img src="i/dialog-titlebar-close.png" onClick="xajax_btnRenuntaDialog()"></div>
	<div>
	';
    $txt = $txt.$innerHTML;

    $txt = $txt.'
	</div>	
	  <div align="center" style="margin-top:10px;">';
     
    if (!empty($btnOk)) {
        $txt .= '<input name="btnRenunta" type="button" class="dialog_exit" value="'. $btnOk .'"  onClick="xajax_btnRenuntaDialog();">';
    }
     $txt .= '</div>
	</div>
	';
    $objResponse = new xajaxResponse();
    $objResponse -> assign("obiecte", "innerHTML", $txt);
    $objResponse -> assign("overlay", "style.display", "block");
    $objResponse -> assign("obiecte", "style.display", "block");
    return $objResponse;
}

function window($name, $content, $width = 200, $height = 200, $modal = true, $append = true)
{
    $mleft = $width/2;
    $mtop =  $height/2;
    $txt = '
	<div id="'. $name .'" style="position:absolute; margin-left:-'.$mleft.'px; width:'.$width.'px;margin-top:-'.$mtop.'px;height:'.$height.'px;  border:1px solid #000; left:50%; top:50%; padding:0px; background-color: #CCCCCC;" >
	<div style="width:100%; text-align:right; background-image:url(i/dialog-title.gif); border-bottom:1px solid #000"><img src="i/dialog-titlebar-close.png" onClick="xajax_close_window(\''.$name.'\')"></div>
	<div id="window-content-'.$name.'" style="padding:5px;">'.$content.'
	</div>
	</div>
	';
    $objResponse = new xajaxResponse();
    $objResponse -> append("windows", "innerHTML", $txt);
    return $objResponse;
}
function close_window($name)
{
    $objResponse = new xajaxResponse();
    $objResponse -> assign("window-content-".$name, "innerHTML", "");
    $objResponse -> assign($name, "style.visibility", "hidden");
    return $objResponse;
}
    
function btnRenuntaDialog()
{
    $objResponse = new xajaxResponse();
    $objResponse -> assign("obiecte", "innerHTML", "");
    $objResponse -> assign("overlay", "style.display", "none");
    $objResponse -> assign("obiecte", "style.display", "none");
    return $objResponse;
}


/* jquery windows
function afisareDialog($innerHTML, $width="600px", $height="300px", $left="200px", $top="150px", $btnOk="Ok")
{

    $w = explode("px", $width);
    $new = $w[0]+20;
    $width = "".$new."px";
    $h= explode("px", $height);
    $new = $h[0]+30;
    $height= "".$new."px";
    $txt = "";
    $txt = $txt.'
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
    ';
    $txt = $txt.$innerHTML;

    $txt = $txt.'
       </td>
      </tr>
    <tr>
      <td>
      <div align="center" style="margin-top:10px;">';
     if(!empty($btnOk))$txt .= '<input name="btnRenunta" type="button" class="dialog_exit" value="'. $btnOk .'"  onClick="xajax_btnRenuntaDialog();">';
     $txt .= '</div>
      </td>
    </tr>
  </table>
    ';
    $objResponse = new xajaxResponse();
    $objResponse -> assign("obiecte", "innerHTML", $txt);
    //$objResponse -> assign("overlay", "style.display", "block");
    $objResponse -> script(" $('#obiecte').dialog(\"close\");");
    $objResponse -> assign("obiecte", "style.display", "block");
    $objResponse -> script("
        $('#obiecte').dialog({
    modal: false,
    resizable: true,
    width: \"$width\",
    height: \"$height\",
    resizable: false
})
    ");

    return $objResponse;
}

function btnRenuntaDialog()
    {
    $objResponse = new xajaxResponse();
    $objResponse -> assign("obiecte", "innerHTML", "");
    $objResponse -> script(" $('#obiecte').dialog(\"close\");");
    return $objResponse;
    }
*/

if (isset($registerFunctions) && $registerFunctions == true) {
    $xajax -> registerFunction("window");
    $xajax -> registerFunction("close_window");
    $xajax -> registerFunction("afisareDialog");
    $xajax -> registerFunction("btnRenuntaDialog");
    $xajax -> registerFunction("tnPopup");
    $xajax -> registerFunction("kbPopup");
    $xajax -> registerFunction("calPopup");
    $xajax -> registerFunction("calChangeDate");
}
