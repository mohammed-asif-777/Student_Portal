<?php
session_start();
include "db.php";
include 'sidebar_admin.php';

$showSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rrn      = $_POST['rrn'];
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $pass     = $_POST['password'];  // plain text stored
    $marks    = $_POST['marks'];
    $fees     = $_POST['fees_due'];
    $att      = $_POST['attendance'];
    $cgpa     = $_POST['cgpa'];

    // Handle photo upload
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $photo = uniqid('photo_', true) . ".$ext";
            move_uploaded_file($_FILES['photo']['tmp_name'], "upload/assets/images/$photo");
        } else {
            die("❌ Invalid file type. Only jpg/jpeg/png allowed.");
        }
    }

    $stmt = $conn->prepare("
        INSERT INTO students (rrn, name, email, password, photo, marks, fees_due, attendance, cgpa)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssidis", $rrn, $name, $email, $pass, $photo, $marks, $fees, $att, $cgpa);
    if ($stmt->execute()) {
        $showSuccess = true;
    } else {
        die("❌ DB Error: {$stmt->error}");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .content.shift { padding: 20px; }
        .form-box {
            background: white; padding: 30px; border-radius: 8px;
            max-width: 700px; margin-top:20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .success {
            position: fixed; top:20px; right:20px;
            background: #28a745; color: #fff;
            padding: 10px 15px; border-radius: 5px;
            opacity: 1; transition: opacity 0.5s ease;
        }
    </style>
</head>
<body>
<div class="content shift">
    <?php if ($showSuccess): ?>
        <div id="successMsg" class="success">Student added successfully!</div>
        <script>
            setTimeout(() => {
              const el = document.getElementById('successMsg');
              el.style.opacity = 0;
              setTimeout(() => el.remove(), 500);
            }, 3000);
        </script>
    <?php endif; ?>

    <div class="form-box">
        <h4>Add New Student</h4>
        <form method="POST" enctype="multipart/form-data">
            <?php
            $fields = ['rrn'=>'RRN', 'name'=>'Name', 'email'=>'Email', 'password'=>'Password', 'marks'=>'Marks', 'fees_due'=>'Fees Due', 'attendance'=>'Attendance (%)', 'cgpa'=>'CGPA'];
            foreach ($fields as $key => $label): ?>
                <div class="form-group">
                    <label><?= $label ?></label>
                    <input name="<?= $key ?>" type="<?= ($key==='password'?'password': ($key==='marks'||$key==='attendance'?'number':'text')) ?>" class="form-control" required>
                </div>
            <?php endforeach; ?>
            <div class="form-group">
                <label>Upload Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control-file" required>
            </div>
            <button class="btn btn-success">Add Student</button>
        </form>
    </div>
</div>
</body>
</html>
