<?php

class Fiscal
{	
	var $bon_id;
	var $BonText;
	var $BonFile="CASHFILE.INP";
	var $FilePath="C:\\CaSyst\\";
	var $mysql;
	
	function append(&$txt, $text)
		{
		$txt .= ''.$text;
		}
	
	function replace(&$unde, $cum, $ce)
		{
		$unde = str_replace($cum, $ce, $unde);
		}
		
	function scrieFisier()
		{
		if(!empty($this -> BonText))
		{	
		$h = fopen("".$this -> FilePath."".$this -> BonFile."", "w+");
		fwrite($h, "".$this -> BonText."");
		}
		}
}

class FiscalDatecs extends Fiscal
{
	var $Linie = "S,<%id%>,______,_,__;<%denumire%>;<%pret%>;<%cant%>;<%sectie%>;1;<%tva%>;0;0;\r\n";
	var $Discount = "C,<%id%>,______,_,__;1;<%procent%>;;;;\r\n";
	var $End = "T,<%id%>,______,_,__;<%cod%>;<%total%>;;;;\r\n";
	var $Cui = "K,<%id%>,______,_,__;<%cui%>;\r\n";
	
	
	 function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	 function genereazaBon()
		{
		global $cfgFiscal;
		$Bon = new Bonuri($this -> mysql, $this -> bon_id);
		$Casa = new CaseFiscale($this -> mysql, $Bon -> obj -> casa_id);
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> bon_id);
		$txt = "";
		if($Bon -> obj -> avans == 'NU')
		{
		if(isset($BonContinut -> objects))
			{
				if(!empty($Bon -> obj -> cui)) {
					$linieCui = $this -> Cui;
					$this -> replace($linieCui, '<%id%>', $Casa -> obj -> id);
					$this -> replace($linieCui, '<%cui%>', $Bon -> obj -> cui);
					$this -> append($txt, $linieCui);
				}

			$Produs = new Produse($this -> mysql);
            $Cotatva = new CoteTva($this->mysql);
			foreach($BonContinut -> objects as $objContinut)
				{
				$Produs -> get($objContinut -> produs_id);
                $Cotatva -> get($Produs->obj->cotatva_id);
				$linie = $this -> Linie;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%denumire%>', strtoupper(substr($Produs -> obj -> denumire, 0, 20)));
				$this -> replace($linie, '<%pret%>', number_format($objContinut -> valoare, 2, '.', ''));
				$this -> replace($linie, '<%cant%>', number_format($objContinut -> cantitate, 3, '.', ''));
				$this -> replace($linie, '<%sectie%>', '1');
                if(isset($Cotatva->obj)) {
                    $this->replace($linie, '<%tva%>', $Cotatva->obj->cod);
                } else {
                    $this->replace($linie, '<%tva%>', '1');
                }
				$this -> append($txt, $linie);
					if($objContinut -> discount > 0) {
						$discount = $this -> Discount;
						$this -> replace($discount, '<%id%>', $Casa -> obj -> id);
						$this -> replace($discount, '<%procent%>', number_format($objContinut -> discount,2,'.',''));
						$this -> append($txt, $discount);
					}
				}
			}
		
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> findAllBy("bon_id", $this -> bon_id);
		$bon_fiscal = TRUE;
		if(isset($BonPlata -> objects))
			{
			$nr_r  = count($BonPlata -> objects);
			if($nr_r > 1) {
			
			foreach($BonPlata -> objects as $objPlata)
				{
				$linie = $this -> End;
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				if($ModPlata -> obj -> fiscal == 'NU') $bon_fiscal = FALSE;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%cod%>', $ModPlata -> obj -> final_fiscal);
				$this -> replace($linie, '<%total%>', number_format($objPlata -> suma, 2, '.', ''));
				$this -> append($txt, $linie);
				}
			}
			else {
				$objPlata = $BonPlata -> objects[0];
				$linie = $this -> End;
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				if($ModPlata -> obj -> fiscal == 'NU') $bon_fiscal = FALSE;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%cod%>', $ModPlata -> obj -> final_fiscal);
				if($ModPlata -> obj -> final_fiscal == '0') $this -> replace($linie, '<%total%>', '');
				else $this -> replace($linie, '<%total%>', number_format($objPlata -> suma, 2, '.', ''));
				$this -> append($txt, $linie);
			}
			}
		
		$this -> BonFile = $this -> bon_id.".INP";
		
		$this -> BonText = $txt;
		if($bon_fiscal) {$this -> scrieFisier();}
		}
		else 
		{
		if($Bon -> obj -> achitat == 'NU')
			{
			$procent = $Bon -> obj -> suma_avans/100;
			$fisier = "-AV";
			$prod = "AV ";
			}
			else
			{
			$procent = (100 - $Bon -> obj -> suma_avans)/100;
			$fisier = "-TOT";
			$prod = "TOT ";
			}
		//emitere bon fiscal cu avans	
		if(isset($BonContinut -> objects))
			{
			foreach($BonContinut -> objects as $objContinut)
				{
				$Produs = new Produse($this -> mysql, $objContinut -> produs_id);
				$linie = $this -> Linie;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%denumire%>', strtoupper(substr($prod.$Produs -> obj -> denumire, 0, 20)));
				$this -> replace($linie, '<%pret%>', number_format($objContinut -> valoare*$procent, 2, '.', ''));
				$this -> replace($linie, '<%cant%>', number_format($objContinut -> cantitate, 3, '.', ''));
				$this -> replace($linie, '<%sectie%>', '1');
				$this -> append($txt, $linie);
				}
			}
		
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> findAllBy("bon_id", $this -> bon_id);
		$bon_fiscal = TRUE;
		if(isset($BonPlata -> objects))
			{
			foreach($BonPlata -> objects as $objPlata)
				{
				$linie = $this -> End;
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				if($ModPlata -> obj -> fiscal == 'NU') $bon_fiscal = FALSE;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%cod%>', $ModPlata -> obj -> final_fiscal);
				$this -> replace($linie, '<%total%>', number_format($objPlata -> suma*$procent, 2, '.', ''));
				$this -> append($txt, $linie);
				}
			}
		
		$this -> BonFile = $this -> bon_id."". $fisier .".INP";
		
		$this -> BonText = $txt;
		if($bon_fiscal) $this -> scrieFisier();
		}
		}	
		
		function executBon()
			{
			}
		
}

