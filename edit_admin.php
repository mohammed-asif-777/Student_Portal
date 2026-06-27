<?php
session_start();
include "db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); exit();
}

$rrn = $_GET['rrn'] ?? '';
if (!$rrn) { header("Location: admin_dashboard.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM students WHERE rrn=?");
$stmt->bind_param("s",$rrn); $stmt->execute(); $data = $stmt->get_result()->fetch_assoc();
if (!$data) { header("Location: admin_dashboard.php"); exit(); }

$msg = "";
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = $_POST['name']; $email = $_POST['email'];
    $marks = intval($_POST['marks']); $attendance = intval($_POST['attendance']);
    $fees = $_POST['fees_due']; $cgpa = $_POST['cgpa'];

    $photoName = $data['photo'];
    if (!empty($_FILES['photo']['tmp_name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = uniqid('p_').'.'.$ext;
        move_uploaded_file($_FILES['photo']['tmp_name'],"upload/assets/images/$photoName");
    }

    $stmt = $conn->prepare(
        "UPDATE students SET name=?,email=?,marks=?,attendance=?,fees_due=?,cgpa=?,photo=? WHERE rrn=?"
    );
    $stmt->bind_param("ssiiisss",$name,$email,$marks,$attendance,$fees,$cgpa,$photoName,$rrn);
    $stmt->execute();
    $msg = "✅ Updated successfully!";
    $data['name']=$name; $data['email']=$email; $data['marks']=$marks;
    $data['attendance']=$attendance; $data['fees_due']=$fees; $data['cgpa']=$cgpa;
    $data['photo']=$photoName;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background:#f5f7fa; margin:0; font-family:Arial; }
        .sidebar { position:fixed; left:0; top:0; width:220px; height:100%; background:#223; color:#fff; }
        .sidebar a { padding:12px 20px; display:block; color:#eee; text-decoration:none; }
        .sidebar a:hover{ background:#334; }
        .main { margin-left:220px; padding:20px; }
        .form-wrapper { max-width:500px; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
        .photo-preview { width:120px; height:120px; object-fit:cover; border-radius:50%; margin-bottom:10px; }
    </style>
</head>
<?php include 'sidebar_admin.php'; ?>
<div class="main">
    <h3>Edit Student <?=htmlspecialchars($data['rrn'])?></h3>
    <?php if ($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
    <div class="form-wrapper">
        <form method="POST" enctype="multipart/form-data">
            <img src="upload/assets/images/<?=htmlspecialchars($data['photo'])?>" class="photo-preview"><br>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?=htmlspecialchars($data['name'])?>" class="form-control" required>
            </div>
            <div class="form-group"><label>Email</label>
                <input type="email" name="email" value="<?=htmlspecialchars($data['email'])?>" class="form-control" required>
            </div>
            <div class="form-group"><label>Marks</label>
                <input type="number" name="marks" value="<?=$data['marks']?>" class="form-control" required>
            </div>
            <div class="form-group"><label>Attendance (%)</label>
                <input type="number" name="attendance" value="<?=$data['attendance']?>" class="form-control" required>
            </div>
            <div class="form-group"><label>Fees Due</label>
                <input type="text" name="fees_due" value="<?=htmlspecialchars($data['fees_due'])?>" class="form-control">
            </div>
            <div class="form-group"><label>CGPA</label>
                <input type="text" name="cgpa" value="<?=htmlspecialchars($data['cgpa'])?>" class="form-control">
            </div>
            <div class="form-group"><label>Photo (leave blank to keep current)</label>
                <input type="file" name="photo" class="form-control-file">
            </div>
            <button class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>
