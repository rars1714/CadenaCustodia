document.getElementById('upload').addEventListener('change', function(event) {
  let fileList = document.getElementById('file-list');
  let evidenciaSelect = document.getElementById('id_evidencia'); // Captura el select
  fileList.innerHTML = ''; // Limpiar lista antes de agregar nuevos archivos

  for (let file of event.target.files) {
    let fileItem = document.createElement('span');
    fileItem.classList.add('formbold-filename');
    fileItem.innerHTML = `${file.name} 
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="this.parentElement.remove()">
        <path d="M9 7.9L12.7 4.2L13.7 5.3L10.1 9L13.7 12.7L12.7 13.8L9 10.1L5.3 13.8L4.2 12.7L7.9 9L4.2 5.3L5.3 4.2L9 7.9Z" fill="#536387"/>
      </svg>`;
    fileList.appendChild(fileItem);

    // Obtener la extensión del archivo
    let extension = file.name.split('.').pop().toLowerCase();

    // Mapear extensiones con el select de "Tipo de Evidencia"
    if (extension === 'pdf') {
      evidenciaSelect.value = 'documento';
    } else if (['png', 'jpg', 'jpeg', 'gif'].includes(extension)) {
      evidenciaSelect.value = 'imagen';
    } else if (['mp4', 'avi', 'mov', 'mkv'].includes(extension)) {
      evidenciaSelect.value = 'video';
    } else if (['mp3', 'wav', 'ogg'].includes(extension)) {
      evidenciaSelect.value = 'audio';
    } else {
      evidenciaSelect.value = 'Otro';
    }
  }
});