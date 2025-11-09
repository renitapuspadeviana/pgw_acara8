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
        input[type="submit"] { 
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
        .error-message {
            color: #ff7b72;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Kecamatan</h2>
<?php
    // --- Koneksi ke Database ---
    $conn = new mysqli("localhost", "root", "", "latihan_8fiks");
    if ($conn->connect_error) {
        die("<p class='error-message'>Koneksi gagal: " . $conn->connect_error . "</p>");
    }

    // --- Ambil Data untuk Form ---
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM data_kecamatan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
?>
        <form action="edit.php" method="post" onsubmit="return validateForm()">
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
        <p id="informasi" class="error-message"></p>
<?php
        } else {
            echo "<p class='error-message'>Data tidak ditemukan!</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error-message'>ID tidak ditemukan.</p>";
    }
    $conn->close();
?>
    </div>

    <script>
        function validateForm() {
            let luas = document.getElementById("luas").value;
            let text = "";
            if (isNaN(luas) || parseFloat(luas) < 0) {
                text = "Data luas harus angka dan tidak boleh bernilai negatif.";
                document.getElementById("informasi").innerHTML = text;
                return false; // Mencegah form dari submit
            }
            return true; // Lanjutkan submit jika valid
        }
    </script>
</body>
</html>
