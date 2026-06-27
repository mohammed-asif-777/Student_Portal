<?php
session_start();
include "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$search = $_GET['search'] ?? '';
$where = "";

if ($search !== '') {
    $esc = $conn->real_escape_string($search);
    $where = "WHERE rrn LIKE '%$esc%' OR name LIKE '%$esc%' OR marks LIKE '%$esc%' OR attendance LIKE '%$esc%'";
}

$rs = $conn->query("SELECT * FROM students $where");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - All Student Graphs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .chart-container h5 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<?php include 'sidebar_admin.php'; ?>


<div class="content">
    <h3>&nbsp;&nbsp;&nbsp; Admin Control Panel</h3>

    <form class="form-inline mb-3" method="GET">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control mr-2" placeholder="Search by RRN, Name, Marks, Attendance" style="width:300px;">
        <button class="btn btn-outline-primary">Search</button>
    </form>

    <h5>Current Students</h5>
    <div class="student-table-wrapper mb-4">
        <table class="table table-bordered mb-0">
            <thead class="thead-dark">
                <tr>
                    <th>RRN</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Marks</th>
                    <th>Attendance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $rs->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['rrn']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['marks'] ?></td>
                    <td><?= $row['attendance'] ?>%</td>
                    <td>
                        <a href="edit_admin.php?rrn=<?= $row['rrn'] ?>" class="btn btn-sm btn-info">Edit</a>
                        <a href="delete_student.php?rrn=<?= $row['rrn'] ?>" onclick="return confirm('Delete this student?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
<?php include "footer.php"; ?>
</body>
</html>
