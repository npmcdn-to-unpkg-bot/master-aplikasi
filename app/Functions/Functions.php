<?php

function timeAgo($timestamp){
    $datetime1=new DateTime("now");
    $datetime2=date_create($timestamp);
    $diff=date_diff($datetime1, $datetime2);
    $timemsg='';
    if($diff->y > 0){
        $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

    }
    else if($diff->m > 0){
     $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
    }
    else if($diff->d > 0){
     $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
    }
    else if($diff->h > 0){
     $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
    }
    else if($diff->i > 0){
     $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
    }
    else if($diff->s > 0){
     $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
    }

$timemsg = $timemsg.' ago';
return $timemsg;
}
	
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}

function tglIndoKeBarat($tagSource)
	{
	$TGL1=explode("-",substr($tagSource,0,10));
	$asli = $TGL1[0]."-".$TGL1[1]."-".$TGL1[2];
	$TGL1 = $TGL1[2]."-".$TGL1[1]."-".$TGL1[0];
	$tglIndoKeBarat = str_replace($asli,$TGL1,$tagSource);
	return $tglIndoKeBarat;
	}
	
	function formatAngka($arg)
	{
	$objArgs = func_get_args();
	$nCount = count($objArgs);
	$formatAngka=number_format($objArgs[0],0,',','.');
	if($nCount==2) 
		{
		$formatAngka=number_format($objArgs[0],$objArgs[1],',','.');
		if(substr($formatAngka,-3)=='000') $formatAngka=number_format($objArgs[0],0,',','.');
		}
	return $formatAngka;
	}

function rubahKeUnix($access_date)
	{
	$date_elements =  explode("-" ,$access_date);
	
	// at this point
	// $date_elements[0] = 2000
	// $date_elements[1] = 5
	// $date_elements[2] = 27
	$jam_elements =  explode(":" ,$access_date);
	
	// $jam_elements[0] = 10 jam
	// $jam_elements[1] = 15 menit
	// $jam_elements[2] = 27 detik
	
	if(strlen($jam_elements[1]) > 0)
		{
		$jam_elements[0]=substr($jam_elements[0],strlen($jam_elements[0]) - 2);
		$rubahKeUnix=mktime ($jam_elements[0], $jam_elements[1], $jam_elements[2], $date_elements [1], $date_elements[ 2],$date_elements [0]);
		}
	else
		{
		if($date_elements [0] < 1970) $date_elements [0]=1970;
		$rubahKeUnix=mktime (0, 0,0 ,$date_elements [1], $date_elements[2],$date_elements [0]);
		}
	return $rubahKeUnix;
	}
	
function slotnumber()
{
  srand(time());
    for ($i=0; $i < 5; $i++)
    {
      $random = (rand()%9);
      $slot[] = $random;
    }
  $no .= $slot[0];
  $no .= $slot[1];
  $no .= $slot[2];
  $no .= $slot[3];
  $no .= $slot[4];
  return $no;    
}

function xBulanIndo($xBulanIndo)
    {    
    if($xBulanIndo == 1) $xBulanIndo="Januari";
    if($xBulanIndo == 2) $xBulanIndo="Februari";
    if($xBulanIndo == 3) $xBulanIndo="Maret";
    if($xBulanIndo == 4) $xBulanIndo="April";
    if($xBulanIndo == 5) $xBulanIndo="Mei";
    if($xBulanIndo == 6) $xBulanIndo="Juni";
    if($xBulanIndo == 7) $xBulanIndo="Juli";
    if($xBulanIndo == 8) $xBulanIndo="Agustus";
    if($xBulanIndo == 9) $xBulanIndo="September";
    if($xBulanIndo == 10) $xBulanIndo="Oktober";
    if($xBulanIndo == 11) $xBulanIndo="November";
    if($xBulanIndo == 12) $xBulanIndo="Desember";
    return $xBulanIndo;
    }

