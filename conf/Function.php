<?php
function dmY($tgl){
    $ch = explode("-","$tgl");
    $new = $ch['2'].'-'.$ch['1'].'-'.$ch['0'];
    $itu = strtotime($new);
    return date("Y-m-d", $itu);
}

function TotalKamarIDPeg($IDINMESS){
    include "conn.php";
    $DfMess = "SELECT INMESS.EXTRA_BED,DAPOK_PEGAWAI.NIP, DAPOK_PEGAWAI.NAMA_PEGAWAI, INMESS.JENIS_PELANGGAN,INMESS.ID_INMESS, TB_MESS.NAMA_MESS, KAMAR.NAMA_KAMAR,INMESS.TGL_CEKIN, INMESS.TGL_CEKOUT, KAMAR.HARGA_PEGAWAI FROM INMESS, DAPOK_PEGAWAI, KAMAR, TB_MESS WHERE INMESS.KODE_PELANGGAN = DAPOK_PEGAWAI.NIP AND KAMAR.ID_KAMAR=INMESS.ID_KAMAR AND TB_MESS.ID_MESS=KAMAR.ID_MESS AND STATUS_BAYAR='SUDAH' AND INMESS.ID_INMESS='$IDINMESS'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);
    $masuk = dmY("$DataMess[TGL_CEKIN]");
    $keluar = dmY("$DataMess[TGL_CEKOUT]");
    $lama = Selisihhari("$masuk","$keluar");
    $hargainap = $lama*$DataMess['HARGA_PEGAWAI'];
    return $hargainap;
}
function TotalKamarIDUmum($IDINMESS){
    include "conn.php";
    $DfMess = "SELECT KAMAR.HARGA_UMUM, PELANGGAN_UMUM.NIK, PELANGGAN_UMUM.NAMA_PELANGGAN, INMESS.JENIS_PELANGGAN, TB_MESS.NAMA_MESS, KAMAR.NAMA_KAMAR,INMESS.TGL_CEKIN, INMESS.TGL_CEKOUT,INMESS.ID_INMESS  
FROM INMESS, PELANGGAN_UMUM, KAMAR, TB_MESS 
WHERE INMESS.KODE_PELANGGAN = PELANGGAN_UMUM.NIK
 AND KAMAR.ID_KAMAR=INMESS.ID_KAMAR 
 AND TB_MESS.ID_MESS=KAMAR.ID_MESS 
 AND STATUS_BAYAR='SUDAH' AND INMESS.ID_INMESS='$IDINMESS'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);
    $masuk = dmY("$DataMess[TGL_CEKIN]");
    $keluar = dmY("$DataMess[TGL_CEKOUT]");
    $lama = Selisihhari("$masuk","$keluar");
    $hargainap = $lama*$DataMess['HARGA_UMUM'];
    return $hargainap;
}
function TotalBayarPegawai($IDINMESS){
    include "conn.php";
    $DfMess = "SELECT INMESS.EXTRA_BED,DAPOK_PEGAWAI.NIP, DAPOK_PEGAWAI.NAMA_PEGAWAI, INMESS.JENIS_PELANGGAN,INMESS.ID_INMESS, TB_MESS.NAMA_MESS, KAMAR.NAMA_KAMAR,INMESS.TGL_CEKIN, INMESS.TGL_CEKOUT, KAMAR.HARGA_PEGAWAI FROM INMESS, DAPOK_PEGAWAI, KAMAR, TB_MESS WHERE INMESS.KODE_PELANGGAN = DAPOK_PEGAWAI.NIP AND KAMAR.ID_KAMAR=INMESS.ID_KAMAR AND TB_MESS.ID_MESS=KAMAR.ID_MESS AND STATUS='OUT' AND INMESS.ID_INMESS='$IDINMESS'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);
    $masuk = dmY("$DataMess[TGL_CEKIN]");
    $keluar = dmY("$DataMess[TGL_CEKOUT]");
    $lama = Selisihhari("$masuk","$keluar");
    $hargainap = $lama*$DataMess['HARGA_PEGAWAI'];
    if (isset($DataMess['EXTRA_BED']) && !empty($DataMess['EXTRA_BED'])){
        $hargaExtra = 100000*$lama;
    }else{
        $hargaExtra = 0;
    }
    $tmkn = 0;
    $mknmnm = "SELECT * FROM PESAN_MENU, TB_MENU WHERE PESAN_MENU.ID_MENU=TB_MENU.ID_MENU AND ID_INMESS='$IDINMESS'";
    $Parmknmnm = ociparse($link, $mknmnm);
    ociexecute($Parmknmnm) or die();
    while ($DataMakan = oci_fetch_array($Parmknmnm)){
        //echo $DataMakan['HARGA'].'|'.$DataMakan['QTY_PESAN'].'|'.$DataMakan['ID_PESANMENU'].'</br>';
        $titemmkn = $DataMakan['HARGA']*$DataMakan['QTY_PESAN'];
        $tmkn=$tmkn+$titemmkn;
    }
    $TotalBayar = $hargainap+$hargaExtra+$tmkn;
    return $TotalBayar;
}
function ByrMakan($IDINMESS){
    include "conn.php";
    $tmkn = 0;
    $mknmnm = "SELECT * FROM PESAN_MENU, TB_MENU WHERE PESAN_MENU.ID_MENU=TB_MENU.ID_MENU AND ID_INMESS='$IDINMESS'";
    $Parmknmnm = ociparse($link, $mknmnm);
    ociexecute($Parmknmnm) or die();
    while ($DataMakan = oci_fetch_array($Parmknmnm)){
        //echo $DataMakan['HARGA'].'|'.$DataMakan['QTY_PESAN'].'|'.$DataMakan['ID_PESANMENU'].'</br>';
        $titemmkn = $DataMakan['HARGA']*$DataMakan['QTY_PESAN'];
        $tmkn=$tmkn+$titemmkn;

    }
    return $tmkn;
}
function TotalBayarUmum($IDINMESS){
    include "conn.php";
    $DfMess = "SELECT INMESS.EXTRA_BED, KAMAR.HARGA_UMUM, PELANGGAN_UMUM.NIK, PELANGGAN_UMUM.NAMA_PELANGGAN, INMESS.JENIS_PELANGGAN, TB_MESS.NAMA_MESS, KAMAR.NAMA_KAMAR,INMESS.TGL_CEKIN, INMESS.TGL_CEKOUT,INMESS.ID_INMESS  
FROM INMESS, PELANGGAN_UMUM, KAMAR, TB_MESS 
WHERE INMESS.KODE_PELANGGAN = PELANGGAN_UMUM.NIK
 AND KAMAR.ID_KAMAR=INMESS.ID_KAMAR 
 AND TB_MESS.ID_MESS=KAMAR.ID_MESS 
 AND STATUS='OUT' AND INMESS.ID_INMESS='$IDINMESS'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);
    $masuk = dmY("$DataMess[TGL_CEKIN]");
    $keluar = dmY("$DataMess[TGL_CEKOUT]");
    $lama = Selisihhari("$masuk","$keluar");
    $hargainap = $lama*$DataMess['HARGA_UMUM'];
    if (isset($DataMess['EXTRA_BED']) && !empty($DataMess['EXTRA_BED'])){
        $hargaExtra = 100000*$lama;
    }else{
        $hargaExtra = 0;
    }
    $tmkn = 0;
    $mknmnm = "SELECT * FROM PESAN_MENU, TB_MENU WHERE PESAN_MENU.ID_MENU=TB_MENU.ID_MENU AND ID_INMESS='$IDINMESS'";
    $Parmknmnm = ociparse($link, $mknmnm);
    ociexecute($Parmknmnm) or die();
    while ($DataMakan = oci_fetch_array($Parmknmnm)){
        //echo $DataMakan['HARGA'].'|'.$DataMakan['QTY_PESAN'].'|'.$DataMakan['ID_PESANMENU'].'</br>';
        $titemmkn = $DataMakan['HARGA']*$DataMakan['QTY_PESAN'];
        $tmkn=$tmkn+$titemmkn;
    }
    $TotalBayar = $hargainap+$hargaExtra+$tmkn;
    return $TotalBayar;
}
function TotMakananTgl($Tgl){
    include "conn.php";
    $TOT = 0;
    $DfMess = "SELECT * FROM PESAN_MENU, TB_MENU,INMESS WHERE PESAN_MENU.ID_MENU=TB_MENU.ID_MENU AND PESAN_MENU.ID_INMESS=INMESS.ID_INMESS AND substr(tanggal_bayar,0,10)='$Tgl'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
        $byr = $DataMess['HARGA']*$DataMess['QTY_PESAN'];
        $TOT = $TOT+$byr;
    }
    return $TOT;
}
function TotExbedidPeg($IDINMESS){
    include "conn.php";
    $DfMess = "SELECT INMESS.EXTRA_BED,DAPOK_PEGAWAI.NIP, DAPOK_PEGAWAI.NAMA_PEGAWAI, INMESS.JENIS_PELANGGAN,INMESS.ID_INMESS, TB_MESS.NAMA_MESS, KAMAR.NAMA_KAMAR,INMESS.TGL_CEKIN, INMESS.TGL_CEKOUT, KAMAR.HARGA_PEGAWAI FROM INMESS, DAPOK_PEGAWAI, KAMAR, TB_MESS WHERE INMESS.KODE_PELANGGAN = DAPOK_PEGAWAI.NIP AND KAMAR.ID_KAMAR=INMESS.ID_KAMAR AND TB_MESS.ID_MESS=KAMAR.ID_MESS AND STATUS_BAYAR='SUDAH' AND INMESS.ID_INMESS='$IDINMESS'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);
    $masuk = dmY("$DataMess[TGL_CEKIN]");
    $keluar = dmY("$DataMess[TGL_CEKOUT]");
    $lama = Selisihhari("$masuk","$keluar");
    $hargainap = $lama*$DataMess['HARGA_PEGAWAI'];
    if (isset($DataMess['EXTRA_BED']) && !empty($DataMess['EXTRA_BED'])){
        $hargaExtra = 100000*$lama;
    }else{
        $hargaExtra = 0;
    }

return $hargaExtra;
}
function TotExbedtglPeg($tgl){
    include "conn.php";
    $ParMess = ociparse($link,"select * from INMESS where TGL_CEKIN='$tgl' AND JENIS_PELANGGAN='PEGAWAI'");
    $TotExtratgl = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
        $by = TotExbedidPeg("$DataMess[ID_INMESS]");
        $TotExtratgl = $TotExtratgl+$by;
        //echo $DataMess['ID_INMESS'];
    }
    return $TotExtratgl;
}
function TotExbedtglUmum($tgl){
    include "conn.php";
    $ParMess = ociparse($link,"select * from INMESS where TGL_CEKIN='$tgl' AND JENIS_PELANGGAN='UMUM'");
    $TotExtratgl = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
        $by = TotExbedIDUmum("$DataMess[ID_INMESS]");
        $TotExtratgl = $TotExtratgl+$by;
        //echo $DataMess['ID_INMESS'];
    }
    return $TotExtratgl;
}
function TotExbedIDUmum($IDINMESS){
    include "conn.php";
    $DfMess = "SELECT KAMAR.HARGA_UMUM, PELANGGAN_UMUM.NIK,INMESS.EXTRA_BED, PELANGGAN_UMUM.NAMA_PELANGGAN, INMESS.JENIS_PELANGGAN, TB_MESS.NAMA_MESS, KAMAR.NAMA_KAMAR,INMESS.TGL_CEKIN, INMESS.TGL_CEKOUT,INMESS.ID_INMESS  
FROM INMESS, PELANGGAN_UMUM, KAMAR, TB_MESS 
WHERE INMESS.KODE_PELANGGAN = PELANGGAN_UMUM.NIK
 AND KAMAR.ID_KAMAR=INMESS.ID_KAMAR 
 AND TB_MESS.ID_MESS=KAMAR.ID_MESS 
 AND STATUS_BAYAR='SUDAH' AND INMESS.ID_INMESS='$IDINMESS'";
    $ParMess = ociparse($link,$DfMess);
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);
    $masuk = dmY("$DataMess[TGL_CEKIN]");
    $keluar = dmY("$DataMess[TGL_CEKOUT]");
    $lama = Selisihhari("$masuk","$keluar");
    $hargainap = $lama*$DataMess['HARGA_UMUM'];
    if (isset($DataMess['EXTRA_BED']) && !empty($DataMess['EXTRA_BED'])){
        $hargaExtra = 100000*$lama;
    }else{
        $hargaExtra = 0;
    }
    return $hargaExtra;
}
function selisih($waktu_dibanding,$waktu_pembanding){
    $tahun1=substr($waktu_dibanding,0,4);
    $bulan1=substr($waktu_dibanding,5,2);
    $tanggal1=substr($waktu_dibanding,8,2);
    $jam1=substr($waktu_dibanding,11,2);
    $menit1=substr($waktu_dibanding,14,2);
    $detik1=substr($waktu_dibanding,17,2);

    $waktu_tujuan = mktime($jam1,$menit1,$detik1,$bulan1,$tanggal1,$tahun1);

    //tentukan waktu saat ini
    $tahun2     =substr($waktu_pembanding,0,4);
    $bulan2     =substr($waktu_pembanding,5,2);
    $tanggal2   =substr($waktu_pembanding,8,2);
    $jam2       =substr($waktu_pembanding,11,2);
    $menit2     =substr($waktu_pembanding,14,2);
    $detik2     =substr($waktu_pembanding,17,2);

    $waktu_sekarang = mktime($jam2,$menit2,$detik2,$bulan2,$tanggal2,$tahun2);

    $selisih_waktu=$waktu_sekarang-$waktu_tujuan;

    $jumlah_hari = floor($selisih_waktu/86400);

//Untuk menghitung jumlah dalam satuan jam:
    $sisa = $selisih_waktu % 86400;
    $jumlah_jam = floor($sisa/3600);
    $jmljam = sprintf("%02d", $jumlah_jam);

//Untuk menghitung jumlah dalam satuan menit:
    $sisa = $sisa % 3600;
    $jumlah_menit = floor($sisa/60);
    $jmlmenit = sprintf("%02d", $jumlah_menit);
//Untuk menghitung jumlah dalam satuan detik:
    $sisa = $sisa % 60;
    $jumlah_detik = floor($sisa/1);
    $jmldetik = sprintf("%02d", $jumlah_detik);

    $br="$jumlah_hari D $jmljam:$jmlmenit:$jmldetik";
    return $br;
}
function selisih_jam($waktu_dibanding,$waktu_pembanding){
    $tahun1=substr($waktu_dibanding,0,4);
    $bulan1=substr($waktu_dibanding,5,2);
    $tanggal1=substr($waktu_dibanding,8,2);
    $jam1=substr($waktu_dibanding,11,2);
    $menit1=substr($waktu_dibanding,14,2);
    $detik1=substr($waktu_dibanding,17,2);

    $waktu_tujuan = mktime($jam1,$menit1,$detik1,$bulan1,$tanggal1,$tahun1);

    //tentukan waktu saat ini
    $tahun2     =substr($waktu_pembanding,0,4);
    $bulan2     =substr($waktu_pembanding,5,2);
    $tanggal2   =substr($waktu_pembanding,8,2);
    $jam2       =substr($waktu_pembanding,11,2);
    $menit2     =substr($waktu_pembanding,14,2);
    $detik2     =substr($waktu_pembanding,17,2);

    $waktu_sekarang = mktime($jam2,$menit2,$detik2,$bulan2,$tanggal2,$tahun2);

    $selisih_waktu=$waktu_sekarang-$waktu_tujuan;

    $jumlah_hari = floor($selisih_waktu/86400);

//Untuk menghitung jumlah dalam satuan jam:
    $sisa = $selisih_waktu % 86400;
    $jumlah_jam = floor($sisa/3600);
    $jmljam = sprintf("%02d", $jumlah_jam);

//Untuk menghitung jumlah dalam satuan menit:
    $sisa = $sisa % 3600;
    $jumlah_menit = floor($sisa/60);
    $jmlmenit = sprintf("%02d", $jumlah_menit);
//Untuk menghitung jumlah dalam satuan detik:
    $sisa = $sisa % 60;
    $jumlah_detik = floor($sisa/1);
    $jmldetik = sprintf("%02d", $jumlah_detik);

    $br="$jmljam:$jmlmenit:$jmldetik";
    return $br;
}
function Selisihhari($TglAwal,$TglAkhir){
    $Diff = strtotime($TglAkhir)-strtotime($TglAwal);
    $Selisih = floor($Diff / (60 * 60 * 24));
    return $Selisih;
}
function TotKamarTglPegawai($tgl){
    include "conn.php";
    //include "FuncDetailInap.php";
    $ParMess = ociparse($link,"select * from INMESS where TGL_CEKIN='$tgl' AND JENIS_PELANGGAN='PEGAWAI'");
    $TotKamarTglPegawai = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
        $byr = TotalKamarIDPeg($DataMess['ID_INMESS']);
        $TotKamarTglPegawai = $TotKamarTglPegawai+$byr;
    }
    return $TotKamarTglPegawai;
}
function TotKamarTglUmum($tgl){
    include "conn.php";
    $ParMess = ociparse($link,"select * from INMESS where TGL_CEKIN='$tgl' AND JENIS_PELANGGAN='UMUM'");
    $TotKamarTglUmum = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
      $by = TotalKamarIDUmum("$DataMess[ID_INMESS]");
        $TotKamarTglUmum = $TotKamarTglUmum+$by;
    }
    return $TotKamarTglUmum;

}
function TotMakanTglUmum($tgl){
    include "conn.php";
    $ParMess = ociparse($link,"select * from INMESS where TGL_CEKIN='$tgl' AND JENIS_PELANGGAN='UMUM' AND STATUS_BAYAR='SUDAH'");
    $TotMakanTglUmum = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
        $byr = ByrMakan($DataMess['ID_INMESS']);
        $TotMakanTglUmum = $TotMakanTglUmum+$byr;
    }
    return $TotMakanTglUmum;
}
function TotMakanTglPegawai($tgl){
    include "conn.php";
    $ParMess = ociparse($link,"select * from INMESS where TGL_CEKIN='$tgl' AND JENIS_PELANGGAN='PEGAWAI' AND STATUS_BAYAR='SUDAH'");
    $TotMakanTglPegawai = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    while ($DataMess  = oci_fetch_array($ParMess)){
        $byr = ByrMakan($DataMess['ID_INMESS']);
        $TotMakanTglPegawai = $TotMakanTglPegawai+$byr;
    }
    return $TotMakanTglPegawai;
}
function PendapatanMessPerbulan($IDMESS, $thn){
    include "conn.php";
    $tbpegwai = 0;
    $tbumum = 0;
    $ParMess = ociparse($link,"SELECT * FROM INMESS, KAMAR, TB_MESS  WHERE INMESS.ID_KAMAR=KAMAR.ID_KAMAR AND KAMAR.ID_MESS=TB_MESS.ID_MESS AND TB_MESS.ID_MESS='$IDMESS' AND SUBSTR(INMESS.TANGGAL_BAYAR, 4,7)='$thn'");
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
        while ($DataMess  = oci_fetch_array($ParMess)){
            if ($DataMess['JENIS_PELANGGAN']=='PEGAWAI' && isset($DataMess['JENIS_PELANGGAN'])){
                $tpegawai = TotalBayarPegawai("$DataMess[ID_INMESS]");
                $tbpegwai = $tbpegwai+$tpegawai;
            }elseif($DataMess['JENIS_PELANGGAN']=='UMUM' && isset($DataMess['JENIS_PELANGGAN'])){
                $tumum = TotalBayarUmum("$DataMess[ID_INMESS]");
                $tbumum = $tbumum+$tumum;
            }
            $Tsemua = $tbpegwai+$tbumum;
        }
        if (isset($Tsemua)){
            $tampil = $Tsemua;
        }else{
            $tampil=0;
        }

    return $tampil;
}
function kamar_tersedia(){
	include "conn.php";
	$sql_kamar = oci_parse($link, "SELECT KAMAR.ID_KAMAR IDK, KAMAR.NAMA_KAMAR NMK,HARGA_PEGAWAI,HARGA_UMUM FROM KAMAR LEFT JOIN INMESS ON INMESS.ID_KAMAR=KAMAR.ID_KAMAR WHERE INMESS.ID_KAMAR IS NULL"); 
	ociexecute($sql_kamar);
	while ($kamar_ada = oci_fetch_array($sql_kamar)){
		$kmr[] = $kamar_ada;
	}
	if(!empty($kmr)){
	return $kmr;	
	}else{
	return null;
	}
}
function ListBiaya($my){
    session_start();
    include "conn.php";
    $tgl = str_replace("-", "", "$my");
    $sql_kamar = oci_parse($link, "SELECT * FROM BIAYA WHERE ID_MESS='$_SESSION[ID_MESS]' AND TO_CHAR(TGLBIAYA, 'MMYYYY')='$tgl'");
    ociexecute($sql_kamar);
    while ($kamar_aktip = oci_fetch_array($sql_kamar)){
        $kmr[] = $kamar_aktip;
    }
    if(!empty($kmr)){
        return $kmr;
    }else{
        return null;
    }
}

