<?php
    session_start();
    ob_start();
    include "conf/auto_number.php";
    include "conf/conn.php";
    include "conf/fungsi_rupiah.php";
    include "conf/Function.php";

    if (isset($_GET['out'])&& $_GET['out']=='y'){
        session_destroy();
        echo "<script>alert('Anda Berhasil Logout');window.location='index.php'</script>";
    }
if (isset($_POST['login'])){
    $username      = trim($_POST['username']);
    $password      = trim($_POST['password']);
    $SQLogin = mysqli_query($conn, "SELECT * from pengguna where username='$username' AND password='$password'");
    $DataLogin  = mysqli_fetch_array($SQLogin);
    echo $DataLogin['username'];
   if (!empty($DataLogin['username'])){
       $_SESSION['username']  = $DataLogin['username'];
       $_SESSION['idup']  = $DataLogin['idup'];
       header('location:index.php');
   }elseif(empty($DataLogin['username'])){
       header('location:login.php?Error');
   }
}else{
     if (empty($_SESSION['username'])){
         header('location:login.php');
     }
 }
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
    <link rel="stylesheet" type="text/css" href="css/custom.css">
	<!-- daterange picker -->
	<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">
    <link rel="shortcut icon" href="images/PLN.png" />
    <title>Radius User Management</title>
	<script src="js/jquery.js"></script>
