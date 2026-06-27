<?php
session_start();
include "db.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// User Info
$role = $_SESSION['role'] ?? 'student';
$sender = ($role === 'admin') ? 'ADMIN' : $_SESSION['rrn'];
$recips = $_POST['recipients'] ?? [];
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// File upload handling
$attachment = '';
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true); // ✅ ensure uploads folder exists
    }
    $f = time() . '_' . basename($_FILES['attachment']['name']);
    if (move_uploaded_file($_FILES['attachment']['tmp_name'], "uploads/" . $f)) {
        $attachment = $f;
    }
}

// Save to DB
$recs = implode(',', $recips);
$stmt = $conn->prepare("INSERT INTO mails (sender, recipients, subject, message, attachment, role, sent_at)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("ssssss", $sender, $recs, $subject, $message, $attachment, $role);
$stmt->execute();

// Email via PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yourgmail@gmail.com';        // ✅ your Gmail
    $mail->Password = 'your_app_password';          // ✅ App Password, not regular password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('yourgmail@gmail.com', 'Student Portal');
    $mail->Subject = $subject;
    $mail->Body = $message;
    if ($attachment) {
        $mail->addAttachment("uploads/" . $attachment);
    }

    if ($role === 'admin') {
        foreach ($recips as $r) {
            $r = $conn->real_escape_string($r);
            $e = $conn->query("SELECT email FROM students WHERE rrn='$r'")->fetch_assoc();
            if (!empty($e['email'])) {
                $mail->addAddress($e['email']);
            }
        }
    } else {
        $result = $conn->query("SELECT email FROM admins");
while ($row = $result->fetch_assoc()) {
    if (!empty($row['email'])) {
        $mail->addAddress($row['email']);
    }
}
 // ✅ your admin Gmail
    }

    $mail->send();
} catch (Exception $e) {
    // You can log the error if needed: $e->getMessage()
}

header("Location: mail_inbox_{$role}.php?sent=1");
exit();
?>
