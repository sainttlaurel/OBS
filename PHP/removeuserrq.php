<?php
include 'config.php';

if(!isset($_GET['id']) || empty($_GET['id'])){
    echo "<script>alert('Invalid request!'); window.location='removeuser.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use prepared statement to delete record
$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('User deleted successfully!'); window.location='removeuser.php';</script>";
} else {
    echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "'); window.location='removeuser.php';</script>";
}

mysqli_stmt_close($stmt);
$conn->close();
?>