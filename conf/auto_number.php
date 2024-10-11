<?php
function get_new_id($fieldname,$table){
    include "conn.php";
    $q="select MAX($fieldname) AS maksimal FROM $table";
    $res=ociparse($link,$q);
    ociexecute($res);
    $row = oci_fetch_array($res);
    $angka=$row['MAKSIMAL'];
    $baru = $angka+1;
    return $baru;
}
?>