class FiscalNet extends Fiscal
{
	var $Linie = "S^<%denumire%>^<%pret%>^<%cant%>^<%um%>^<%tva%>^<%sectie%>\r\n";
	var $Discount = "DP^<%procent%>\r\n";
	var $End = "P^<%cod%>^<%total%>\r\n";
	
	
	
	 function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	 function genereazaBon()
		{
		global $cfgFiscal;
		$Bon = new Bonuri($this -> mysql, $this -> bon_id);
		$Casa = new CaseFiscale($this -> mysql, $Bon -> obj -> casa_id);
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> bon_id);
		$txt = "";
		if($Bon -> obj -> avans == 'NU')
		{
		if(isset($BonContinut -> objects))
			{
			$Produs = new Produse($this -> mysql);
			foreach($BonContinut -> objects as $objContinut)
				{
				$Produs -> get($objContinut -> produs_id);
				$linie = $this -> Linie;
				$this -> replace($linie, '<%denumire%>', strtoupper(substr($Produs -> obj -> denumire, 0, 20)));
				$this -> replace($linie, '<%pret%>', number_format($objContinut -> valoare, 2, '', ''));
				$this -> replace($linie, '<%cant%>', number_format($objContinut -> cantitate, 3, '', ''));
				$this -> replace($linie, '<%um%>', 'buc');
				$this -> replace($linie, '<%tva%>', '1');
				$this -> replace($linie, '<%sectie%>', '1');
				$this -> append($txt, $linie);
					if($objContinut -> discount > 0) {
						$discount = $this -> Discount;
						$this -> replace($discount, '<%procent%>', number_format($objContinut -> discount,2,'',''));
						$this -> append($txt, $discount);
					}
				}
			}
		
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> findAllBy("bon_id", $this -> bon_id);
		$bon_fiscal = TRUE;
		if(isset($BonPlata -> objects))
			{
			$nr_r  = count($BonPlata -> objects);
			if($nr_r > 1) {
			
			foreach($BonPlata -> objects as $objPlata)
				{
				$linie = $this -> End;
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				if($ModPlata -> obj -> fiscal == 'NU') $bon_fiscal = FALSE;
				$this -> replace($linie, '<%cod%>', $ModPlata -> obj -> final_fiscal);
				$this -> replace($linie, '<%total%>', number_format($objPlata -> suma, 2, '', ''));
				$this -> append($txt, $linie);
				}
			}
			else {
				$objPlata = $BonPlata -> objects[0];
				$linie = $this -> End;
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				if($ModPlata -> obj -> fiscal == 'NU') $bon_fiscal = FALSE;
				$this -> replace($linie, '<%cod%>', $ModPlata -> obj -> final_fiscal);
				if($ModPlata -> obj -> final_fiscal == '0') $this -> replace($linie, '<%total%>', '');
				else $this -> replace($linie, '<%total%>', number_format($objPlata -> suma, 2, '', ''));
				$this -> append($txt, $linie);
			}
			}
		
		$this -> BonFile = $this -> bon_id.".txt";
		
		$this -> BonText = $txt;
		if($bon_fiscal) {$this -> scrieFisier();}
		}
		else 
		{
		if($Bon -> obj -> achitat == 'NU')
			{
			$procent = $Bon -> obj -> suma_avans/100;
			$fisier = "-AV";
			$prod = "AV ";
			}
			else
			{
			$procent = (100 - $Bon -> obj -> suma_avans)/100;
			$fisier = "-TOT";
			$prod = "TOT ";
			}
		//emitere bon fiscal cu avans	
		if(isset($BonContinut -> objects))
			{
			foreach($BonContinut -> objects as $objContinut)
				{
				$Produs = new Produse($this -> mysql, $objContinut -> produs_id);
				$linie = $this -> Linie;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%denumire%>', strtoupper(substr($prod.$Produs -> obj -> denumire, 0, 20)));
				$this -> replace($linie, '<%pret%>', number_format($objContinut -> valoare*$procent, 2, '.', ''));
				$this -> replace($linie, '<%cant%>', number_format($objContinut -> cantitate, 3, '.', ''));
				$this -> replace($linie, '<%sectie%>', '1');
				$this -> append($txt, $linie);
				}
			}
		
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> findAllBy("bon_id", $this -> bon_id);
		$bon_fiscal = TRUE;
		if(isset($BonPlata -> objects))
			{
			foreach($BonPlata -> objects as $objPlata)
				{
				$linie = $this -> End;
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				if($ModPlata -> obj -> fiscal == 'NU') $bon_fiscal = FALSE;
				$this -> replace($linie, '<%id%>', $Casa -> obj -> id);
				$this -> replace($linie, '<%cod%>', $ModPlata -> obj -> final_fiscal);
				$this -> replace($linie, '<%total%>', number_format($objPlata -> suma*$procent, 2, '.', ''));
				$this -> append($txt, $linie);
				}
			}
		
		$this -> BonFile = $this -> bon_id."". $fisier .".INP";
		
		$this -> BonText = $txt;
		if($bon_fiscal) $this -> scrieFisier();
		}
		}	
		
		function executBon()
			{
			}
		
}

