<?php
session_start();
include "db.php";

// Security: Uncomment this in production
// if (!isset($_SESSION['rrn']) || $_SESSION['role'] !== 'student') {
//     header("Location: index.php");
//     exit();
// }

$rrn = $_SESSION['rrn'];
$result = $conn->query("SELECT name, marks, attendance FROM students WHERE rrn='$rrn'");
$data = $result->fetch_assoc();

$name = $data['name'];
$marks = $data['marks'];
$attendance = $data['attendance'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student - My Graph</title>
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
            max-width: 600px;
        }
        .chart-container h5 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<?php include 'sidebar_student.php'; ?>


<!-- Graphs -->
<div class="content">
    <div class="chart-container">
        <h5>📊 My Marks Histogram</h5>
        <canvas id="marksChart" height="100" width="400"></canvas>
    </div>

    <div class="chart-container">
        <h5>📈 My Attendance Histogram</h5>
        <canvas id="attendanceChart" height="100" width="400"></canvas>
    </div>
</div>

<script>
    const studentName = <?= json_encode($name); ?>;
    const marks = <?= json_encode($marks); ?>;
    const attendance = <?= json_encode($attendance); ?>;

    const markColor = marks < 40 ? 'red' : marks < 70 ? 'orange' : 'green';
    const attColor = attendance < 60 ? 'red' : attendance < 85 ? 'orange' : 'green';

    // Marks Chart with animation
    new Chart(document.getElementById("marksChart"), {
        type: "bar",
        data: {
            labels: [studentName],
            datasets: [{
                label: "Marks",
                data: [0], // Start from 0 for animation
                backgroundColor: [markColor],
                borderRadius: 4
            }]
        },
        options: {
            animation: {
                duration: 1000,
                onComplete: function () {
                    this.data.datasets[0].data[0] = marks;
                    this.update();
                }
            },
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Marks (out of 100)'
                    }
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

    // Attendance Chart with animation
    new Chart(document.getElementById("attendanceChart"), {
        type: "bar",
        data: {
            labels: [studentName],
            datasets: [{
                label: "Attendance (%)",
                data: [attendance],
                backgroundColor: [attColor],
                borderRadius: 4
            }]
        },
        options: {
            animation: {
                duration: 1000
            },
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Attendance (%)'
                    }
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

</body>
<?php include "footer.php"; ?>
</html>
