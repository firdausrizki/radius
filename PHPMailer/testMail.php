<?php
include "src/PHPMailer.php";
include "src/Exception.php";
include "src/OAuth.php";
include "src/POP3.php";
include "src/SMTP.php";

$mail = new \PHPMailer\PHPMailer\PHPMailer(true);



try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'ssl://smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'firdausrizki71@gmail.com';         // SMTP username
    $mail->Password = 'S4ilorm4n';                        // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
   // $mail->setFrom('firdausrizki17@gmail.com', 'ADMIN');
    $mail->addAddress('ulfahkemlasari@gmail.com', 'Ulfaaaahhhhh');     // Add a recipient
    $mail->addAddress('jefriyan.jy@gmail.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Iko Judul';
    $mail->Body    = 'iko isi <b>huruf taba</b>';
    $mail->AltBody = 'kasadonyo content';

    $mail->send();
    echo 'Lah Takirim';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}