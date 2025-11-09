<?php
// --- Koneksi ke Database ---
$conn = new mysqli("localhost", "root", "", "latihan_8fiks");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// --- Logika untuk Handle Request ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // --- PROSES UPDATE DATA (POST Request) ---
    $id = $_POST['id'];
    $kecamatan = $_POST['kecamatan'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    $sql = "UPDATE data_kecamatan SET 
                kecamatan = ?, 
                latitude = ?, 
                longitude = ?, 
                luas = ?, 
                jumlah_penduduk = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    // Bind parameter ke statement: sddddi -> string, double, double, double, double, integer
    $stmt->bind_param("sddddi", $kecamatan, $latitude, $longitude, $luas, $jumlah_penduduk, $id);

    if ($stmt->execute()) {
        // Jika berhasil, redirect kembali ke halaman peta
        header("Location: leafletJS.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();

} else {
    // --- TAMPILKAN FORM EDIT (GET Request) ---
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM data_kecamatan WHERE id = $id");
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan!";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Kecamatan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #0a192f; /* Warna navy gelap untuk background */
            color: #ccd6f6; /* Warna teks terang untuk kontras */
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .container { 
            background-color: #112240; /* Warna navy sedikit lebih terang untuk container */
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.3); 
            width: 400px;
            border: 1px solid #64ffda; /* Border dengan warna aksen */
        }
        h2 { 
            text-align: center; 
            margin-bottom: 20px; 
            color: #64ffda; /* Warna aksen untuk judul */
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #8892b0; /* Warna abu-abu terang untuk label */
        }
        input[type="text"], input[type="number"] { 
            width: 100%; 
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #233554; /* Border yang lebih gelap */
            border-radius: 4px; 
            box-sizing: border-box; 
            background-color: #233554; /* Background input yang lebih gelap */
            color: #ccd6f6; /* Warna teks input */
        }
        input[type="text"]:focus, input[type="number"]:focus {
            outline: none;
            border-color: #64ffda; /* Border focus dengan warna aksen */
        }
        input[type="submit"] { d
            width: 100%; 
            background-color: #64ffda; /* Warna aksen untuk tombol */
            color: #0a192f; /* Warna teks gelap pada tombol */
            padding: 10px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover { 
            background-color: #52d8c9; /* Warna hover yang sedikit lebih gelap */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Kecamatan</h2>
        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
            
            <label for="kecamatan">Nama Kecamatan:</label>
            <input type="text" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($data['kecamatan']) ?>" required>
            
            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" value="<?= htmlspecialchars($data['latitude']) ?>" required>
            
            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" value="<?= htmlspecialchars($data['longitude']) ?>" required>
            
            <label for="luas">Luas (km²):</label>
            <input type="number" step="0.01" id="luas" name="luas" value="<?= htmlspecialchars($data['luas']) ?>" required>
            
            <label for="jumlah_penduduk">Jumlah Penduduk:</label>
            <input type="number" id="jumlah_penduduk" name="jumlah_penduduk" value="<?= htmlspecialchars($data['jumlah_penduduk']) ?>" required>
            
            <input type="submit" value="Update Data">
        </form>
    </div>
</body>
</html>
<?php
} // End of else (GET request)
$conn->close();
?>
