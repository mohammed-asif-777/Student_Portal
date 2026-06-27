<?php
include "db.php";
$result = $conn->query("SELECT * FROM students");
?>

<h2>All Students</h2>
<table border="1" cellpadding="5">
<tr>
    <th>RRN</th><th>Name</th><th>Email</th><th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['rrn'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><a href="edit_student.php?id=<?= $row['id'] ?>">Edit</a></td>
</tr>
<?php endwhile; ?>
</table>
