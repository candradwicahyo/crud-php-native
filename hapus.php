<?php
// jalankan session
session_start();
// panggil file functions.php
require 'functions.php';
// tangkap id yang berada di url
$id = escapeString($_GET['id']);
// jalankan function hapus
if (hapusData($id) > 0) {
  // jika berhasil
  setFlash('flash', 'Data Mahasiswa Berhasil Dihapus!', 'success');
  header('Location: index.php?berhasil');
  exit;
} else {
  // jika gagal
  setFlash('flash', 'Data Mahasiswa Gagal Dihapus!', 'danger');
  header('Location: index.php?gagal');
  exit;
}