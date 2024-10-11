<?php
include "conf/Function.php";
$my = $_POST['item_id'];
$t = ListBiaya($my);
echo json_encode($t);
?>