function xHariIndo($xHariIndo)
    {    
    if($xHariIndo == 0 or $xHariIndo == 7) $xHariIndo="Minggu";
    if($xHariIndo == 1) $xHariIndo="Senin";
    if($xHariIndo == 2) $xHariIndo="Selasa";
    if($xHariIndo == 3) $xHariIndo="Rabu";
    if($xHariIndo == 4) $xHariIndo="Kamis";
    if($xHariIndo == 5) $xHariIndo="Jum'at";
    if($xHariIndo == 6) $xHariIndo="Sabtu";
    return $xHariIndo;
    }
	
function utkDigit($digit,$nilai)
    {
      $utkDigit = str_pad($nilai, $digit, "0", STR_PAD_LEFT);  
      return $utkDigit;
    }
	
function tglIndo()
    {
    //argumen asli ($xTgl,$formatNya,$selisihJam)
    //argumen tambahan $sisaTanggal
    $objArgs = func_get_args();
	//print_r($objArgs);
    $nCount = count($objArgs);
    $xTgl=$objArgs[0];
    if(strpos($xTgl,"-") > 0) $xTgl=rubahKeUnix($xTgl);
    $formatNya=$objArgs[1];
    $selisihJam=$objArgs[2];
    $sisaTanggal=0;
    if($nCount > 3)
        {
        $sisaTanggal=$objArgs[3];
        }
       $xTgl = getdate(DateAdd("h",$selisihJam,$xTgl));
    //l = long Date (Selasa, 1 Januari 2002, 03:00 WIB)
    //h = long Date (Selasa, 1 Januari 2002, 03:00 WIB)
    //s = short Date (1 Januari 2002)
    //t = time (03:00 WIB)
    //f = (7/22/2003 9:50:37 PM)
      if($formatNya == "l") 
          {
            $TglIndo = xHariIndo($xTgl["wday"]).", ".$xTgl["mday"]." ".xBulanIndo($xTgl["mon"])." ".($xTgl["year"] - $sisaTanggal).", ".utkDigit(2,$xTgl["hours"]).":".utkDigit(2,$xTgl["minutes"])." WIB";
        }
      elseif($formatNya == "l_e") 
          {
            $TglIndo = $xTgl["weekday"].", ".$xTgl["month"]." ".$xTgl["mday"].", ".($xTgl["year"] - $sisaTanggal)." - ".utkDigit(2,$xTgl["hours"]).":".utkDigit(2,$xTgl["minutes"])." WIT (GMT + 7)";
        }
      elseif($formatNya == "h") 
          {
            $TglIndo = xHariIndo($xTgl["wday"]).", ".$xTgl["mday"]." ".xBulanIndo($xTgl["mon"])." ".($xTgl["year"] - $sisaTanggal);
        }
     elseif($formatNya == "f") 
          {
            $TglIndo =$xTgl["mday"]."/".$xTgl["mon"]."/".($xTgl["year"] - $sisaTanggal).", ".utkDigit(2,$xTgl["hours"]).":".utkDigit(2,$xTgl["minutes"])." WIB";
        }
     elseif($formatNya == "s")
          {
            $TglIndo = $xTgl["mday"]." ".xBulanIndo($xTgl["mon"])." ".($xTgl["year"] - $sisaTanggal);
        }
     elseif($formatNya == "jawa")
          {
            $TglIndo = xHariIndo($xTgl["wday"])." ".weton($objArgs[0]).", ".$xTgl["mday"]." ".xBulanIndo($xTgl["mon"])." ".($xTgl["year"] - $sisaTanggal);
        }
     elseif($formatNya == "s_e")
          {
            $TglIndo = $xTgl["mday"]." ".xBulanIndo2($xTgl["mon"])." ".($xTgl["year"] - $sisaTanggal);
        }        
     elseif($formatNya == "t")
          {
            $TglIndo = utkDigit(2,$xTgl["hours"]).":".utkDigit(2,$xTgl["minutes"])." WIB";
        }
     elseif($formatNya == "z")
          {
            $TglIndo = $xTgl["mday"]." ".xBulanIndo($xTgl["mon"])." ".($xTgl["year"] - $sisaTanggal).", ".utkDigit(2,$xTgl["hours"]).":".utkDigit(2,$xTgl["minutes"]).":".utkDigit(2,$xTgl["seconds"])." WIB";
        }
    elseif($formatNya == "z_e") 
          {
            $TglIndo = $xTgl["mday"]." ".$xTgl["month"]." ".($xTgl["year"] - $sisaTanggal)." - ".utkDigit(2,$xTgl["hours"]).":".utkDigit(2,$xTgl["minutes"]).":".utkDigit(2,$xTgl["seconds"])." WIT (GMT + 7)";
        }
        return $TglIndo;      
    }
    