function kamar_aktip(){
	include "conn.php";
	$sql_kamar = oci_parse($link, "SELECT KAMAR.ID_KAMAR, KAMAR.NAMA_KAMAR,KAMAR.HARGA_PEGAWAI,KAMAR.HARGA_UMUM FROM KAMAR,INMESS WHERE KAMAR.ID_KAMAR=INMESS.ID_KAMAR"); 
	ociexecute($sql_kamar);
	while ($kamar_aktip = oci_fetch_array($sql_kamar)){
		$kmr[] = $kamar_aktip;
	}
	if(!empty($kmr)){
	return $kmr;	
	}else{
	return null;
	}
}

function KamarTersedia2(){
    include "conn.php";
    $query = oci_parse($link, "SELECT * FROM KAMAR");
    oci_execute($query);
    while ($dd = oci_fetch_array($query)){
        $kmrterpakai = KamarTerpakai($dd['ID_KAMAR']);
        $Datakamar = array(
          'TypeKamar' => $dd['NAMA_KAMAR'] ,
          'Harga' => $dd['HARGA_PEGAWAI'] ,
          'MAX_TAMU' => $dd['MAX_TAMU'] ,
        );
    }
}

function KamarTerpakai($IDDKAMAR){
    include "conn.php";
    $query = oci_parse($link, "SELECT COUNT(INMESS.ID_INMESS) AS JMLTERPAKAI FROM INMESS where ID_KAMAR='$IDDKAMAR'");
    oci_execute($query);
    $dl = oci_fetch_array($query);
    $jmlterpakai =  $dl['JMLTERPAKAI'];
    return $jmlterpakai;
}
function KamarReservasi($IDDKAMAR){
    include "conn.php";
    $query = oci_parse($link, "   SELECT COUNT(ID_RESERVASI) JMLRE FROM RESERVASI WHERE STATUS='AKTIF' AND JUMLAH_KAMAR='$IDDKAMAR'");
    oci_execute($query);
    $dl = oci_fetch_array($query);
    $jmlterpakai =  $dl['JMLRE'];
    return $jmlterpakai;
}

