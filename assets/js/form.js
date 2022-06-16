window.onload = () => {
  
  // lakukan preview gambar terlebih dahulu
  const image = document.querySelector('.image');
  const inputFile = document.querySelector('#upload-file');
  inputFile.addEventListener('change', function() {
    const file = this.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function() {
      image.setAttribute('src', this.result);
    } 
  });
  
}