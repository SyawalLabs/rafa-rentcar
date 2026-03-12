<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mail = new PHPMailer(true);

    try {

        // Ambil data dari form
        $name  = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $passengers = $_POST['passengers'] ?? '';
        $from  = $_POST['from_destination'] ?? '';
        $to    = $_POST['to_destination'] ?? '';
        $date  = $_POST['date_time'] ?? '';
        $message = $_POST['message'] ?? '';

        $subject = "Pemesanan Taksi dari $name";

        // SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'zaifanrafa82@gmail.com';
        $mail->Password   = 'muia rcdq wiuq hgon'; // App Password dari google account setting
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Pengirim
        $mail->setFrom('zaifanrafa82@gmail.com', 'Rafa RentCar');

        // Reply ke pemesan
        $mail->addReplyTo($email, $name);

        // Penerima
        $mail->addAddress('zaifanrafa82@gmail.com');

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $mail->Body = "
        <h2>🚕 Pemesanan Taksi</h2>
        <hr>

        <b>Nama:</b> $name <br>
        <b>Email:</b> $email <br>
        <b>No Telepon:</b> $phone <br>
        <b>Jumlah Penumpang:</b> $passengers <br>
        <b>Dari:</b> $from <br>
        <b>Ke:</b> $to <br>
        <b>Tanggal & Waktu:</b> $date <br>
        <b>Pesan:</b> $message
        ";

        $mail->AltBody = "
        Pemesanan Taksi

        Nama: $name
        Email: $email
        No Telepon: $phone
        Jumlah Penumpang: $passengers
        Dari: $from
        Ke: $to
        Tanggal & Waktu: $date
        Pesan: $message
        ";

        $mail->send();

        // Nomor WhatsApp tujuan
        $wa_number = "6282210091033";

        // Pesan WA (format normal dulu)
        $wa_text = "Halo Rafa RentCar, saya ingin memesan taxi dengan detail berikut:\n\n";
        $wa_text .= "Nama: $name\n";
        $wa_text .= "Email: $email\n";
        $wa_text .= "No Telepon: $phone\n";
        $wa_text .= "Jumlah Penumpang: $passengers\n";
        $wa_text .= "Dari: $from\n";
        $wa_text .= "Ke: $to\n";
        $wa_text .= "Tanggal & Waktu: $date\n";
        $wa_text .= "Pesan: $message";

        // Encode agar aman untuk URL
        $wa_message = urlencode($wa_text);

        // Redirect ke WhatsApp
        header("Location: https://wa.me/$wa_number?text=$wa_message");
        exit();
        
    } catch (Exception $e) {
        echo "Error: " . $mail->ErrorInfo;
    }
}
