<?php
class Clienti extends AbstractDB
{
	var $useTable="clienti";
	var $primaryKey="client_id";
	var $form = array();
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function frmClient()
		{
		$out = '<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="28%"><strong>Denumire</strong>
    <input name="client_id" type="hidden" id="client_id" value="'. $this -> obj -> client_id .'"></td>
    <td width="72%"><input name="denumire" type="text" id="denumire" size="40" value="'. $this -> obj -> denumire .'"></td>
  </tr>
  <tr>
    <td><strong>Nr reg com </strong></td>
    <td><input name="reg_com" type="text" id="reg_com" size="40" value="'. $this -> obj -> reg_com .'"></td>
  </tr>
  <tr>
    <td><strong>Cod Fiscal </strong></td>
    <td><input name="cif" type="text" id="cif" value="'. $this -> obj -> cif .'"></td>
  </tr>
  <tr>
    <td><strong>Sediul</strong></td>
    <td><textarea name="sediul" cols="40" id="sediul">'. $this -> obj -> sediul .'</textarea></td>
  </tr>
  <tr>
    <td><strong>Judetul</strong></td>
    <td><input name="judetul" type="text" id="judetul" size="40" value="'. $this -> obj -> judetul .'"></td>
  </tr>
  <tr>
    <td><strong>Cont</strong></td>
    <td><input name="contul" type="text" id="contul" size="45" value="'. $this -> obj -> contul .'"></td>
  </tr>
  <tr>
    <td><strong>Banca</strong></td>
    <td><input name="banca" type="text" id="banca" size="45" value="'. $this -> obj -> banca .'"></td>
  </tr>
</table>
';
return $out;
		}
	
	function displayClient()
		{
		$out = '<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="28%"><strong>Denumire</strong>
    <input name="client_id" type="hidden" id="client_id" value="'. $this -> obj -> client_id .'"></td>
    <td width="72%">'. $this -> obj -> denumire .'</td>
  </tr>
  <tr>
    <td><strong>Nr reg com </strong></td>
    <td>'. $this -> obj -> reg_com .'</td>
  </tr>
  <tr>
    <td><strong>Cod Fiscal </strong></td>
    <td>'. $this -> obj -> cif .'</td>
  </tr>
  <tr>
    <td><strong>Sediul</strong></td>
    <td>'. $this -> obj -> sediul .'</td>
  </tr>
  <tr>
    <td><strong>Judetul</strong></td>
    <td>'. $this -> obj -> judetul .'</td>
  </tr>
  <tr>
    <td><strong>Cont</strong></td>
    <td>'. $this -> obj -> contul .'</td>
  </tr>
  <tr>
    <td><strong>Banca</strong></td>
    <td>'. $this -> obj -> banca .'</td>
  </tr>
</table>
';
return $out;
		}	

	function salveazaClient($frmValues)
		{
		$this -> tableToForm();
		$this -> saveForm($frmValues);
		}				

}
?>