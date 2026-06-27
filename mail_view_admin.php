<?php
session_start();
include "db.php";
include "sidebar_admin.php";

$inbox = $conn->query("SELECT * FROM mails WHERE role='student' ORDER BY sent_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inbox - Admin</title>
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
        <h5>📥 Received Mails from Students</h5>
        <?php while($mail = $inbox->fetch_assoc()): ?>
            <div class="border-bottom pb-3 mb-3">
                <strong>From:</strong> <?= htmlspecialchars($mail['sender']) ?><br>
                <strong>Subject:</strong> <?= htmlspecialchars($mail['subject']) ?><br>
                <strong>Message:</strong><br>
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
