<?php

if (!isset($_POST['tambah'])) header('Location: tambah.php');

require_once '../../koneksi.php';
$nama = mysqli_escape_string($koneksi, $_POST['nama']);
$nip = mysqli_escape_string($koneksi, $_POST['nip']);
$jenis_kelamin = mysqli_escape_string($koneksi, $_POST['jenis_kelamin']);
$no_hp = mysqli_escape_string($koneksi, $_POST['no_hp']);
$tempat_lahir = mysqli_escape_string($koneksi, $_POST['tempat_lahir']);
$tanggal_lahir = mysqli_escape_string($koneksi, $_POST['tanggal_lahir']);
$mata_pelajaran = mysqli_escape_string($koneksi, $_POST['mata_pelajaran']);
$alamat = mysqli_escape_string($koneksi, $_POST['alamat']);

// persiapan upload foto
$ekstensi = $_FILES['foto']['name'];
$ekstensi = pathinfo($ekstensi, PATHINFO_EXTENSION);

$nama_foto = strtolower($nama); // kecilkan semua nama foto yang masuk
$nama_foto = str_replace(' ', '-', $nama_foto) . '.' . $ekstensi;

$asal = $_FILES['foto']['tmp_name'];
$tujuan = '../../images/guru/';

if ($_FILES['foto']['error'] == 0) {
	if ($_FILES['foto']['size'] < 1000000) { // jika ukuran gambar kurang dari 1 mb, maka jalankan
		if (file_exists($tujuan . $nama_foto)) unlink($tujuan . $nama_foto); // jika ada foto yang sama maka hapus foto tersebut

		$query = mysqli_query($koneksi, "INSERT INTO tbl_guru (nama, nip, jenis_kelamin, no_hp, tempat_lahir, tanggal_lahir, mata_pelajaran, alamat, foto) VALUES('$nama', $nip, '$jenis_kelamin', '$no_hp', '$tempat_lahir', '$tanggal_lahir', '$mata_pelajaran', '$alamat', '$nama_foto')") or die(mysqli_error($koneksi)); // jika ada query yang salah maka tampilkan error
		move_uploaded_file($asal, $tujuan . $nama_foto) or die('gagal upload foto'); // $ asal itu = directori sementara, lalu di pindahkan, ke asal file, maka taroh nama file fotonya
		if ($query) { // jika query berhasil (tdk ada error)
			$_SESSION['sukses'] = 'Data Berhasil Ditambahkan!';
			header('Location: index.php');
		} else { // jika query error
			$_SESSION['gagal'] = 'Data Gagal Ditambahkan!';
			header('Location: index.php');
		}
	} else { // jika lebih dari 1mb maka tampilkan pesan dan pindahkan ke index.php
		$_SESSION['gagal'] = 'ukuran gambar tidak boleh lebih dari 1000kb!';
		header('Location: index.php');
	}
}
