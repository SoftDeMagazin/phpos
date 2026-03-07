<?php
class Inventar extends AbstractDB
{
    var $useTable="inventar";
    var $primaryKey="inventar_id";
    var $form = array();
    
    function deschideInventar()
    {
        $row = $this -> mysql -> getRow("SELECT MAX(numar_inventar) as max_nr FROM ". $this -> useTable ."");
        $frm['numar_inventar'] = $row['max_nr']+1;
        $frm['inventar_id'] = 0;
        $frm['data'] = date("Y-m-d");
        $frm['user_id'] = $_SESSION['USERID'];
        $frm['calculat'] = "NU";
        $this -> tableToForm();
        $this -> saveForm($frm);
    }
    
    function addComponenta($frmValues)
    {
        $frmValues['inventar_id'] = $this -> obj -> inventar_id;
        $frmValues['inventar_componenta_id'] = 0;
        $componenta = new InventarContinut($this -> mysql);
        $componenta -> tableToForm();
        $componenta -> saveForm($frmValues);
    }
    
    
    function continut()
    {
        $continut = new ViewInventarContinut($this -> mysql);
        $continut -> findAllBy("inventar_id", $this -> obj -> inventar_id);
        if (isset($continut -> objects)) {
            $txt = '<table width="550" cellspaceing="0" cellpadding="0">
			<tr class="rowhead">
		<td width="200">DENUMIRE PROUS</td>
		<td width="150">STOC SCRIPTIC</td>
		<td width="150">STOC FAPTIC</td>
		<td width="50">STERGE</td>
		<td width="50">EDIT</td>
		</tr>';
            $i=0;
            foreach ($continut -> objects as $obj) {
                $continut -> obj = $obj;
                if ($i%2==0) {
                    $class = "roweven";
                } else {
                    $class = "rowodd";
                }
                if ($this -> obj -> calculat == 'DA') {
                    $txt .= $continut -> afisare($class, false);
                } else {
                    $txt .= $continut -> afisare($class);
                }
                $i++;
            }
            $txt .= '</table>';
        }
        return $txt;
    }
    
    function recalculare()
    {
        $sql = "
		INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, pret_vanzare, activ, tip, data)
		(select '".$this -> obj -> inventar_id."' as nir_id, inventar_continut_id, produs_id, stoc_faptic - stoc_scriptic as cantitate, stoc_faptic - stoc_scriptic as cantitate_ramasa, 0,produse.pret as pret_vanzare, 1, 'diferenta' as tip, '". $this -> obj -> data ."' as data
		from inventar_continut 
		inner join produse using(produs_id)
		where stoc_faptic > stoc_scriptic and inventar_id = '". $this -> obj -> inventar_id ."'
		) 
		";
        $this -> mysql -> query($sql);
        $continut = new ViewInventarContinut($this -> mysql);
        $nr_r = $continut -> find(array("where stoc_faptic < stoc_scriptic and inventar_id = '". $this -> obj -> inventar_id ."'"));
        if (isset($continut -> objects)) {
            foreach ($continut -> objects as $objContinut) {
                $produs = new Produse($this -> mysql, $objContinut -> produs_id);
                $produs -> scadereStoc($objContinut -> stoc_scriptic - $objContinut -> stoc_faptic, $objContinut -> inventar_continut_id, "inventar");
            }
        }
        $this -> obj -> calculat = "DA";
        $this -> save();
    }
    
    /*
    function recalculare()
        {
        $continut = new ViewInventarContinut($this -> mysql);
        $continut -> findAllBy("inventar_id", $this -> obj -> inventar_id);
        if(isset($continut -> objects))
        {
            foreach($continut -> objects as $objContinut)
                {
                $diferenta = $objContinut -> stoc_faptic - $objContinut -> stoc_scriptic;
                if($diferenta > 0)
                    {
                        $sql = "
                        INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, activ, tip, data)
                               VALUES ('". $this -> obj -> inventar_id ."', '". $objContinut -> inventar_continut_id ."', '". $objContinut -> produs_id ."', '". $diferenta ."', '". $diferenta ."', 0, 1, 'diferenta', '". $this -> obj -> data ."');
                        ";
                        $this -> mysql -> query($sql);
                    }
                else if($diferenta < 0)
                    {
                        $sql = "
                        INSERT INTO intrari_continut (nir_id, nir_componenta_id, produs_id, cantitate, cantitate_ramasa, pret_intrare, activ, tip, data)
                               VALUES ('". $this -> obj -> inventar_id ."', '". $objContinut -> inventar_continut_id ."', '". $objContinut -> produs_id ."', '0', '". $diferenta ."', 0, 1, 'ajustare', '". $this -> obj -> data ."');
                        ";
                        $this -> mysql -> query($sql);
                    }
                }
        }
        $this -> obj -> calculat = "DA";
        $this -> save();
        }
    */
    
    function stergeInventar()
    {
        $this -> mysql -> query("DELETE FROM inventar WHERE inventar_id = '". $this -> obj -> inventar_id ."'");
        $this -> mysql -> query("DELETE FROM inventar_continut WHERE inventar_id = '". $this -> obj -> inventar_id ."'");
        $this -> mysql -> query("DELETE FROM intrari_continut WHERE nir_id = '". $this -> obj -> inventar_id ."' and (tip='diferenta' or tip='ajustare')");
    }
    
    function verificaProdus($produs_id)
    {
        $row = $this -> mysql -> getRow("SELECT inventar_continut_id FROM inventar_continut WHERE inventar_id = '". $this -> obj -> inventar_id ."' AND produs_id = '$produs_id'");
        if (!$row) {
            return 0;
        } else {
            return $row['inventar_continut_id'];
        }
    }
}

