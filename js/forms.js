document.getElementById('upload').addEventListener('change', function(event) {
  let fileList = document.getElementById('file-list');
  let tipoSelect = document.getElementById('tipo_evidencia');
  fileList.innerHTML = '';

  for (let file of event.target.files) {
    let fileItem = document.createElement('span');
    fileItem.classList.add('formbold-filename');
    fileItem.innerHTML = `${file.name} 
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="this.parentElement.remove()">
        <path d="M9 7.9L12.7 4.2L13.7 5.3L10.1 9L13.7 12.7L12.7 13.8L9 10.1L5.3 13.8L4.2 12.7L7.9 9L4.2 5.3L5.3 4.2L9 7.9Z" fill="#536387"/>
      </svg>`;
    fileList.appendChild(fileItem);

    // Detectar tipo según extensión
    let extension = file.name.split('.').pop().toLowerCase();

    if (extension === 'pdf') {
      tipoSelect.value = 'PDF';
    } else if (['png', 'jpg', 'jpeg', 'gif'].includes(extension)) {
      tipoSelect.value = 'Imagen';
    } else if (['mp4', 'avi', 'mov', 'mkv'].includes(extension)) {
      tipoSelect.value = 'Video';
    } else if (['mp3', 'wav', 'ogg'].includes(extension)) {
      tipoSelect.value = 'Audio';
    } else {
      tipoSelect.value = 'Otro';
    }
  }
});

