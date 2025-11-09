<?php
$id = $_GET['id'];
// Sesuaikan dengan setting MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latihan_8fiks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}
//DELETE FROM table_name WHERE condition;
$sql = "DELETE FROM data_kecamatan WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Record with id = $id deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error]);
}
$conn->close();
?>