class ViewInventarContinut extends AbstractDB
{
    var $useTable="view_inventar_continut";
    var $primaryKey="inventar_continut_id";
    var $form = array();
    
    function afisare($class = '', $sterg = true)
    {
        if ($sterg == true) {
            $btnSterg = '<div align="center"><a href="#" onClick="xajax_removeComponenta('. $this -> obj -> inventar_continut_id .');return false;"><img src="files/img/gtk-close.png" width="24" height="24"></a></div>';
            $btnEdit = '<div align="center"><a href="#" onClick="xajax_editComponenta('. $this -> obj -> inventar_continut_id .');return false;"><img src="files/img/gtk-close.png" width="24" height="24"></a></div>';
        } else {
            $btnSterg='&nbsp;';
            $btnEdit='&nbsp;';
        }
        $out = '<tr class="'. $class .'">
		<td>'. $this -> obj -> denumire .'</td>
		<td>'. $this -> obj -> stoc_scriptic .'</td>
		<td>'. $this -> obj -> stoc_faptic .'</td>
		<td>'. $btnSterg .'</td>
		<td>'. $btnEdit .'</td>
		</tr>
		';
        return $out;
    }
}


class InventarContinut extends AbstractDB
{
    var $useTable="inventar_continut";
    var $primaryKey="inventar_continut_id";
    var $form = array();
    
    function afisare($class = '', $sterg = true)
    {
        $produs = new Produse($this -> mysql, $this -> obj -> produs_id);
        if ($sterg == true) {
            $btnSterg = '<div align="center"><a href="#" onClick="xajax_removeComponenta('. $this -> obj -> inventar_continut_id .');return false;"><img src="files/img/gtk-close.png" width="24" height="24"></a></div>';
            $btnEdit = '<div align="center"><a href="#" onClick="xajax_editComponenta('. $this -> obj -> inventar_continut_id .');return false;"><img src="files/img/gtk-close.png" width="24" height="24"></a></div>';
        } else {
            $btnSterg='&nbsp;';
            $btnEdit='&nbsp;';
        }
        $out = '<tr class="'. $class .'">
		<td>'. $produs -> obj -> denumire .'</td>
		<td>'. $this -> obj -> stoc_scriptic .'</td>
		<td>'. $this -> obj -> stoc_faptic .'</td>
		<td>'. $btnSterg .'</td>
		<td>'. $btnEdit .'</td>
		</tr>
		';
        return $out;
    }
    
    function editareComponenta()
    {
        $produs = new Produse($this -> mysql, $this -> obj -> produs_id);
            $txt = '
			<form id="frmEditComponenta" action="" method="post" name="frmEditComponenta">
			<table width="550" cellspaceing="0" cellpadding="0">
			<tr class="rowhead">
		<td width="200">DENUMIRE PROUS</td>
		<td width="150">STOC SCRIPTIC</td>
		<td width="150">STOC FAPTIC</td></tr>
		<tr class="'. $class .'">
		<td>'. $produs -> obj -> denumire .'<input type="hidden" id="inventar_continut_id" value="'. $this -> obj -> inventar_continut_id .'" name="inventar_continut_id"></td>
		<td>'. $this -> obj -> stoc_scriptic .'</td>
		<td><input type="text" value="'. $this -> obj -> stoc_faptic .'" name="stoc_faptic" id="stoc_faptic"></td>	</tr>
		</table>
		<div align="center"><input name="btnSaveEditComponenta" type="button" value="SALVEAZA" onClick="xajax_saveComponenta(xajax.getFormValues(\'frmEditComponenta\'), xajax.getFormValues(\'frmInventar\'));" class="btnTouch"></div>
		</form>
		';
        return $txt;
    }
}
