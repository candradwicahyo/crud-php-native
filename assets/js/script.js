window.onload = () => {

  // hapus data
  const deleteButton = document.querySelectorAll('.btn-hapus');
  deleteButton.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      // tangkap id
      const id = this.dataset.id;
      // jalankan sweetalert2
      swal.fire ({
        icon: 'warning',
        title: 'peringatan!',
        text: 'apakah anda yakin ingin menghapus data tersebut?',
        showCancelButton: true,
        cancelButtonText: 'tidak',
        confirmButtonText: 'yakin'
      }).then(result => {
        // jika tombol yakin ditekan
        if (result.isConfirmed) {
          // arahkan ke file hapus.php
          // berserta kirimkan parameter id diurl tersebut
          document.location.href = `hapus.php?id=${id}`;
        }
      });
    });
  });

  // pencarian data
  const tableWrapper = document.querySelector('.table-wrapper');
  const searchInput = document.querySelector('.search-input');
  searchInput.addEventListener('keyup', async function() {
    const value = this.value.trim();
    const data = await getDataMahasiswa(value);
    tableWrapper.innerHTML = data;
  });

  function getDataMahasiswa(value) {
    return fetch('table.php?value=' + value)
    .then(response => response.text());
  }

}