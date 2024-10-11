<div class="page-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> List User External Radius</h1>
    </div>
    <div>
        <ul class="breadcrumb">
            <li><i class="fa fa-home fa-lg"></i> Dashboard</li>
            <li><a href="#">List User</a></li>
        </ul>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="table-responsive">
                    <?php
                    if (isset($_SESSION['idup']) && $_SESSION['idup']==""){ ?>
                        <div class="col-lg-6">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Filter UP3</label>
                                    <select name="idup" id="" class="form-control" onchange="submit()">
                                        <option value="">Pilih</option>
                                        <?php
                                        $up = mysqli_query($conn, "select * from up");
                                        while ($up3 = mysqli_fetch_assoc($up)){
                                            echo "<option value='$up3[idup]'>$up3[NamaUP]</option>";
                                        }
                                        ?>
                                        <option value="">UIW SUMBAR</option>
                                        <option value="Semua">SEMUA DATA</option>
                                    </select>
                                </div>
                            </form>

                        </div>
                        <?php
                    }else{
                        $Fileter = "where up.idup='$_SESSION[idup]'";
                    }

                    ?>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Instansi</th>
                            <th>Alamat</th>
                            <th>Allow</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($_POST['idup'])){
                            if ($_POST['idup']=="Semua"){
                                $Fileter = "";
                            }else{
                                $Fileter = "where radcheck.idup='$_POST[idup]'";
                            }
                        }else{
                            $Fileter = "";
                        }
                        if (isset($_GET['Hapus'])){
                            $hapus = mysqli_query($conn, "DELETE from radcheck WHERE id='$_GET[Hapus]'");

                        }
                        if (isset($_POST['status'])){
                            $st = $_POST['status'];
                            $id = $_POST['id'];
                            $update = mysqli_query($conn, "UPDATE radcheck SET op='$st' WHERE id='$id'");
                        }
                        $no=1;
                        $Sql = mysqli_query($conn, "SELECT * FROM radcheck  $Fileter");
                        while ($item = mysqli_fetch_array($Sql)) {

                            if ($item['op']==':='){
                                $val = 'Yes';
                                $option = "<option value='!='>No</option>";
                            }else{
                                $option = "<option value=':='>Yes</option>";
                                $val = 'No';
                            }
                            echo "
                        <tr>
                            <td>$no <a href='index.php?page=LUser&Hapus=$item[id]' class=\"btn btn-warning btn-xs\" onclick=\"return confirm('Lai Yakin ka dihapus ?')\"><i class=\"fa fa-trash\"></i></a></td>
                            <td>$item[username]</td>
                            <td>$item[value]</td>
                            <td>$item[perusahaan]</td>
                            <td>$item[alamat]</td>
                            <td> 
                                <form action='' method='post' onchange='submit()'>
                                    <input type='hidden' name='id' value='$item[id]'>
                                    <select name='status' id=''>
                                    <option value='$item[op]'>$val</option>
                                    $option
                                    </select>
                                </form>
                            </td>
                        </tr>
                        ";
                            $no++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
