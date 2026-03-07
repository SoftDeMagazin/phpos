<?php
/* uses helper.class.php, html.class.php */
/* genereaza lista orizontala de taburi */

class TabView extends Helper
{
    var $v1='
<div id="tabView" style="width:<%tabViewWidth%>px;height:<%tabViewHeight%>px;">
  <div id="tabViewContent" style="width:<%tabViewContentWidth%>px; height:<%tabViewHeight%>px;overflow:hidden; float:left;">
  <%tabViewContent%>
  </div>
  <div id="tabViewNav" style="width:90px; float:right;">
    <input name="btnTabViewLeft" type="button" id="btnTabViewLeft" value=" " onClick="tabViewScroll(-<%tabViewScroll%>)" onDblClick="tabViewScroll(-<%tabViewScroll%>)"><input name="btnTabViewRight" type="button" id="btnTabViewRight" value=" " onClick="tabViewScroll(<%tabViewScroll%>)" onDblClick="tabViewScroll(<%tabViewScroll%>)">
  </div>
</div>
	';
    
    var $v2='
<div id="tabView" style="width:<%tabViewWidth%>px;height:<%tabViewHeight%>px;">
  <div id="tabViewNav" style="width:45px; float:left;">
    <input name="btnTabViewLeft" type="button" id="btnTabViewLeft" value=" " onClick="tabViewScroll(-<%tabViewScroll%>)" onDblClick="tabViewScroll(-<%tabViewScroll%>)">
  </div>
  <div id="tabViewContent" style="width:<%tabViewContentWidth%>px; height:<%tabViewHeight%>px;overflow:hidden; float:left; margin-left:4px;">
  <%tabViewContent%>
  </div>
  <div id="tabViewNav" style="width:45px; float:right;">
    <input name="btnTabViewRight" type="button" id="btnTabViewRight" value=" " onClick="tabViewScroll(<%tabViewScroll%>)" onDblClick="tabViewScroll(<%tabViewScroll%>)">
  </div>
</div>
	';
    var $root="../../";
    function printJavaScript()
    {
        return '
		<script type="text/javascript" language="javascript">
			function tabViewScroll(amount) {
				var objDiv = document.getElementById("tabViewContent");
				objDiv.scrollLeft = objDiv.scrollLeft + amount;
				}
		</script>
		';
    }
    
    function printCss()
    {
        return '
		<style type="text/css">
			<!--
			#tabViewContent button {
			padding: 0px 0px 0px 0px; 
			margin:0px 0px 0px 0px; 
			float:left;
			text-align:left;
			}
#btnTabViewLeft {height: 30px; width: 40px; background-image:url('.$this -> root.'files/img/go-previous.png); background-repeat:no-repeat; background-position:center;}
#btnTabViewRight {height: 30px; width:40px; background-image:url('.$this -> root.'files/img/go-next.png); background-repeat:no-repeat; background-position:center;}
			-->
		</style>
		';
    }
    
    function printTabView($options = array(), $v = 1)
    {
        if ($v == 1) {
            $txt = $this -> v1;
        } else {
            $txt = $this -> v2;
        }
        $this -> replace($txt, '<%tabViewWidth%>', $options['width']);
        $this -> replace($txt, '<%tabViewHeight%>', $options['height']);
        $this -> replace($txt, '<%tabViewContentWidth%>', $options['width']-100);
        $this -> replace($txt, '<%tabViewScroll%>', $options['scroll']);
        $this -> replace($txt, '<%tabViewContent%>', $this -> tabViewContent($options['content']));
        return $txt;
    }
    
    function tabViewContent($contents = array())
    {
        $html = new Html;
        $txt = '<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr>';
        foreach ($contents as $content) {
            $this -> append($txt, '<td>'. $html -> buttonTag($content) .'</td>');
        }
        $this -> append($txt, '</tr></table>');
        return $txt;
    }
}
