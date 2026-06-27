<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
include "db.php";
include "sidebar_student.php";

// Fetch messages sent by admin to this student
$rrn = $conn->real_escape_string($_SESSION['rrn']);
$inbox = $conn->query("
    SELECT * FROM mails
    WHERE FIND_IN_SET('$rrn', recipients)
      AND role = 'admin'
    ORDER BY sent_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Inbox</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .content { padding: 20px; }
        .inbox-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 800px;
        }
        .message-box {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .message-box:last-child { border-bottom: none; }
        .message-meta {
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
<div class="content shift">
    <div class="inbox-box">
        <h4>📥 Inbox – Messages from Admin</h4>

        <?php if ($inbox && $inbox->num_rows > 0): ?>
            <?php while ($m = $inbox->fetch_assoc()): ?>
                <div class="message-box">
                    <strong>Subject:</strong> <?= htmlspecialchars($m['subject']) ?><br>
                    <p><?= nl2br(htmlspecialchars($m['message'])) ?></p>

                    <?php if (!empty($m['attachment'])): ?>
                        <div><a href="uploads/<?= htmlspecialchars($m['attachment']) ?>" download>📎 Download Attachment</a></div>
                    <?php endif; ?>

                    <div class="message-meta">
                        Received at: <?= htmlspecialchars($m['sent_at']) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