function DateAdd ($interval,  $number, $date) 
    {

    $date_time_array  = getdate($date);
        
    $hours =  $date_time_array["hours"];
    $minutes =  $date_time_array["minutes"];
    $seconds =  $date_time_array["seconds"];
    $month =  $date_time_array["mon"];
    $day =  $date_time_array["mday"];
    $year =  $date_time_array["year"];
    
        switch ($interval) {
        
            case "yyyy":
                $year +=$number;
                break;        
            case "q":
                $year +=($number*3);
                break;        
            case "m":
                $month +=$number;
                break;        
            case "y":
            case "d":
            case "w":
                 $day+=$number;
                break;        
            case "ww":
                 $day+=($number*7);
                break;        
            case "h":
                 $hours+=$number;
                break;        
            case "n":
                 $minutes+=$number;
                break;        
            case "s":
                 $seconds+=$number;
                break;        
    
        }    
    $timestamp =  mktime($hours ,$minutes, $seconds,$month ,$day, $year);
    
        return $timestamp;
    }
	
function textBetween($subject="",$start="",$end="")
{
    $ret=array("start"=>false,"end"=>false,"text"=>$subject,"between"=>"","left"=>"","right"=>"");
    if(is_string($ret["text"]) && is_string($start)){
        $subl=strlen($ret["text"]);
        $startl=strlen($start);
        if($subl && $startl){
            
            $ret["start"]=strpos($ret["text"],$start);
            if($ret["start"]!==false){
                
                $offset=$ret["start"]+$startl;
                $ret["text"]=substr($ret["text"],$offset);
                if(is_string($end)){
                    $endl=strlen($end);
                    if($endl){
                        $ret["end"]=strpos($ret["text"],$end);
                        if($ret["end"]!==false){
                            $checknested=true;
                            $tmppos=0;
                            while($checknested){
                                
                                $ttp=strpos(substr($ret["text"],$tmppos,$ret["end"]-$tmppos),$start);
                                if($ttp!==false){
                                    $tmppos+=$ttp;
                                  
                                    $ret["end"]=strpos($ret["text"],$end,$ret["end"]+1);
                                    if($ret["end"]!==false){
                                        $tmppos++;
                                    }else{
                                        $checknested=false;
                                    }
                                }else{
                                   
                                    $checknested=false;
                                    if($ret["end"]!==false){
                                        $ret["text"]=substr($ret["text"],0,$ret["end"]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    if($ret["text"]!=$subject){
        $ret["between"]=$ret["text"];
    }
    if($ret["start"]!==false){
        if($ret["start"]>0){
            $ret["outside"]=substr($subject,0,$ret["start"]);
            $ret["left"]=$ret["outside"];
        }
        if($ret["end"]!==false && $ret["end"]>0){
            $ret["right"]=substr($subject,$ret["start"]+$ret["end"]+$startl+$endl);
            $ret["outside"].=$ret["right"];
        }
    }else{
        $ret["outside"]=$subject;
    }
   
    return $ret;
}


?>