<style>
.uppercase { text-transform: uppercase; }
</style>
  </head>
  <body class="sidebar-mini fixed">
    <div class="wrapper">
      <!-- Navbar-->
      <header class="main-header hidden-print"><a class="logo" href="index.php">USER RADIUS</a>
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button--><a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
          <!-- Navbar Right Menu-->
          <div class="navbar-custom-menu">
            <ul class="top-nav">
              <!--Notification Menu-->
              <li class="dropdown notification-menu"><a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell-o fa-lg"><span class="bubble" id="jumlah">0</span></i></a>
                <ul class="dropdown-menu">
                  <li><a class="media" href="?page=ResPegawai"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-user-circle fa-stack-1x fa-inverse"></i></span></span>
                      <div class="media-body"><span class="block"><strong>Reservasi Pegawai</strong></span><span class="bubble1" id="jumlah1">0</span> Notifikasi</div></a></li>
				  <li><a class="media" href="?page=ResUmum"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-user-circle-o fa-stack-1x fa-inverse"></i></span></span>
                      <div class="media-body"><span class="block"><strong>Reservasi Umum</strong></span><span class="bubble1" id="jumlah2">0</span> Notifikasi</div></a></li>	  
                  <li><a class="media" href="?page=CekInOut2"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-address-card-o fa-stack-1x fa-inverse"></i></span></span>
                      <div class="media-body"><span class="block"><strong>Cek Out</strong></span><span class="bubble2" id="jumlah3">0</span> Notifikasi</div></a></li>
                </ul>
              </li>
              <!-- User Menu-->
              <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu">
                  <li><a href="?out=y" class="btn btn-danger" onclick="return confirm('Yakin Ka Kalua ?')"><i class="fa fa-sign-out fa-lg"></i> logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print">
          <?php
                include 'menu/admin.php';
          ?>
      </aside>
      <div class="content-wrapper">
          <?php
            if (isset($_GET['page']) or !empty($_GET['page'])){
                $file =  'views/'.$_GET['page'].'.php';
                if (file_exists($file)){
                    include $file;
                }else{
                    header('location:dist/pages/page-error.html');
                }
            }else{
                include "views/dashboard.php";
            }
          ?>
      </div>
    </div>
    <!-- Javascripts-->
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script src="js/main.js"></script>
	<!-- date-range-picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
	<script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('.sampleTable').DataTable();</script>
    <!-- Select 2-->
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>
   <!-- inputmask curency -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>
    <script type="text/javascript">
		
	
        $('#sl').click(function(){
            $('#tl').loadingBtn();
            $('#tb').loadingBtn({ text : "Signing In"});
        });

        $('#el').click(function(){
            $('#tl').loadingBtnComplete();
            $('#tb').loadingBtnComplete({ html : "Sign In"});
        });

        $('.demoDate').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true
        });

        $('.demoSelect').select2();
    </script>
	
	 <script type="text/javascript">
      $('#tgldari').datepicker({
      	format: "dd-mm-yyyy",
      	autoclose: true,
      	todayHighlight: true,
      });
	  $('#tgldari1').datepicker({
      	format: "dd-mm-yyyy",
      	autoclose: true,
      	todayHighlight: true
      });
	  
	  $('#tglsampai1').datepicker({
      	format: "dd-mm-yyyy",
      	autoclose: true,
      	todayHighlight: true
      });
	  
	  $('#tgldari2').datepicker({
      	format: "dd-mm-yyyy",
      	autoclose: true,
      	todayHighlight: true
      });
	  
	  $('#tglsampai2').datepicker({
      	format: "dd-mm-yyyy",
      	autoclose: true,
      	todayHighlight: true
      });

      $(".select2").select2();
      $("#demoSelect").select2();
	 
		$('#datepicker').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			startDate: '0',
			endDate: ''
		});
      $('.dp').datepicker({
          format: "mm/dd/yyyy",
          autoclose: true,
          startDate: '0',
          endDate: '',
          todayHighlight: true
      });

		function CheckSampai(){
			var mulai = $("#datepicker").val();
			var n = 0;
			var n = mulai;
			//var n = '28-02-2019';
			console.log(n);
			 $('#tglsampai').datepicker({
			 format: "dd-mm-yyyy",
			 autoclose: true,
      		 startDate: n,
			 endDate: ''
      });
		}
		Inputmask.extendAliases({
			  pesos: {
						prefix: "₱ ",
						groupSeparator: ".",
						alias: "numeric",
						placeholder: "0",
						autoGroup: !0,
						digits: 0,
						digitsOptional: !1,
						clearMaskOnLostFocus: !1
					}
			});

    </script>
	<script type="text/javascript">
    $(document).ready(function () {

        $("#do1").focusout(function () {
            $(".hitung1").val('');
            $("#currency2").val('');
        });
        $("#reservation").focusout(function () {
            $(".hitung").val('');
            $("#currency1").val('');
        });

        $(".hitung").keyup(function(){
            var jlin = $(".hitung");
            for(var i = 0; i < jlin.length; i++){

            }
            var tanggal = $("#reservation").val();
            var hsplit = tanggal.split("-");
            var tglmulai = hsplit[0];
            var tglakhir = hsplit[1];
            var jmlhari =Durasihari(tglmulai,tglakhir);
            var hasilh =HitungMasal(jmlhari);
            $("#currency1").val(hasilh);
            hasilh = '';
        });

        $(".hitung").focusout(function(){
            var tanggal = $("#reservation").val();
            var hsplit = tanggal.split("-");
            var tglmulai = hsplit[0];
            var tglakhir = hsplit[1];
            var jmlhari =Durasihari(tglmulai,tglakhir);
            var hasilh =HitungMasal(jmlhari);
            $("#currency1").val(hasilh);
            hasilh = '';
        });
        function HitungMasal(jmlhari) {
            var jlin = $(".hitung");
            var sisa = $(".sisakamar");
            var hrg = $(".hrg");
            var tbayarpeg = $(".tbayarpeg");
            var total = 0;
            var totalbayar = 0;
            for(var i = 0; i < jlin.length; i++){
                var jmreservasi = $(jlin[i]).val();
                var sisakamar = $(sisa[i]).val();
                var harga = $(hrg[i]).val();
                if (jmreservasi!=''){
                    if (jmreservasi>sisakamar){
                        alert('jumlah kamar tidak cukup');
                        $(jlin[i]).val("");
                    }else{
                        total = Number(jmreservasi)*(Number(harga)*Number(jmlhari));
                        $(tbayarpeg[i]).val(total);
                        totalbayar = Number(totalbayar)+Number(total);
                    }
                }else{
                    $(tbayarpeg[i]).val('');
                }
            }
            return totalbayar;
        }
        $(".hitung1").keyup(function(){
            var tanggal = $("#reservation1").val();
            var tglmulai = $("#di1").val();
            var tglakhir =  $("#do1").val();
            var jmlhari =Durasihari(tglmulai,tglakhir);
            var hasilh =HitungMasal1(jmlhari);
            console.log(tglmulai);
            $("#currency2").val(hasilh);
            hasilh = '';
        });

        $(".hitung1").focusout(function(){
            var tanggal = $("#reservation1").val();
            var tglmulai = $("#di1").val();
            var tglakhir =  $("#do1").val();
            var jmlhari =Durasihari(tglmulai,tglakhir);
            var hasilh =HitungMasal1(jmlhari);
            $("#currency2").val(hasilh);
            hasilh = '';
        });

        function HitungMasal1(jmlhari) {
            var jlin = $(".hitung1");
            var sisa = $(".sisakamar1");
            var hrg = $(".hrg1");
            var tbayarpeg = $(".tbayarpeg1");
            var total = 0;
            var totalbayar = 0;
            for(var i = 0; i < jlin.length; i++){
                var jmreservasi = $(jlin[i]).val();
                var sisakamar = $(sisa[i]).val();
                var harga = $(hrg[i]).val();
                if (jmreservasi!=''){
                    if (jmreservasi>sisakamar){
                        alert('jumlah kamar tidak cukup');
                        $(jlin[i]).val("");
                    }else{
                        total = Number(jmreservasi)*(Number(harga)*Number(jmlhari));
                        $(tbayarpeg[i]).val(total);
                        totalbayar = Number(totalbayar)+Number(total);
                    }
                }else{
                    $(tbayarpeg[i]).val('');
                }
            }
            return totalbayar;
        }
        function Durasihari(tglawal,tglakshir) {
            var tglexsplit = tglawal.split("/");
            var tglexsplit1 = tglakshir.split("/");
            var tglawals = tglexsplit[2].trim()+'/'+tglexsplit[0].trim()+'/'+tglexsplit[1].trim();
            var tglakhir2 = tglexsplit1[2].trim()+'/'+tglexsplit1[0].trim()+'/'+tglexsplit1[1].trim();
            var oneday = 24*60*60*1000;
            var firstdate = new Date(tglawals);
            var lastdate = new Date(tglakhir2);
            var jumlah_hari = Math.round(Math.round(lastdate.getTime() - firstdate.getTime()) / oneday);
            if(jumlah_hari==0){
                jumlah_hari = 1;
            }else{
                jumlah_hari = jumlah_hari;
            }
            return jumlah_hari;
        }

			  $("#currency1").inputmask({ alias : "currency", prefix: 'Rp ' });
			  $(".RpFormat").inputmask({ alias : "currency", prefix: 'Rp ' });
			  $("#currency2").inputmask({ alias : "currency", prefix: 'Rp ' });
			  $("#currency3").inputmask({ alias : "pesos" });

        $('.numbersOnly').keypress(function(event) {
            var charCode = (event.which) ? event.which : event.keyCode
            if ((charCode >= 48 && charCode <= 57)
                || charCode == 46
                || charCode == 45
				|| charCode == 8)
                return true;
            return false;
        });
		//Date range picker
        var tomorrow = moment().add(1, 'days');
    $('#reservation').daterangepicker({
		format: "dd-mm-yyyy",
		minDate: tomorrow
	});
    //Date range picker
    $('#reservation1').daterangepicker({
		format: "dd-mm-yyyy",
		minDate: tomorrow
	});

    });
	 
		
    
   
    </script>
  </body>
</html>