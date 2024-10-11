<?php
include "PHPMailer/src/PHPMailer.php";
include "PHPMailer/src/Exception.php";
include "PHPMailer/src/OAuth.php";
include "PHPMailer/src/POP3.php";
include "PHPMailer/src/SMTP.php";
include "conf/conn.php";

if (isset($_POST['resetpass'])){
    //check email exist
    $ParMess = ociparse($link,"select * from USER_MESS where EMAIL='$_POST[email]'");
    $TotExtratgl = 0;
    ociexecute($ParMess) or die ("Gagal terhubung ke database");
    $DataMess  = oci_fetch_array($ParMess);




    if (!empty($DataMess['EMAIL'])){

        $reset = ociparse($link,"update USER_MESS set PASSWORD='PLN2710' WHERE EMAIL='$_POST[email]'");
        $TotExtratgl = 0;
        ociexecute($reset) or die ("Gagal terhubung ke database");

        if ($reset){

            $sqlsvrmail ="
		SELECT SERVER,
			   USEREMAIL,
			   PASWD,
			   NAMA,
			   ALAMAT,
			   KETERANGAN
		  FROM MM_MAILCENTER
		";
            $stmt = ociparse($link,$sqlsvrmail);
            ociexecute($stmt) or die($sqlsvrmail);
            $row=oci_fetch_array($stmt,OCI_BOTH);
            if ($row)
            {
                $xserver    = trim($row['SERVER']);
                $xusermail  = trim($row['USEREMAIL']);
                $xpasswd    = trim($row['PASWD']);
                $xnama      = trim($row['NAMA']);
                $xalamat    = trim($row['ALAMAT']);
            }
            $username = $DataMess['USERNAME'];
            $email = $_POST['email'];
            $mail = new PHPMailer\PHPMailer\PHPMailer(false);
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->IsSMTP();
            $mail->Host = $xserver;
            $mail->SMTPAuth = true;
            $mail->Username = $xusermail;
            $mail->Password = $xpasswd;
            $mail->FromName = 'Admin SIPA';
            $mail->From = $xalamat;
            // $mail->AddAddress($email,'SIPA');
            // $mail->AddCC($cc,'SIPA');
            $mail->Subject = 'User SIPA';

            //Recipients
            $mail->setFrom('noreplymesspln@pln.co.id', 'ADMIN MESS');
            $mail->addAddress("$email","");     // Add a recipient
            //  $mail->addAddress('jefriyan.jy@gmail.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'MESS WSB (RESET PASSWORD)';
            $mail->Body    = "<html>
<head>
    <style>
        #container{
            width: 700px;
            background-color: rgba(246, 243, 243, 0.44);
            margin: 0 auto;
        }
        #header{
            width: 100%;
            background-color: rgb(240, 255, 244);
            text-align: center;
            float: left;
            padding: 5px;
        }
        h1,h2,h4{
            margin: 0;
        }
        #badan{
            overflow: hidden;
            width: 100%;
            float: left;
            padding: 5px;
            background-color: rgba(246, 243, 243, 0.44);
        }
    </style>
</head>
<body>
    <div id=\"container\">
        <div id=\"header\">
            <img src=\"dist/img/logoicon.png\" alt=\"\" style=\"width: 150px;float: left;\">
            <h1>APLIKASI MESS PLN WILAYAH SUMATERA BARAT</h1>
            <h3>RESET PASSWORD</h3>
        </div>
        <div id=\"badan\">
            <p>Reset Password anda berhasil</p>
            <p>Silahkan login menggunakan username & password berikut</p>
            <table style=\"width: 100%;border-collapse: collapse\" border=\"1\">
                <tr>
                    <th>USERNAME</th>
                    <th>:</th>
                    <th>$username</th>
                </tr>
                <tr>
                    <td>PASSWORD</td>
                    <td>:</td>
                    <td>PLN2710</td>
                </tr>
            </table>
            <p></p>
        </div>
        <div id=\"akhir\">
        </div>
    </div>
</body>
</html>";
            $mail->AltBody = 'kasadonyo content';
            if ($mail->send()){
                header('location:login.php?resetsuch='.$_POST['email']);
            }else{
                echo "NOK";
            }

        }
    }else{
        header('location:login.php?unregemail='.$_POST['email']);
    }
}