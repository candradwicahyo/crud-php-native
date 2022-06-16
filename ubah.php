<?php
// jalankan session
session_start();
// panggil file functions.php
require 'functions.php';
// tangkap id yang berada di url
$id = escapeString($_GET['id']);
// data yang akan dikirim
$data['judul'] = 'Halaman Ubah Data';
// ambil data mahasiswa berdasarkan id
$data['mhs'] = getDataMahasiswaById($id)[0];
// jalankan function view
view('header', $data);
// jika tombol tambah ditekan
if (isset($_POST['ubah'])) {
  // jalankan function tambahData 
  if (ubahData($_POST, $data['mhs']['nrp'], $data['mhs']['email'], $data['mhs']['gambar'], $id) > 0) {
    // jika berhasil
    setFlash('flash', 'Data Mahasiswa Berhasil Diubah', 'success');
    header('Location: index.php?berhasil');
    exit;
  } else {
    // jika tidak ada session error
    if (!isset($_SESSION['error'])) {
      // jika gagal
      setFlash('flash', 'Data Mahasiswa Gagal Diubah', 'danger');
      header('Location: index.php?gagal');
      exit;
    }
  }
}
?>

<div class="container my-3">
  <div class="row">
    <div class="col-md-7 mx-auto">
      
      <div class="bg-primary p-4 rounded shadow-sm mb-3">
        <h1 class="fw-semibold text-white">Halaman Ubah Data</h1>
        <p class="fw-light text-light">
          halaman untuk mengubah data tertentu dan akan disimpan kedalam database lagi
        </p>
      </div>
      
      <?php
      // jika ada session error
      if (isset($_SESSION['error'])) {
        /*
          jalankan function flash guna untuk menampilkan error
          jika saat melakukan upload gambar terdapat kesalahan
        */
        echo flash('form');
        // hapus session error
        unset($_SESSION['error']);
      }
      ?>
      
      <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-2">
          <label for="nama" class="form-label fw-light">Nama Lengkap</label>
          <input type="text" name="nama" id="nama" class="form-control py-2 px-3" placeholder="nama lengkap" autocomplete="off" value="<?= $data['mhs']['nama']; ?>" required>
        </div>
        <div class="mb-2">
          <label for="nrp" class="form-label fw-light">Nrp</label>
          <input type="number" name="nrp" id="nrp" class="form-control py-2 px-3" placeholder="nrp" autocomplete="off" value="<?= $data['mhs']['nrp']; ?>" required>
        </div>
        <div class="mb-2">
          <label for="email" class="form-label fw-light">Alamat Email</label>
          <input type="email" name="email" id="email" class="form-control py-2 px-3" placeholder="example@example.com" autocomplete="off" value="<?= $data['mhs']['email']; ?>" required>
        </div>
        <div class="mb-2">
          <label for="jurusan" class="form-label fw-light">Jurusan</label>
          <input type="text" name="jurusan" id="jurusan" class="form-control py-2 px-3" placeholder="Jurusan" autocomplete="off" value="<?= $data['mhs']['jurusan']; ?>" required>
        </div>
        <div class="card mb-2">
          <div class="card-body">
            <img src="assets/images/<?= $data['mhs']['gambar']; ?>" class="image img-fluid rounded">
          </div>
        </div>
        <div class="mb-2">
          <label for="upload-file" class="form-label fw-light">Pilih Gambar</label>
          <input type="file" name="gambar" id="upload-file" class="form-control py-2 px-3" accept="image/*">
        </div>
        <button type="submit" name="ubah" class="btn btn-outline-primary rounded-1 my-2">Ubah Data</button>
      </form>
      
    </div>
  </div>
</div>

<?php
$data['javascript'] = 'form.js';
// jalankan function view
view('footer', $data);
?>