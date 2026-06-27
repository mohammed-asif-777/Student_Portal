<?php
session_start(); include "db.php";
if (!isset($_SESSION['rrn'])) { header("Location: index.php"); exit; }
$rs = $conn->prepare("SELECT name,email,rrn,marks,fees_due,attendance,cgpa,photo FROM students WHERE rrn=?");
$rs->bind_param("s", $_SESSION['rrn']);
$rs->execute(); $data = $rs->get_result()->fetch_assoc();
?>
<!DOCTYPE html><html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }
        .sidebar {
            width: 200px;
            position: fixed;
            height: 100%;
            background: #2c3e50;
            padding-top: 20px;
            color: white;
        }
        .sidebar a {
            padding: 10px 20px;
            display: block;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #34495e;
        }
    .main { margin-left:220px; padding:40px; }
    .profile { text-align:center; background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .profile img { width:120px; height:120px; border-radius:50%; object-fit:cover; }
    .info { margin-top:20px; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .info p { margin:8px 0; font-size:1rem; }
  </style>
</head>
<body>

<?php include 'sidebar_student.php'; ?>



<div class="main">
  <div class="profile">
    <img src="upload/assets/images/<?= htmlspecialchars($data['photo']) ?>" alt="Profile Photo">
    <h3><?= htmlspecialchars($data['name']) ?></h3>
  </div>

  <div class="info">
    <p><strong>RRN:</strong> <?= htmlspecialchars($data['rrn']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
    <p><strong>Marks:</strong> <?= htmlspecialchars($data['marks']) ?></p>
    <p><strong>Attendance:</strong> <?= htmlspecialchars($data['attendance']) ?>%</p>
    <p><strong>Fees Due:</strong> ₹<?= htmlspecialchars($data['fees_due']) ?></p>
    <p><strong>CGPA:</strong> <?= htmlspecialchars($data['cgpa']) ?></p>
  </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>
