<?php
// panggil file functions.php
require 'functions.php';
// tangkap value yang ada di url
$value = test($_GET['value']);
// ambil data berdasarkan isi input pencarian data
$data['mahasiswa'] = cariData($value);
?>
<?php if ($data['mahasiswa']) : ?>
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
<?php else : ?>
<div class="row">
  <div class="col-md my-3 mx-auto">
    <div class="alert alert-primary p-3 rounded" role="alert">
      Data Tidak Ditemukan
    </div>
  </div>
</div>
<?php endif; ?>