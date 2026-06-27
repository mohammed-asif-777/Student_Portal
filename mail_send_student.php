<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location:index.php");
    exit();
}
include "db.php";
include "sidebar_student.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Send Mail – Student</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .content { padding: 20px; }
    .form-box {
      background: white; padding: 30px; border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 700px;
    }
  </style>
</head>
<body>
<div class="content">
  <div class="form-box">
    <h4>📤 Contact Admin</h4>
    <form method="POST" action="mail_send_submit.php" enctype="multipart/form-data">
      <input type="hidden" name="recipients[]" value="admin">
      <div class="form-group">
        <label>Subject</label>
        <input name="subject" type="text" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Message</label>
        <textarea name="message" rows="5" class="form-control" required></textarea>
      </div>
      <div class="form-group">
        <label>Attachment</label>
        <input name="attachment" type="file" class="form-control-file">
      </div>
      <button class="btn btn-primary">Send to Admin</button>
    </form>
    <?php if (isset($_GET['sent'])): ?>
      <div class="alert alert-success mt-3">Your message has been sent!</div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
