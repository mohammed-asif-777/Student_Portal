<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'];
    $rrn = $_POST['rrn'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user_type === 'student') {
        echo "student";
        $sql = "SELECT * FROM students WHERE rrn='$rrn' AND email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $student = $result->fetch_assoc();
            $_SESSION['rrn'] = $student['rrn'];
            $_SESSION['role'] = 'student';
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid Student Credentials.";
        }
    } elseif ($user_type === 'admin') {
        //echo "admin";
        $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid Admin Credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background: #eef2f5; }
        .login-container { max-width: 450px; margin: 50px auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .logo { display: block; margin: 0 auto 10px; width: 60px; }
        .form-group label { font-weight: 600; }
    </style>
    <script>
        function toggleRRN() {
            const type = document.querySelector('input[name="user_type"]:checked').value;
            document.getElementById('rrn-field').style.display = type === 'student' ? 'block' : 'none';
        }
    </script>
</head>
<body onload="toggleRRN()">
<div class="login-container">
    <img src="upload/assets/images/logo.png" class="logo">
    <h4 class="text-center mb-3">Login Portal</h4>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label><input type="radio" name="user_type" value="student" checked onchange="toggleRRN()"> Student</label>
            <label class="ml-3"><input type="radio" name="user_type" value="admin" onchange="toggleRRN()"> Admin</label>
        </div>
        <div id="rrn-field" class="form-group">
            <label>RRN</label>
            <input type="text" name="rrn" class="form-control">
        </div>
        <div class="form-group">
            <label>Email ID</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>
</body>
<?php include "footer.php"; ?>
</html>
