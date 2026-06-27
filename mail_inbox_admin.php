<?php
session_start();
include "db.php";
include "sidebar_admin.php";
$inbox = $conn->query("SELECT * FROM mails WHERE role='student' ORDER BY sent_at DESC");
?>
<html><head></head><body>
<div class="content">
  <h4>Admin Inbox</h4>
  <?php if($inbox->num_rows){
    while($m=$inbox->fetch_assoc()){ ?>
      <div class="msg">
        <b>From:</b> <?=$m['sender']?> |
        <b>Subject:</b> <?=$m['subject']?><br>
        <?=nl2br($m['message'])?><br>
        <?php if($m['attachment']) echo "<a href='uploads/".$m['attachment']."'>Attachment</a><br>"; ?>
        <small>Sent at: <?= $m['sent_at']?></small>
      </div><hr>
  <?php }} else echo "No messages"; ?>
</div></body></html>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Inbox</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; margin: 0; }
        .content { margin-left: 200px; padding: 30px; }
        .inbox-card {
            background: white; padding: 20px; border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .message-box {
            border-bottom: 1px solid #ccc; padding-bottom: 15px; margin-bottom: 15px;
        }
        .message-box:last-child { border: none; }
    </style>
</head>
<body>
<div class="content">
    <div class="inbox-card">
        <h4>📥 Inbox – Messages from Students</h4>
        <?php if ($inbox->num_rows > 0): ?>
            <?php while($mail = $inbox->fetch_assoc()): ?>
                <div class="message-box">
                    <strong>From:</strong> <?= htmlspecialchars($mail['sender']) ?><br>
                    <strong>Subject:</strong> <?= htmlspecialchars($mail['subject']) ?><br>
                    <p><?= nl2br(htmlspecialchars($mail['message'])) ?></p>
                    <?php if (!empty($mail['attachment'])): ?>
                        <a href="uploads/<?= $mail['attachment'] ?>" target="_blank">📎 View Attachment</a><br>
                    <?php endif; ?>
                    <small><i>Sent at: <?= $mail['sent_at'] ?></i></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