class FiscalZeka extends Fiscal
{
	var $Linie = "";
	var $End;
	function __construct($mysql)
		{
		$this -> mysql = $mysql;
		}
	
	function genereazaBon()	
		{
		global $cfgFiscal;
		$Bon = new Bonuri($this -> mysql, $this -> bon_id);
		$txt = "KARAT\r\n";
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> bon_id);
		if(isset($BonContinut -> objects))
			{
			foreach($BonContinut -> objects as $objContinut)
				{
				$linie = '*';
				$Produs = new Produse($this -> mysql, $objContinut -> produs_id);
				$denumire = strtoupper(substr($Produs -> obj -> denumire, 0, 15));
				$denumire = str_pad($denumire, 24, ' ', STR_PAD_RIGHT);
				$linie .= $denumire;
				$pret = number_format($objContinut -> valoare, 2, '.', '');
				$pret = $pret*100;
				$pret = str_pad($pret, 8, '0', STR_PAD_LEFT);
				$linie .=$pret;
				$linie .='2';
				$cantitate = number_format($objContinut -> cantitate, 3, '.', '');
				$cantitate = $cantitate*1000;
				$cantitate = str_pad($cantitate, 9, '0', STR_PAD_LEFT);
				$linie .= $cantitate;
				$linie .= "100";
				$this -> append($txt, $linie);
				$this -> append($txt, "\r\n");
				}
			}
		$this -> BonFile = $this -> bon_id.".BON";
		$txt .= "END KARAT";
		$this -> BonText = $txt;
		$this -> scrieFisier();
		}
	
	function executBon()
		{
		}	
}

