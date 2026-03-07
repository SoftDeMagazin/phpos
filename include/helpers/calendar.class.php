<?php
class Calendar
{
    function luna($luna)
    {
        switch ($luna) {
            case 1:{$l="Ianuarie";

            }break;
            case 2:{$l="Februarie";

            }break;
            case 3:{$l="Martie";

            }break;
            case 4:{$l="Aprilie";

            }break;
            case 5:{$l="Mai";

            }break;
            case 6:{$l="Iunie";

            }break;
            case 7:{$l="Iulie";

            }break;
            case 8:{$l="August";

            }break;
            case 9:{$l="Septembrie";

            }break;
            case 10:{$l="Octombrie";

            }break;
            case 11:{$l="Noiembrie";

            }break;
            case 12:{$l="Decembrie";

            }break;
        }
        return $l;
    }
    

    function genereazaCalendar($luna, $an, $dest, $window = "")
    {
        $txt = '';
        if ($luna== null || $an== null) {
            $luna= date("m");
            $an= date("Y");
        }
    
        $azi = date("Y-m-d");

        $prima_zi = date("w", mktime(0, 0, 0, $luna, 1, $an));
    
        if ($prima_zi == 0) {
            $prima_zi= 7;
        }

        $total_zile = date("t", mktime(0, 0, 0, $luna, 1, $an));


        $saptamana=1;
        $zi=0;

        while ($saptamana <=6) {
            if ($saptamana == 1) {
                for ($i=1; $i<=7; $i++) {
                    if ($i < $prima_zi) {
                        $zi = 0;
                    } elseif ($i == $prima_zi) {
                        $zi=1;
                        $calendar[$saptamana][$i] = $zi;
                    } elseif ($i > $prima_zi) {
                        $zi++;
                    }
  
                    $calendar[$saptamana][$i] = $zi;
                }
            } else {
                for ($i=1; $i<=7; $i++) {
                    if ($zi > ($total_zile-1)) {
                        $calendar[$saptamana][$i] = 0;
                    } else {
                        $zi++;
                        $calendar[$saptamana][$i] = $zi;
                    }
                }
            }
            $saptamana++;
        }
        $an_next = $an;
        $an_back = $an;
        $next = $luna+1;
        if ($next == 13) {
            $next = 1;
            $an_next = $an + 1;
        }
        $back = $luna-1;
        if ($back == 0) {
            $back = 12;
            $an_back = $an - 1;
        }

        $luna_nume = $this -> luna($luna);

        $txt .= '	  
	  	<table width="140" border="0"  cellpadding="4" cellspacing="1" align="center">
        	<tr align="center" style=" ">
		    	<td class="zi"> 
			<input type="button" value="<<" onClick="xajax_calChangeDate('. $back .','. $an_back .', \''. $dest .'\', \''. $window .'\');" style="padding: 5px 5px 5px 5px;">
			</td>
          	<td class="zi" colspan="5">&nbsp;'. $luna_nume .'&nbsp;'. $an .'</td>
          	<td class="zi">
			<input type="button" value=">>" onClick="xajax_calChangeDate('. $next .','. $an_next .', \''. $dest .'\', \''. $window .'\');" style="padding: 5px 5px 5px 5px;">
			</td>
        	</tr>
        	<tr align="right">
          	<td class="zi" >Lu</td>
          	<td class="zi" >Ma</td>
          	<td class="zi" >Mi</td>
          	<td class="zi" >Jo</td>
          	<td class="zi" >Vi</td>
          	<td class="zi" >Sa</td>
          	<td class="zi" >Du</td>';
         
        for ($i=1; $i<=6; $i++) {
            $txt .= '</tr>
        <tr align="right" >';
            for ($j=1; $j<=7; $j++) {
                $zi = $an."-".$luna."-".$calendar[$i][$j];
                $d = date("Y-m-d", mktime(0, 0, 0, $luna, $calendar[$i][$j], $an));
                $onClick = "document.getElementById('$dest').value = '$d';xajax_close_window('". $window ."');";
                if ($calendar[$i][$j] != 0) {
                    if ($azi == $zi) {
                        if (1 != 0) {
                                                $txt .= '<td class="azi" ><input type="button" value="'. $calendar[$i][$j] .'" onClick="'. $onClick .'" style="padding: 5px 5px 5px 5px;"></td>';
                        } else {
                            $txt .= '<td class="azi" >'. $calendar[$i][$j] .'</td>';
                        }
                    } elseif (1 != 0) {
                        $txt .= '<td class="zi" ><input type="button" value="'. $calendar[$i][$j] .'" onClick="'. $onClick .'" style="padding: 5px 5px 5px 5px;"></td>';
                    } else {
                        $txt .= '<td class="zi" >'. $calendar[$i][$j] .'</td>';
                    }
                } else {
                    $txt .= '<td class="zi" >&nbsp;</td>';
                }
            }
        }
        $txt .= '</tr></table>';
        return $txt;
    }
}
