<?php
// koneksi
$conn = mysqli_connect('localhost', 'root', '', 'phpdasar');

function view($param = 'index', $data = []) {
  // cek apakah isi dari param berupa string atau bukan
  if (is_string($param)) {
    // hapus spasi dibagian depan dN belakang
    $param = trim($param);
    // hapus tanda slash dibagian akhir bila ada
    $param = rtrim($param, '/');
    // pecah menjadi beberapa bagian bila ada . dibagian isi param
    $param = explode('.', $param);
    // panggil file
    require $param[0] . '.php';
  }
}

function query($param) {
  // panggil variabel conn yang berada diluar function
  global $conn;
  // lakukan query sesuai isi dari parameter $param
  $result = mysqli_query($conn, $param);
  // buat array kosong
  $data = [];
  // loop data yang didapatkan
  while ($row = mysqli_fetch_assoc($result)) {
    // masukkan data yang didapat kedalam array kosong
    $data[] = $row;
  }
  return $data;
}

function getAllDataMahasiswa() {
  // panggil variabel conn yang berada diluar function
  global $conn;
  // perintah query
  $query = "SELECT * FROM mahasiswa ORDER BY id DESC";
  // jalankan perintah
  return query($query);
}

function setFlash($nama, $pesan, $tipe) {
  /*
    cek apakah isi dari param nama, pesan dan tipe berupa
    string atau bukan. jika berupa string, buat session baru
  */
  if (is_string($nama) && is_string($pesan) && is_string($tipe)) {
    $_SESSION[$nama] = [
      'pesan' => $pesan,
      'tipe' => $tipe
    ];
  }
}

function flash($nama) {
  // cek apakah ada sebuah session dengan nama yang serupa dengan isi param
  if ($_SESSION[$nama]) {
    $string = '<div class="alert alert-'. $_SESSION[$nama]['tipe'] .' p-3 alert-dismissible fade show" role="alert">
    '. $_SESSION[$nama]['pesan'] . '
    <button class="btn-close" data-bs-dismiss="alert" aria-label="Closw"></button>
    </div>';
    unset($_SESSION[$nama]);
    return $string;
  }
}

function test($param) {
  // hapus spasi dibagian depan dan belakang
  $param = trim($param);
  // hindari element berbahaya
  $param = stripslashes($param);
  $param = htmlspecialchars($param);
  return $param;
}

function tambahData($data) {
  // panggil variabel conn yang berada diluar function
  global $conn;
  // tangkap value dari tiap" input
  $nama = test($data['nama']);
  $nrp = test($data['nrp']);
  $email = test($data['email']);
  $jurusan = test($data['jurusan']);
  // simpan value kedalam session
  $_SESSION['value'] = [
    'nama'  => $nama,
    'nrp'   => $nrp,
    'email' => $email
  ];
  // ambil data nrp berdasarkan isi input nrp
  $getDataNrp = query("SELECT nrp FROM mahasiswa WHERE nrp = '$nrp'")[0];
  // jika menghasilkan boolean true, tandanya data
  // nrp sudah pernah dipakai
  if ($getDataNrp) {
    setFlash('form', 'nrp sudah pernah dipakai!', 'danger');
    // buat session dengan nama error
    $_SESSION['error'] = true;
    // kembalikan nilai false
    return false;
  }
  // ambil data email berdasarkan isi input email
  $getDataEmail = query("SELECT email FROM mahasiswa WHERE email = '$email'")[0];
  // jika menghasilkan boolean true, tandanya data
  // email sudah pernah dipakai!
  if ($getDataEmail) {
    setFlash('form', 'email sudah pernah dipakai!', 'danger');
    // buat session dengan nama error
    $_SESSION['error'] = true;
    // kembalikan nilai false
    return false;
  }
  // jalankan function upload
  $gambar = upload();
  // jika variabel gambar menghasilkan boolean false, hentikan function
  if (!$gambar) {
    // buat session dengan nama error
    $_SESSION['error'] = true;
    // kembalikan nilai false
    return false;
  }
  // perintah query
  $query = "INSERT INTO mahasiswa VALUES('', '$nama', '$nrp', '$email', '$jurusan', '$gambar')";
  // jalankan perintah
  mysqli_query($conn, $query);
  // hapus session value
  unset($_SESSION['value']);
  // kembalikan nilai antara 0 sampai 1
  return mysqli_affected_rows($conn);
}