class FiscalElka extends Fiscal
{
	var $Linie = "";
	var $End;
	function __construct($mysql)
		{
		global $mysql;
		$this -> mysql = $mysql;
		}
	
	function genereazaBon()	
		{
		global $cfgFiscal;
		$Bon = new Bonuri($this -> mysql, $this -> bon_id);
		$txt = "";
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> bon_id);
		if(isset($BonContinut -> objects))
			{
			foreach($BonContinut -> objects as $objContinut)
				{
				$linie = '';
				$Produs = new Produse($this -> mysql, $objContinut -> produs_id);
				$denumire = strtoupper(substr($Produs -> obj -> denumire, 0, 12));
				$denumire = str_replace(" ","_",$denumire);
				$linie .= $Produs -> obj -> produs_id;
				$linie .= ",";
				$linie .= $Produs -> obj -> categorie_id;
				$linie .= ",";
				$linie .= $denumire;
				$linie .= ",";
				$pret = number_format($objContinut -> valoare, 2, '.', '');
				$pret = $pret*100;
				$linie .= $pret;
				$linie .= ",";
				$linie .='2';
				$cantitate = number_format($objContinut -> cantitate, 2, '.', '');
				$cantitate = $cantitate*100;
				$linie .= $cantitate;
				$linie .= ",";
				$linie .= "1,1,+0000";
				$this -> append($txt, $linie);
				$this -> append($txt, "\r\n");
				}
			}
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> findAllBy("bon_id", $this -> bon_id);
		if(isset($BonPlata -> objects))
			{
			foreach($BonPlata -> objects as $objPlata)
				{
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				$this -> append($txt, $ModPlata -> obj -> final_fiscal);
				$this -> append($txt, ",");
				$this -> append($txt, $ModPlata -> obj -> suma);
				$this -> append($txt, "\r\n");
				}
			}
		$this -> BonFile = $this -> bon_id.".BON";
		$this -> BonText = $txt;
		$this -> scrieFisier();
		}
	
	function executBon()
		{
		}	
}
class FiscalSapel extends Fiscal
{
	var $Linie = "";
	var $End;
	function __construct($mysql)
		{
		global $mysql;
		$this -> mysql = $mysql;
		}
	
	function genereazaBon()	
		{
		global $cfgFiscal;
		$Bon = new Bonuri($this -> mysql, $this -> bon_id);
		$txt = "";
		$BonContinut = new BonuriContinut($this -> mysql);
		$BonContinut -> findAllBy("bon_id", $this -> bon_id);
		if(isset($BonContinut -> objects))
			{
			foreach($BonContinut -> objects as $objContinut)
				{
				$linie = '';
				$Produs = new Produse($this -> mysql, $objContinut -> produs_id);
				$denumire = strtoupper(substr($Produs -> obj -> denumire, 0, 20));
				$denumire = str_pad($denumire,20," ",STR_PAD_RIGHT);
				$linie .= $denumire;
				$linie .= ",";
				$cantitate = number_format($objContinut -> cantitate, 3, '.', '');
				$cantitate = str_pad($cantitate, 8, '0', STR_PAD_LEFT);
				$linie .= $cantitate;
				$linie .= ",";
				$pret = number_format($objContinut -> valoare, 2, '.', '');
				$pret = $pret*100;
				$pret = str_pad($pret, 8, '0', STR_PAD_LEFT);
				$linie .= $pret;
				$linie .= ",";
				$linie .='01';
				$this -> append($txt, $linie);
				$this -> append($txt, "\r\n");
				}
			}
		$BonPlata = new BonuriPlata($this -> mysql);
		$BonPlata -> findAllBy("bon_id", $this -> bon_id);
		if(isset($BonPlata -> objects))
			{
			foreach($BonPlata -> objects as $objPlata)
				{
				$ModPlata = new ModuriPlata($this -> mysql, $objPlata -> mod_plata_id);
				$this -> append($txt, $ModPlata -> obj -> final_fiscal);
				//$this -> append($txt, $ModPlata -> obj -> suma);
				$this -> append($txt, "\r\n");
				}
			}
		$this -> BonFile = "BON".$this -> bon_id.".TXT";
		$this -> BonText = $txt;
		$this -> scrieFisier();
		}
	
	function executBon()
		{
		}	
}
?>
