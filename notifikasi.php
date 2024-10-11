<?php
include "conf/conn.php";
$act = $_GET['aksi'];
switch($act){
 case "selectall":
	function CekWaktu($todays_date,$end_date)
	{
	   $end_date = strtotime($end_date);
	   $todays_date = strtotime($todays_date); 
		  if($todays_date > $end_date)
		  {
			 return 1;
		  }
		  else
		  {
			 return 2;
		  }
	}
$hitungres = 0;
$ambilres = "SELECT DARI FROM RESERVASI WHERE STATUS = 'AKTIF'";		  
$datares = ociparse($link,$ambilres);
ociexecute($datares) or die ("Gagal terhubung ke database");
while($dres = oci_fetch_array($datares))
{		
	$DATE_NOWRES=date("Y-m-d H:i:s");
	$END_DATERES=date("Y-m-d 12:00:00",(strtotime($dres['DARI'])));
	$CekStatusres=CekWaktu($DATE_NOWRES,$END_DATERES);
	if($CekStatusres==1)
	{
	   $hitungres = $hitungres + 1;
	} 
	elseif($CekStatusres==2)
	{
	   $hitungres = $hitungres;
	}
}

$hitungout = 0;
$ambilout = "SELECT TGL_CEKOUT FROM INMESS WHERE STATUS = 'IN'";		  
$dataout = ociparse($link,$ambilout);
ociexecute($dataout) or die ("Gagal terhubung ke database");
while($dout = oci_fetch_array($dataout))
{		
	$DATE_NOWOUT=date("Y-m-d H:i:s");
	$END_DATEOUT=date("Y-m-d 12:00:00",(strtotime($dout['TGL_CEKOUT'])));
	$CekStatusout=CekWaktu($DATE_NOWOUT,$END_DATEOUT);
	if($CekStatusout==1)
	{
	   $hitungout = $hitungout + 1;
	} 
	elseif($CekStatusout==2)
	{
	   $hitungout = $hitungout;
	}
}
$hitung = $hitungres + $hitungout;
echo json_encode($hitung);
break;

case "selectrespeg":
	function CekWaktu($todays_date1,$end_date1)
	{
	   $end_date1 = strtotime($end_date1);
	   $todays_date1 = strtotime($todays_date1); 
		  if($todays_date1 > $end_date1)
		  {
			 return 1;
		  }
		  else
		  {
			 return 2;
		  }
	}
$hitung1 = 0;
$ambil1 = "SELECT DARI FROM RESERVASI WHERE JENIS_PELANGGAN = 'PEGAWAI' AND STATUS = 'AKTIF'";		  
$data1 = ociparse($link,$ambil1);
ociexecute($data1) or die ("Gagal terhubung ke database");
while($d1 = oci_fetch_array($data1))
{		
	$DATE_NOW1=date("Y-m-d H:i:s");
	$END_DATE1=date("Y-m-d 12:00:00",(strtotime($d1['DARI'])));
	$CekStatus1=CekWaktu($DATE_NOW1,$END_DATE1);
	if($CekStatus1==1)
	{
	   $hitung1 = $hitung1 + 1;
	} 
	elseif($CekStatus1==2)
	{
	   $hitung1 = $hitung1;
	}
}
echo json_encode($hitung1);
break;

case "selectresum":
	function CekWaktu($todays_date1,$end_date1)
	{
	   $end_date1 = strtotime($end_date1);
	   $todays_date1 = strtotime($todays_date1); 
		  if($todays_date1 > $end_date1)
		  {
			 return 1;
		  }
		  else
		  {
			 return 2;
		  }
	}
$hitung1 = 0;
$ambil1 = "SELECT DARI FROM RESERVASI WHERE JENIS_PELANGGAN = 'UMUM' AND STATUS = 'AKTIF'";		  
$data1 = ociparse($link,$ambil1);
ociexecute($data1) or die ("Gagal terhubung ke database");
while($d1 = oci_fetch_array($data1))
{		
	$DATE_NOW1=date("Y-m-d H:i:s");
	$END_DATE1=date("Y-m-d 12:00:00",(strtotime($d1['DARI'])));
	$CekStatus1=CekWaktu($DATE_NOW1,$END_DATE1);
	if($CekStatus1==1)
	{
	   $hitung1 = $hitung1 + 1;
	} 
	elseif($CekStatus1==2)
	{
	   $hitung1 = $hitung1;
	}
}
echo json_encode($hitung1);
break;

case "selectcekout":
	function CekWaktu($todays_date2,$end_date2)
	{
	   $end_date2 = strtotime($end_date2);
	   $todays_date2 = strtotime($todays_date2); 
		  if($todays_date2 > $end_date2)
		  {
			 return 1;
		  }
		  else
		  {
			 return 2;
		  }
	}
$hitung2 = 0;
$ambil2 = "SELECT TGL_CEKOUT FROM INMESS WHERE STATUS = 'IN'";		  
$data2 = ociparse($link,$ambil2);
ociexecute($data2) or die ("Gagal terhubung ke database");
while($d2 = oci_fetch_array($data2))
{		
	$DATE_NOW2=date("Y-m-d H:i:s");
	$END_DATE2=date("Y-m-d 12:00:00",(strtotime($d2['TGL_CEKOUT'])));
	$CekStatus2=CekWaktu($DATE_NOW2,$END_DATE2);
	if($CekStatus2==1)
	{
	   $hitung2 = $hitung2 + 1;
	} 
	elseif($CekStatus2==2)
	{
	   $hitung2 = $hitung2;
	}
}
echo json_encode($hitung2);
break;
}
?>