function ListKamar(){
    include "conn.php";
    $query = oci_parse($link, "SELECT * FROM KAMAR");
    oci_execute($query);
    while ($dd = oci_fetch_array($query)){
        //cari kamar terpakai
        $Terpakai = KamarTerpakai($dd['ID_KAMAR']);
        $Reserved = KamarReservasi($dd['ID_KAMAR']);
        if ($dd['TYPE_KAMAR']=="PERKAMAR"){
            $Tersisa = $dd['JML_UNIT']-($Terpakai+$Reserved);
        }else{
            $Tersisa = $dd['MAX_TAMU']-($Terpakai+$Reserved);
        }

        $listkamar[] = array(
            'ID_KAMAR'=>$dd['ID_KAMAR'],
            'ID_MESS'=>$dd['ID_MESS'],
            'NAMA_KAMAR'=>$dd['NAMA_KAMAR'],
            'HARGA_PEGAWAI'=>$dd['HARGA_PEGAWAI'],
            'HARGA_UMUM'=>$dd['HARGA_UMUM'],
            'TYPE_KAMAR'=>$dd['TYPE_KAMAR'],
            'JML_UNIT'=>$dd['JML_UNIT'],
            'MAX_TAMU'=>$dd['MAX_TAMU'],
            'Reserved'=>$Reserved,
            'Terpakai'=>$Terpakai,
            'Tersisa'=>$Tersisa
        );
    }
    return $listkamar;
}