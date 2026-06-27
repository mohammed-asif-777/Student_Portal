<?php
session_start();
include "db.php";

//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
   // header("Location: index.php");
    //exit();
//}

$result = $conn->query("SELECT name, marks, attendance FROM students");
$names = $marks = $attendance = [];

while ($row = $result->fetch_assoc()) {
    $names[] = $row['name'];
    $marks[] = $row['marks'];
    $attendance[] = $row['attendance'];
}
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
            transition: 0.3s;
        }
         .sidebar :hover {
            background: #34495e;
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
    <div class="chart-container">
        <h5>📊 Student Marks Histogram</h5>
        <canvas id="marksChart" height="100"></canvas>
    </div>

    <div class="chart-container">
        <h5>📈 Student Attendance Histogram</h5>
        <canvas id="attendanceChart" height="100"></canvas>
    </div>
</div>

<script>
    const names = <?= json_encode($names); ?>;
    const marks = <?= json_encode($marks); ?>;
    const attendance = <?= json_encode($attendance); ?>;

    // Set color thresholds
    const markColors = marks.map(m => m < 40 ? 'red' : m < 70 ? 'orange' : 'green');
    const attColors = attendance.map(a => a < 60 ? 'red' : a < 85 ? 'orange' : 'green');

    // Animate marks chart
    const marksChart = new Chart(document.getElementById("marksChart"), {
        type: "bar",
        data: {
            labels: names,
            datasets: [{
                label: "Marks",
                data: marks.map(() => 0), // start from 0
                backgroundColor: markColors,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1500,
                onComplete: function () {
                    this.data.datasets[0].data = [...marks];
                    this.update();
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: { display: true, text: 'Marks out of 100' }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                },
                legend: { display: false }
            }
        }
    });

    // Animate attendance chart
    const attendanceChart = new Chart(document.getElementById("attendanceChart"), {
        type: "bar",
        data: {
            labels: names,
            datasets: [{
                label: "Attendance (%)",
                data: attendance.map(() => 0), // start from 0
                backgroundColor: attColors,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1500,
                onComplete: function () {
                    this.data.datasets[0].data = [...attendance];
                    this.update();
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: { display: true, text: 'Attendance (%)' }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                },
                legend: { display: false }
            }
        }
    });
</script>
<?php include "footer.php"; ?>
