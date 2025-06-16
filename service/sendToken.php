<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Pastikan PHPMailer sudah diinstal via Composer

function sendTokenEmail($email, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io'; // Ganti dengan SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = '51709648475756'; // Ganti dengan email pengirim
        $mail->Password = '91284f950cf613'; // Ganti dengan password email pengirim
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Pengaturan email
        $mail->setFrom('andika.syarrell6@smk.belajar.id', 'Kasir Digital');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Kode Verifikasi Akun Anda';
        $mail->Body    = "Kode verifikasi Anda adalah: <b>$token</b><br>Kode ini berlaku selama 1 jam.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
