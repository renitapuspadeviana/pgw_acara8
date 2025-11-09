<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Kecamatan Sleman + CRUD</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0a192f; /* Deep Navy */
            color: #ccd6f6; /* Light Slate */
        }
        .container {
            padding: 20px;
        }
        #map {
            width: 100%;
            height: 500px; /* Adjusted height */
            margin-top: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            border: 1px solid #233554;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #112240; /* Navy Border */
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #112240; /* Lighter Navy */
            color: #64ffda; /* Bright Aqua/Cyan */
            text-align: center;
        }
        td {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #64ffda; /* Bright Aqua/Cyan */
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
        h1, h2 {
            text-align: center;
            color: #ccd6f6;
            border-bottom: 1px solid #233554;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .form-container, .table-container {
            background: #112240; /* Lighter Navy */
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
            border: 1px solid #233554;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #8892b0;
        }
        form input[type="text"], form input[type="number"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #233554;
            background: #0a192f;
            color: #ccd6f6;
            font-size: 14px;
        }
        form input[type="submit"] {
            background-color: #64ffda; /* Bright Aqua/Cyan */
            color: #0a192f;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            font-size: 16px;
            transition: all 0.25s ease;
        }
        form input[type="submit"]:hover {
            background-color: #a7ffea;
        }

        /* Custom Leaflet Popup */
        .leaflet-popup-content-wrapper {
            background-color: #112240; /* Lighter Navy */
            color: #ccd6f6; /* Light Slate */
            border-radius: 8px;
            border: 1px solid #233554;
        }
        .leaflet-popup-content {
            margin: 15px;
            font-size: 14px;
            line-height: 1.6;
        }
        .leaflet-popup-content b {
            color: #64ffda; /* Bright Aqua/Cyan */
            font-size: 16px;
        }
        .leaflet-popup-content a {
            color: #64ffda;
            text-decoration: none;
        }
        .leaflet-popup-content a:hover {
            text-decoration: underline;
        }
        .leaflet-popup-tip {
            background-color: #112240;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Peta Sebaran Kecamatan di Sleman</h1>

    <div id="map"></div>

    <?php
    // --- Koneksi ke Database ---
    $conn = new mysqli("localhost", "root", "", "latihan_8fiks");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM data_kecamatan");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $conn->close();
    ?>

    <div class="table-container">
        <h2>Data Kecamatan</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Luas (km²)</th>
                <th>Penduduk</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; foreach ($data as $d): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['kecamatan']) ?></td>
                <td><?= htmlspecialchars($d['latitude']) ?></td>
                <td><?= htmlspecialchars($d['longitude']) ?></td>
                <td><?= htmlspecialchars($d['luas']) ?></td>
                <td><?= htmlspecialchars($d['jumlah_penduduk']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $d['id'] ?>">Edit</a> | 
                    <a href="#" onclick="deleteData(<?= $d['id'] ?>, event)">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="form-container">
        <h2>Input Data Baru</h2>
        <form action="../Input/input.php" method="post">
            <label for="kecamatan">Kecamatan:</label>
            <input type="text" id="kec" name="kecamatan" required>
            
            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" required>
            
            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" required>
            
            <label for="luas">Luas:</label>
            <input type="text" id="luas" name="luas" required>
            
            <label for="jumlah_penduduk">Jumlah Penduduk:</label>
            <input type="text" id="jumlah_penduduk" name="jumlah_penduduk" required>
            
            <input type="submit" value="Submit">
        </form>
    </div>

</div>

<!-- LEAFLET SCRIPT -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-7.75, 110.33], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var kecamatanData = <?php echo json_encode($data); ?>;
    var bounds = [];

    kecamatanData.forEach(function(item) {
        var lat = parseFloat(item.latitude);
        var lon = parseFloat(item.longitude);

        if (!isNaN(lat) && !isNaN(lon)) {
            var popupContent = `
                <b>${item.kecamatan}</b><br>
                Luas: ${item.luas} km²<br>
                Penduduk: ${item.jumlah_penduduk}<br><br>
                <a href="edit.php?id=${item.id}">Edit</a> | 
                <a href="#" onclick="deleteData(${item.id}, event)">Hapus</a>
            `;
            var marker = L.marker([lat, lon]).addTo(map).bindPopup(popupContent);
            bounds.push([lat, lon]);
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds);
    }

    function deleteData(id, event) {
        event.preventDefault(); // Prevent default link behavior
        if (confirm('Data ini akan dihapus secara permanen dari database. Apakah Anda yakin ingin melanjutkan?')) {
            fetch(`../delete.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Gagal menghapus data: ' + (data.message || 'Pesan tidak diketahui.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghubungi server.');
                });
        }
    }
</script>
</body>
</html>