<?php
class Facturi extends AbstractDB
{
	var $useTable="facturi";
	var $primaryKey="factura_id";
	var $form = array();
	
	function __construct($mysql,$id=NULL)
		{
		parent::__construct($mysql, $id);
		}
	
	function  preview()
		{
		$bon = new Bonuri($this -> mysql, $this -> obj -> bon_id);
		$client = new Clienti($this -> mysql, $this -> obj -> client_id);
		require("config/date_firma.php");
$out = '<table width="100%"  border="1" cellspacing="3" cellpadding="0" style="margin-top:20px; ">
        <tr>
          <td width="30%" valign="top">'. $dateFirma .'</td>
          <td><table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2">
              <tr>
                <td width="28%" bgcolor="#999999"><strong>Serie</strong></td>
                <td width="72%"><strong>
               '. $this ->  obj -> serie.'
                </strong></td>
              </tr>
              <tr>
                <td bgcolor="#999999"><strong>Nr</strong></td>
                <td><strong>'. $this ->  obj -> numar.'</strong></td>
              </tr>
              <tr>
                <td bgcolor="#999999"><strong>Data</strong></td>
                <td><strong>
                 '. date("d/m/Y", strtotime($this ->  obj -> data)).'
                </strong></td>
              </tr>
          </table></td>
          <td width="35%">
		  '. $client -> displayClient() .'
          </td>
        </tr>
        <tr>
          <td colspan="3">'. $bon -> bonContinutFactura() .'</td>
        </tr>
        <tr>
          <td colspan="3"><strong>Emis De:</strong></td>
        </tr>
        <tr>
          <td colspan="3"><table width="100%"  border="1" cellspacing="2" cellpadding="2">
              <tr>
                <td width="30%" height="150" valign="top">Semnatura si stampila furnizorului </td>
                <td width="35%"><table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2">
                    <tr>
                      <td width="35%" bgcolor="#999999"><strong>Nume delegat </strong></td>
                      <td width="65%"><strong></strong></td>
                    </tr>
                    <tr>
                      <td bgcolor="#999999"><strong>CNP</strong></td>
                      <td><strong></strong></td>
                    </tr>
                    <tr>
                      <td bgcolor="#999999"><strong>Mijloc tranport </strong></td>
                      <td><strong></strong></td>
                    </tr>
                    <tr>
                      <td bgcolor="#999999"><strong>Semnatura</strong></td>
                      <td><strong> </strong></td>
                    </tr>
                </table></td>
                <td width="35%"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td><strong>Total Factura </strong></td>
                    <td><strong>Total TVA </strong></td>
                  </tr>
                  <tr>
                    <td>'. number_format($bon -> obj -> total*100/124,2,'.','') .'</td>
                    <td>'. number_format($bon -> obj -> total*24/124,2,'.','') .'</td>
                  </tr>
                  <tr>
                    <td colspan="2"><div align="center"><strong>Total de plata </strong></div></td>
                  </tr>
                  <tr>
                    <td colspan="2">'. number_format($bon -> obj -> total,2,'.','') .'</td>
                  </tr>
                </table></td>
              </tr>
          </table></td>
        </tr>
      </table>';
	  return $out;
		}	
}
?>