function upload() {
  // tangkap tiap" value dari input file upload
  $namaFile   = $_FILES['gambar']['name'];
  $ukuranFile = $_FILES['gambar']['size'];
  $error      = $_FILES['gambar']['error'];
  $tmpName    = $_FILES['gambar']['tmp_name'];
  // cek apakah user mengupload file atau tidak
  if ($error === 4) {
    setFlash('form', 'upload file gambar terlebih dahulu!', 'danger');
    return false;
  }
  // validasi ekstensi file yanv diupload
  // apakah sudah menentui persyaratan
  $ekstensiGambarValid = ['jpg','jpeg','png','gif'];
  // pecah beberapa bagian dengan delimiternya adalah tanda . / titik
  $ekstensiFile = explode('.', $namaFile);
  // ambil bagian paling akhir untuk mendapafkan ekstensi file
  $ekstensiFile = end($ekstensiFile);
  // ubah format text menjadi kwcil semua
  $ekstensiFile = strtolower($ekstensiFile);
  // jika ekstensj yang diupload tidak sesuai dengan ekstensi yang ditentukan
  if (!in_array($ekstensiFile, $ekstensiGambarValid)) {
    setFlash('form', 'file yang anda upload bukanlah gambar!', 'danger');
    return false;
  }
  // cek ukuran file gambar
  $limit = 4000000; // 4 megabyte / 4mb
  // jika melebihi batas maximum
  if ($ukuranFile > $limit) {
    setFlash('form', 'ukuran file gambar terlalu besar!', 'danger');
    return false;
  }
  // jadikan nama file gambar menjadi unik sehingga tidak dapat diduplikat oleh data lain
  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensiFile;
  // pindahkan file yang diupload ke directory tertentu
  move_uploaded_file($tmpName, 'assets/images/' . $namaFileBaru);
  return $namaFileBaru;
}

function escapeString($param) {
  // panggil variabel conn yang berada diluar function
  global $conn;
  return mysqli_real_escape_string($conn, $param);
}

function hapusData($id) {
  // panggil variabel conn yang berada diluar function
  global $conn;
  // perintah query
  $query = "DELETE FROM mahasiswa WHERE id = '$id'";
  // jalankan perintah
  mysqli_query($conn, $query);
  // kembalikan nilai dari angka 0 sampai 1
  return mysqli_affected_rows($conn);
}

function getDataMahasiswaById($id) {
  // panggil variabel conn yang berada diluar function
  global $conn;
  // perintah query
  $query = "SELECT * FROM mahasiswa WHERE id = '$id'";
  // jalankan perintah query
  return query($query);
}

function ubahData($data, $nrpLama, $emailLama, $gambarLama, $id) {
  // panggil variabel conn yang berada diluar function
  global $conn;
  // tangkap value dari tiap" input
  $nama = test($data['nama']);
  $nrp = test($data['nrp']);
  $email = test($data['email']);
  $jurusan = test($data['jurusan']);
  // jika isi input nrp tidak sama dengan value sebelumnya
  if ($nrp !== $nrpLama) {
    // ambil data nrp berdasarkan isi input nrp
    $getDataNrp = query("SELECT nrp FROM mahasiswa WHERE nrp = '$nrp'")[0];
    // jika menghasilkan boolean true, tandanya data
    // nrp sudah pernah dipakai
    if ($getDataNrp) {
      setFlash('form', 'nrp sudah pernah dipakai!', 'danger');
      // buat session dengan nama error
      $_SESSION['error'] = true;
      // kembalikan nilai false
      return false;
    }
  }
  // jika isi input email tidak sama dengan value sebelumnya
  if ($email !== $emailLama) {
    // ambil data email berdasarkan isi input email
    $getDataEmail = query("SELECT email FROM mahasiswa WHERE email = '$email'")[0];
    // jika menghasilkan boolean true, tandanya data
    // email sudah pernah dipakai!
    if ($getDataEmail) {
      setFlash('form', 'email sudah pernah dipakai!', 'danger');
      // buat session dengan nama error
      $_SESSION['error'] = true;
      // kembalikan nilai false
      return false;
    }
  }
  // cek apakah user mengupload gambar atau tidak
  $error = $_FILES['gambar']['error'];
  // jika user tidak mengupload gambar, jadikan gambar lama 
  // sebagai data yang akan dikirim ke database
  $gambar = ($error === 4) ? $gambarLama : upload();
  // jika variabel gambar menghasilkan boolean false, hentikan function
  if (!$gambar) {
    // buat session dengan nama error
    $_SESSION['error'] = true;
    // kembalikan nilai false
    return false;
  }
  // perintah query
  $query = "UPDATE mahasiswa SET 
           nama = '$nama',
           nrp = '$nrp',
           email = '$email',
           jurusan = '$jurusan',
           gambar = '$gambar'
           WHERE id = '$id'";
  // jalankan perintah query
  mysqli_query($conn, $query);
  // kembalikan nilai antara angka 0 sampai 1
  return mysqli_affected_rows($conn);
}

function cariData($keyword) {
  // perintah query
  $query = "SELECT * FROM mahasiswa WHERE
            nama LIKE '%$keyword%' OR
            nrp LIKE '%$keyword%' OR
            email LIKE '%$keyword%' OR
            jurusan LIKE '%$keyword%'";
  // jalankan perintah query
  return query($query);
}