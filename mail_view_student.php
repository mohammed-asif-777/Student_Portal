<?php
session_start();
include "db.php";
include "sidebar_student.php";

$rrn = $_SESSION['rrn'];
$rs = $conn->query("SELECT * FROM mails WHERE FIND_IN_SET('$rrn', recipients) ORDER BY sent_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inbox - Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI'; margin: 0; }
        .content { margin-left: 200px; padding: 20px; }
        .mail-box {
            background: white; padding: 20px; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .mail-box h5 { margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="content">
    <div class="mail-box">
        <h5>📥 Messages from Admin</h5>
        <?php while($mail = $rs->fetch_assoc()): ?>
            <div class="border-bottom pb-3 mb-3">
                <strong>Subject:</strong> <?= htmlspecialchars($mail['subject']) ?><br>
                <strong>Message:</strong>
                <p><?= nl2br(htmlspecialchars($mail['message'])) ?></p>
                <?php if ($mail['attachment']): ?>
                    <a href="uploads/<?= $mail['attachment'] ?>" target="_blank">📎 View Attachment</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
