<?php
// jalankan session
session_start();
// panggil file functions.php
require 'functions.php';
// data yang akan dikirim
$data['judul'] = 'Halaman Utama';
$data['mahasiswa'] = getAllDataMahasiswa();
// jalankan function view
view('header', $data);
?>

<div class="container my-3">
  <div class="row">
    <div class="col-md">
      
      <div class="bg-primary p-4 rounded shadow-sm mb-3">
        <h1 class="fw-semibold text-white">Program CRUD Sederhana</h1>
        <p class="fw-light text-light">
          sebuah program CRUD (create, read, update, delete) sederhana yang dibuat dengan html, css, javascript dan php. diprogram ini ada fitur seperti preview gambar sebelum diupload dan input pencarian data
        </p>
      </div>
      
      <div class="row">
        <div class="col-md-7">
          <?= flash('flash'); ?>
        </div>
      </div>
      
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="wrapper mb-2">
          <a href="tambah.php" class="text-decoration-none btn btn-outline-primary py-2 px-3 rounded-1">Tambah Data</a>
        </div>
        <!-- form pencarian data -->
        <form action="" method="post">
          <div class="input-group mb-2">
            <input type="text" name="cari" id="search" class="form-control py-2 px-3 search-input" placeholder="masukkan input pencarian data" autocomplete="off">
          </div>
        </form>
      </div>
      
      <?php if ($data['mahasiswa']) : ?>
      <!-- table -->
      <div class="table-wrapper">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="p-3 fw-normal">No</th>
                <th class="p-3 fw-normal">nama</th>
                <th class="p-3 fw-normal">Nrp</th>
                <th class="p-3 fw-normal">Email</th>
                <th class="p-3 fw-normal">Jurusan</th>
                <th class="p-3 fw-normal">Gambar</th>
                <th class="p-3 fw-normal">Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <!-- loop data -->
              <?php foreach ($data['mahasiswa'] as $mhs) : ?>
              <!-- jika nomor menghasilkan sisa berupa angka genap -->
              <?php if ($no % 2 == 0) : ?>
              <tr class="bg-light">
              <?php else : ?>
              <tr>
              <?php endif; ?>
                <td class="p-3 fw-light"><?= $no; ?></td>
                <td class="p-3 fw-light"><?= $mhs['nama']; ?></td>
                <td class="p-3 fw-light"><?= $mhs['nrp']; ?></td>
                <td class="p-3 fw-light"><?= $mhs['email']; ?></td>
                <td class="p-3 fw-light"><?= $mhs['jurusan']; ?></td>
                <td class="p-3 fw-light">
                  <img src="assets/images/<?= $mhs['gambar']; ?>" alt="image" class="rounded" width="60">
                </td>
                <td class="p-3 fw-light">
                  <a href="ubah.php?id=<?= $mhs['id']; ?>" class="btn btn-outline-primary rounded-1 mb-1">Ubah</a>
                  <a href="" class="btn btn-outline-danger rounded-1 btn-hapus" data-id="<?= $mhs['id']; ?>">Hapus</a>
                </td>
              </tr>
              <?php $no++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php else : ?>
      <div class="row">
        <div class="col-md">
          <div class="bg-light p-4 rounded shadow-sm my-2">
            <h3 class="fw-semibold">Pemberitahuan!</h3>
            <p class="fw-light">Tidak Ada Data Sama Sekali Di Database. silahkan tambahkan 1 data baru bila ingin melihat datanya berupa tabel</p>
          </div>
        </div>
      </div>
      <?php endif; ?>
      
    </div>
  </div>
</div>

<?php
$data['javascript'] = 'script.js';
// jalankan function view
view('footer', $data);
?>