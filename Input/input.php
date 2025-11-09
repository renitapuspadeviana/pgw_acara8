<?php
    //mengambil data dari html menggunakan metode post
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    //koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latihan_8fiks";
    
    //membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    //cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    //menyisipkan data ke dalam tabel data_kecamatan
    $sql = "INSERT INTO data_kecamatan (kecamatan, longitude, latitude, luas, jumlah_penduduk)
            VALUES ('$kecamatan', '$longitude', '$latitude', '$luas', '$jumlah_penduduk')";

    //menyimpan dan mengecek apakah data berhasil disisipkan
    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil ditambahkan!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    //menutup koneksi
    $conn->close();

    //mengalihkan kembali ke halaman leafletJS.php
    header("Location: ../Edit/leafletJS.php");

    exit;
?>