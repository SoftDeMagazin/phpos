<?php
require("test.login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>BONURI CONSUM</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link type="text/css" rel="stylesheet" href="css/common.css" >
<link type="text/css" rel="stylesheet" href="css/config.produse.css">
<script type="text/javascript" src="js/ui/jquery-1.2.6.js"></script>
<style type="text/css">
<!--
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
#loading {

    padding: 20px;
    border: 0px solid green;
    display: none; /* hidden */
    position: absolute;    
    left: 50%;
    margin-left: -100px;
    top: 25%;
    width: 200px;
    /*height: 100px;*/
        /*margin-top: -50;*/
    font-weight: bold;
    font-size: large;
    }

-->
</style>
<?php
require_once("bonuriconsum.common.php");

require("config/config.php");
$xajax->printJavascript('thirdparty/xajax/');
?>

<?php
if ($cfgGui['loading']) {
    $load = new Loading();
    echo $load -> javaScript();
}
$tn = new TastaturaNumerica();
echo $tn -> printJavaScript();
$kb = new KeyBoard();
echo $kb -> printJavaScript();
$tabView = new TabView();
$tabView -> root = "";
echo $tabView -> printCss();
echo $tabView -> printJavaScript();
?>
</head>
<?php
/*         DB              */
$mysql = new MySQL();
?>
<Script Language=JavaScript>

function getRealLeft(el){
xPos = document.getElementById(el).offsetLeft;
tempEl = document.getElementById(el).offsetParent;
while (tempEl != null) {
xPos += tempEl.offsetLeft;
tempEl = tempEl.offsetParent;
}
return xPos;
}

function getRealTop(el){
yPos = document.getElementById(el).offsetTop;
tempEl = document.getElementById(el).offsetParent;
while (tempEl != null) {
yPos += tempEl.offsetTop;
tempEl = tempEl.offsetParent;
}
return yPos;
}

function getRealLeftSec(el){
xPos = el.offsetLeft;
tempEl = el.offsetParent;
while (tempEl != null) {
xPos += tempEl.offsetLeft;
tempEl = tempEl.offsetParent;
}
return xPos;
}

function getRealTopSec(el){
yPos = el.offsetTop;
tempEl = el.offsetParent;
while (tempEl != null) {
yPos += tempEl.offsetTop;
tempEl = tempEl.offsetParent;
}
return yPos;
}

function dispTruePos(isID){
trueX = getRealLeft(isID);
trueY = getRealTop(isID);
alert('True Xpos is: '+trueX+'nTrue Ypos is: '+trueY)
}

</Script>
<script type="text/javascript">
function fn_loadProdus(denumire, pret_vanzare)
    {
    xajax.$('div_denumire').innerHTML = denumire;
    xajax.$('div_pret').innerHTML = pret_vanzare;
    }
function fn_focus(strId)
    {
    document.getElementById(strId).focus();
    }   
</script>
<script type="text/javascript">
var OnKeyRequestBuffer = 
    {
        bufferText: false,
        bufferTime: 500,
        
        modified : function(strId)
        {
                setTimeout('OnKeyRequestBuffer.compareBuffer("'+strId+'","'+xajax.$(strId).value+'");', this.bufferTime);
        },
        
        compareBuffer : function(strId, strText)
        {
            if (strText == xajax.$(strId).value && strText != this.bufferText)
            {
                this.bufferText = strText;
                OnKeyRequestBuffer.makeRequest(strId);
            }
        },
        
        makeRequest : function(strId)
        {
            xajax_searchProduse(document.getElementById('txtDenumire').value);
        }
    }
</script>
<?php
echo '<body id="body" onLoad="">';
?>
<link rel="stylesheet" type="text/css" href="js/ui/themes/flora/flora.all.css">
<script type="text/javascript" src="js/ui/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/ui/ui.datepicker.js"></script>
<div id="main">
<div id="layout" style="width:1020px;">

<form action="" method="post" name="frmTransformare" id="frmTransformare" onSubmit="return false;">
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2"><strong>Bonuri consum </strong></td>
    </tr>
    <tr>
      <td colspan="2">
      <div id="lista_modificari" style="height:500px; overflow:auto">
      <?php
        $mysql = new MySQL();
        $mp = new BonuriConsum($mysql);
        $nr_r =  $mp -> find(array("order by data desc, numar_document desc"));
        if ($nr_r) {
                    $gv = new GridView();
                    $gv -> tableOptions['tag'] = array("width" => "100%", "border" => 0, "cellspacing" => 0, "cellpadding" => 0, "align" => "left");
                    $gv -> tableOptions['head'] = array("class" => "rowhead");
                    $gv -> columns = array("Data", "Numar Document", "Emis de");
                    $gv -> tableOptions['ColWidth'] = array();
            for ($i = 0; $i < $nr_r; $i++) {
                $obj = $mp -> objects[$i];
                $user = new Users($mysql, $obj -> user_id);
                $gv -> dataTable[$i]['data'] = array(date("d/m/Y", strtotime($obj -> data)), $obj -> numar_document, $user -> obj -> nume);
                if ($i % 2 == 0) {
                    $class = "roweven";
                } else {
                    $class = "rowodd";
                }

                $gv -> dataTable[$i]['tag'] = array("class" => $class,
                "onMouseOver" => "$(this).addClass('rowhover')",
                "onMouseOut" => "$(this).removeClass('rowhover')",
                "onClick" => "window.location.href = 'print.bonconsum.php?bon_consum_id=" . $obj -> bon_consum_id . "'"
                );
            }
            $d = $gv -> getTable();
        } else {
            $d = "NU SUNT BONURI CONSUM EMISE IN ACEASTA PERIOADA";
        }
        echo $d;
        ?>
      
      </div>
      </td>
    </tr>
    <tr>
      <td width="50%"><div align="center">
        <input name="btnIesire" type="button" id="btnIesire" onClick="window.location.href = 'login.php'" value="IESIRE"> 
        </div></td>
      <td width="50%"><div align="center">
        </div></td>
    </tr>
  </table>
</form>
</div>
<div id="overlay">
</div>
<div id="obiecte" class="flora" style=" ">
</div>
</div>
<?php
if ($cfgGui['loading']) {
    $load = new Loading();
    echo $load -> div();
}
?>
<div id="windows">
</div>
</body>
</html>
