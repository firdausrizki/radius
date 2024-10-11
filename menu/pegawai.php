<section class="sidebar">
    <div class="user-panel">
        <div class="pull-left image"><img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image"></div>
        <div class="pull-left info">
            <p><?php echo $_SESSION['NAMA'] ?></p>
            <p class="designation"><?php echo $_SESSION['LEVELUSER'] ?></p>
        </div>
    </div>
    <!-- Sidebar Menu-->
    <ul class="sidebar-menu">
        <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>

        <li class="treeview"><a href="#"><i class="fa fa-book"></i><span>Laporan</span><i class="fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="?page=Tamu"><i class="fa fa-circle-o"></i> Tamu</a></li>
                <li><a href="?page=Transaksi"><i class="fa fa-circle-o"></i> Transaksi</a></li>
            </ul>
        </li>
    </ul>
</section>