<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include "db.php";
include "sidebar_admin.php";

// Fetch all student emails
$students = $conn->query("SELECT rrn, name, email FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Mail - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .content { padding: 20px; }
        .mail-box {
            background: white;
            padding: 25px;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        .mail-box h4 {
            margin-bottom: 25px;
            font-weight: 600;
        }
        .form-control, .form-check-input {
            box-shadow: none !important;
        }
        .form-check {
            margin-right: 15px;
        }
        .select-all {
            font-size: 0.9rem;
            cursor: pointer;
            color: #007bff;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="content shift">
    <div class="mail-box">
        <h4>📤 Send Mail to Students</h4>
        <form action="mail_send_submit.php" method="POST" enctype="multipart/form-data">

            <!-- Recipients -->
            <div class="form-group">
                <label><strong>To:</strong></label><br>
                <span class="select-all" onclick="toggleAll()">✔ Select All</span>
                <div class="d-flex flex-wrap">
                    <?php while ($s = $students->fetch_assoc()): ?>
                        <div class="form-check mr-3">
                            <input class="form-check-input student-check" type="checkbox" name="recipients[]" value="<?= $s['rrn'] ?>" id="stu<?= $s['rrn'] ?>">
                            <label class="form-check-label" for="stu<?= $s['rrn'] ?>">
                                <?= htmlspecialchars($s['name']) ?> (<?= $s['rrn'] ?>)
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- CC -->
            <div class="form-group">
                <label for="cc"><strong>CC (optional):</strong></label>
                <input type="email" class="form-control" name="cc" placeholder="someone@example.com">
            </div>

            <!-- Subject -->
            <div class="form-group">
                <label for="subject"><strong>Subject:</strong></label>
                <input type="text" class="form-control" name="subject" required>
            </div>

            <!-- Message -->
            <div class="form-group">
                <label for="message"><strong>Message:</strong></label>
                <textarea class="form-control" name="message" rows="5" required></textarea>
            </div>

            <!-- Attachment -->
            <div class="form-group">
                <label><strong>Attachment:</strong> <small>(PDF, image, etc.)</small></label>
                <input type="file" class="form-control-file" name="attachment">
            </div>

            <button type="submit" class="btn btn-primary">📧 Send Mail</button>
        </form>
    </div>
</div>

<script>
    function toggleAll() {
        document.querySelectorAll('.student-check').forEach(cb => cb.checked = true);
    }
</script>

</body>
</html>
