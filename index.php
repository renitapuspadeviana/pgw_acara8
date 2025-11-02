<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    // koneksi ke database
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "latihan_8fiks"; 

    // Create connection 
    $conn = new mysqli($servername, $username, $password, $dbname); 
    
    //cek koneksi
    if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
    } 

    $sql = "SELECT * FROM data_kecamatan";
    $result = $conn->query($sql); 
    
    echo "<a href='input/index.html'>Input</a>";

    //menyimpan hasil query ke variabel result
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kecamatan</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Luas</th>
                        <th>Jumlah Penduduk</th>
                    </tr>";

        //output data dari setiap baris
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["kecamatan"] . "</td>
                        <td>" . $row["longitude"] . "</td>
                        <td>" . $row["latitude"] . "</td>
                        <td>" . $row["luas"] . "</td>
                        <td>" . $row["jumlah_penduduk"] . "</td>
                    </tr>"; 
    } 
        echo "</table>"; 
    } else { 
        echo "0 results"; 
    } 
    $conn->close();
?>
</body>
</html>