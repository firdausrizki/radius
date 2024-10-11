<?php session_start();
ob_start();
include "conf/auto_number.php";
include "conf/conn.php";
include "conf/fungsi_rupiah.php";
include "conf/fungsi_indotgl.php";
include "conf/fungsi_selisih.php";


if (isset($_GET['id'])){
    $DetInmes = "SELECT * FROM INMESS where ID_INMESS='$_GET[id]'";
    $ParInmess = ociparse($link,$DetInmes);
    ociexecute($ParInmess);
    $Detailinmess = oci_fetch_array($ParInmess);
    // detail Pelanggan

    if ($Detailinmess['JENIS_PELANGGAN']=="PEGAWAI"){
        $DetPel = "SELECT * FROM DAPOK_PEGAWAI WHERE NIP='$Detailinmess[KODE_PELANGGAN]'";
        $Parpelanggan = ociparse($link,$DetPel);
        ociexecute($Parpelanggan);
        $DetailPelanggan = oci_fetch_array($Parpelanggan);
        $NamaPelanggan = $DetailPelanggan['NAMA_PEGAWAI'];
        $Email = $DetailPelanggan['EMAIL'];
        $KONTAK = $DetailPelanggan['KONTAK'];
        $jenispegawai = "PEGAWAI";
    }elseif ($Detailinmess['JENIS_PELANGGAN']=="UMUM"){
        $DetPel = "SELECT * FROM PELANGGAN_UMUM WHERE ID_PEL_UMUM='$Detailinmess[KODE_PELANGGAN]'";
        $Parpelanggan = ociparse($link,$DetPel);
        ociexecute($Parpelanggan);
        $DetailPelanggan = oci_fetch_array($Parpelanggan);
        $NamaPelanggan = $DetailPelanggan['NAMA_PELANGGAN'];
        $Email = $DetailPelanggan['EMAIL'];
        $KONTAK = $DetailPelanggan['KONTAK'];
        $jenispegawai = "Pelanggan Umum";
    }

    $ExplodeIn = explode("/","$Detailinmess[TGL_CEKIN]");
    $ExplodeOut= explode("/","$Detailinmess[TGL_CEKOUT]");
        $TGLCEKIN   =   $ExplodeIn['0'];
        $BULANCEKIN =   $ExplodeIn['1'];
        $TAHUNCEKIN =   $ExplodeIn['2'];

        $TGL_CEKIN = $TAHUNCEKIN.'-'.$BULANCEKIN.'-'.$TGLCEKIN;

        $TGLCEKOUT   = $ExplodeOut['0'];
        $BULANCEKOUT = $ExplodeOut['1'];
        $TAHUNCEKOUT = $ExplodeOut['2'];

    $TGL_CEKOUT = $TAHUNCEKOUT.'-'.$BULANCEKOUT.'-'.$TGLCEKOUT;

$jmlInap = Selisihhari("$TGL_CEKIN","$TGL_CEKOUT");

//Detail kamar
    $Detailkamar = "SELECT * FROM KAMAR WHERE ID_KAMAR='$Detailinmess[ID_KAMAR]'";
    $ParDetkamar = ociparse($link,$Detailkamar);
    ociexecute($ParDetkamar);
    $Detkamar = oci_fetch_array($ParDetkamar);
if ($Detailinmess['JENIS_PELANGGAN']=="PEGAWAI"){
    $Harga = format_rupiah($Detkamar['HARGA_PEGAWAI']);
    $total = format_rupiah($Detkamar['HARGA_PEGAWAI']*$jmlInap);
}elseif($Detailinmess['JENIS_PELANGGAN']=="UMUM"){
    $Harga = format_rupiah($Detkamar['HARGA_UMUM']);
    $total = format_rupiah($Detkamar['HARGA_UMUM']*$jmlInap);
}
}
$TglKini = date("Y-m-d");
$TamTGLKini = tgl_indo($TglKini);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Vali Admin</title>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
  </head>
  <body class="sidebar-mini fixed">
    <div class="wrapper">
      <!-- Navbar-->
      <header class="main-header hidden-print"><a class="logo" href="index.html">Vali</a>
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button--><a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
          <!-- Navbar Right Menu-->
          <div class="navbar-custom-menu">
            <ul class="top-nav">
              <!--Notification Menu-->
              <li class="dropdown notification-menu"><a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell-o fa-lg"></i></a>
                <ul class="dropdown-menu">
                  <li class="not-head">You have 4 new notifications.</li>
                  <li><a class="media" href="javascript:;"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                      <div class="media-body"><span class="block">Lisa sent you a mail</span><span class="text-muted block">2min ago</span></div></a></li>
                  <li><a class="media" href="javascript:;"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                      <div class="media-body"><span class="block">Server Not Working</span><span class="text-muted block">2min ago</span></div></a></li>
                  <li><a class="media" href="javascript:;"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                      <div class="media-body"><span class="block">Transaction xyz complete</span><span class="text-muted block">2min ago</span></div></a></li>
                  <li class="not-footer"><a href="#">See all notifications.</a></li>
                </ul>
              </li>
              <!-- User Menu-->
              <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu">
                  <li><a href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
                  <li><a href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>
                  <li><a href="page-login.html"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image"><img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image"></div>
            <div class="pull-left info">
              <p>John Doe</p>
              <p class="designation">Frontend Developer</p>
            </div>
          </div>
          <!-- Sidebar Menu-->
          <ul class="sidebar-menu">
            <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
            <li class="treeview"><a href="#"><i class="fa fa-laptop"></i><span>UI Elements</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="bootstrap-components.html"><i class="fa fa-circle-o"></i> Bootstrap Elements</a></li>
                <li><a href="http://fontawesome.io/icons/" target="_blank"><i class="fa fa-circle-o"></i> Font Icons</a></li>
                <li><a href="ui-cards.html"><i class="fa fa-circle-o"></i> Cards</a></li>
                <li><a href="widgets.html"><i class="fa fa-circle-o"></i> Widgets</a></li>
              </ul>
            </li>
            <li><a href="charts.html"><i class="fa fa-pie-chart"></i><span>Charts</span></a></li>
            <li class="treeview"><a href="#"><i class="fa fa-edit"></i><span>Forms</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="form-components.html"><i class="fa fa-circle-o"></i> Form Components</a></li>
                <li><a href="form-custom.html"><i class="fa fa-circle-o"></i> Custom Components</a></li>
                <li><a href="form-samples.html"><i class="fa fa-circle-o"></i> Form Samples</a></li>
                <li><a href="form-notifications.html"><i class="fa fa-circle-o"></i> Form Notifications</a></li>
              </ul>
            </li>
            <li class="treeview"><a href="#"><i class="fa fa-th-list"></i><span>Tables</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="table-basic.html"><i class="fa fa-circle-o"></i> Basic Tables</a></li>
                <li><a href="table-data-table.html"><i class="fa fa-circle-o"></i> Data Tables</a></li>
              </ul>
            </li>
            <li class="treeview"><a href="#"><i class="fa fa-file-text"></i><span>Pages</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="blank-page.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                <li><a href="page-login.html"><i class="fa fa-circle-o"></i> Login Page</a></li>
                <li><a href="page-lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen Page</a></li>
                <li><a href="page-user.html"><i class="fa fa-circle-o"></i> User Page</a></li>
                <li><a href="page-invoice.html"><i class="fa fa-circle-o"></i> Invoice Page</a></li>
                <li><a href="page-calendar.html"><i class="fa fa-circle-o"></i> Calendar Page</a></li>
                <li><a href="page-mailbox.html"><i class="fa fa-circle-o"></i> Mailbox</a></li>
                <li><a href="page-error.html"><i class="fa fa-circle-o"></i> Error Page</a></li>
              </ul>
            </li>
            <li class="treeview"><a href="#"><i class="fa fa-share"></i><span>Multilevel Menu</span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="blank-page.html"><i class="fa fa-circle-o"></i> Level One</a></li>
                <li class="treeview"><a href="#"><i class="fa fa-circle-o"></i><span> Level One</span><i class="fa fa-angle-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="blank-page.html"><i class="fa fa-circle-o"></i> Level Two</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i><span> Level Two</span></a></li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
        </section>
      </aside>
      <div class="content-wrapper">
        <div class="page-title hidden-print">
          <div>
            <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
            <p>A Printable Invoice Format</p>
          </div>
          <div>
            <ul class="breadcrumb">
              <li><i class="fa fa-home fa-lg"></i></li>
              <li><a href="#">Invoice</a></li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <section class="invoice">
                <div class="row">
                  <div class="col-xs-12">
                    <h2 class="page-header"><i class="fa fa-globe"></i> INVOICE <small class="pull-right"> <?php echo $TamTGLKini ?></small></h2>
                  </div>
                </div>
                <div class="row invoice-info">
                  <div class="col-xs-4">
                    <address><strong><?php echo "$Detailinmess[NAMA_PELANGGAN]"?></strong><br><?php echo "$KONTAK"?><br><?php echo $Email?><br><?php echo $jenispegawai?><br></address>
                  </div>
                  <div class="col-xs-4"><strong>Check-in:</strong>
                    <address><?php echo tgl_indo($TGL_CEKIN)?><br><?php echo " $Detailinmess[WAKTU_CEKIN]"?><br><?php if (!empty($Detailinmess['EXTRA_BED'])){echo "<i style='color: #009688'>EXTRA BED</i>";}?><br><?php echo ""?><br></address>
                  </div>
                  <div class="col-xs-4"><b>Check-out:</b></br> <?php echo tgl_indo($TGL_CEKOUT)?><br><b><?php echo "$Detailinmess[WAKTU_CEKOUT]"?></b> <br><b></b> </div>
                </div>
                <div class="row">
                    <h4 class="page-header"><i class="fa fa-list"></i> Detail :</h4>
                  <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Nama Layanan</th>
                          <th>Harga</th>
                          <th>QTY</th>
                          <th>TOTAL</th>
                        </tr>
                      </thead>
                      <tbody>

                        <tr>
                          <td>Sewa Kamar<?php echo $Detkamar['NAMA_KAMAR']?></td>
                          <td><?php echo $Harga ?></td>
                          <td><?php echo $jmlInap?> Hari</td>
                          <td align="right"><?php echo $total?></td>
                        </tr>
                        <?php
                        if (!empty($Detailinmess['EXTRA_BED'])){
                           echo "
                           <tr>
                               <td><i>EXTRA BED</i></td>
                               <td>Rp. 100.000</td>
                               <td>1 Unit</td>
                               <td align=\"right\">Rp. 100.000</td>
                            </tr>
                           
                           ";
                        }
                        $tlmakan = 0;
                        $Makanan = "SELECT * FROM TB_MENU,PESAN_MENU WHERE TB_MENU.ID_MENU=PESAN_MENU.ID_MENU AND ID_INMESS='$Detailinmess[ID_INMESS]'";
                        $ParMakanan = ociparse($link,$Makanan);
                        ociexecute($ParMakanan);
                        while ($DetMakanan = oci_fetch_array($ParMakanan)){
                            $TotHarga = format_rupiah($DetMakanan['HARGA']*$DetMakanan['QTY_PESAN']);
                            $Hrgmenu = format_rupiah($DetMakanan['HARGA']);
                            echo "
                           <tr>
                               <td>$DetMakanan[NM_MENU]</td>
                               <td>$Hrgmenu</td>
                               <td>$DetMakanan[QTY_PESAN] Porsi</td>
                               <td align=\"right\">$TotHarga</td>
                            </tr>
                           
                           ";
                            $Makan = $DetMakanan['HARGA']*$DetMakanan['QTY_PESAN'];
                            $tlmakan = $tlmakan+$Makan;
                        }
                        //total
                        $Inap = $Detkamar['HARGA_PEGAWAI']*$jmlInap;

                        if (!empty($Detailinmess['EXTRA_BED'])){
                            $SemuaTotal = format_rupiah($Inap+$tlmakan+100000);
                        }else{
                            $SemuaTotal = format_rupiah($Inap+$tlmakan);
                        }
                        ?>
                        <tr>
                            <td colspan="3">TOTAL</td>
                            <td align="right"><?php echo "$SemuaTotal"?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row hidden-print mt-20">
                  <div class="col-xs-12 text-right"><a class="btn btn-primary" href="javascript:window.print();" target="_blank"><i class="fa fa-print"></i> Print</a></div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Javascripts-->
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript">$('body').removeClass("sidebar-mini").addClass("sidebar-collapse");</script>
  </body>
</html>