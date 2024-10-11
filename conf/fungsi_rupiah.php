<?php
function format_rupiah($angka){
  $rupiah= number_format($angka,0,',','.');
    $tampil = "Rp. $rupiah";
  return $tampil;
}
function format_rupiah2($angka){
  $rupiah= number_format($angka,0,',','.');
  $tampil = "$rupiah";
  return $tampil;
}
?> 
