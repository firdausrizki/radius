<?php
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.17.0.15)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))";
$link = OCILogon("INVOICE", "#123pln123", $db)or die("<h3 class=menu01> <font color ='red'>
<div style='font-size:36px'>Tidak Terhubung ke Server Database.....</div></h3>");
