document.getElementById("confirm").addEventListener("click", function () {
  var idEvidencia = document.getElementById("id_evidencia").value;
  var idCaso = document.getElementById("id_caso").value;
  var tipoEvidencia = document.getElementById("tipo_evidencia").value;
  var descripcion = document.getElementById("Descripcion").value;
  var archivoInput = document.getElementById("upload");
  var nombreArchivo = archivoInput.files.length > 0 ? archivoInput.files[0].name : "";

  if (!idCaso || !tipoEvidencia || !descripcion || archivoInput.files.length === 0) {
    alert("Por favor, completa todos los campos obligatorios y adjunta un archivo.");
    return;
  }

  document.getElementById("confirmid_evidencia").innerText = "ID Evidencia: " + idEvidencia;
  document.getElementById("confirmid_caso").innerText = "ID Caso: " + idCaso;
  document.getElementById("confirmtipo_evidencia").innerText = "Tipo de Evidencia: " + tipoEvidencia;
  document.getElementById("confirmdescripcion").innerText = "Descripción: " + descripcion;
  document.getElementById("confirmnombre_archivo").innerText = "Archivo: " + nombreArchivo;

  document.getElementById("registrationForm").style.display = "none";
  document.getElementById("confirmation").style.display = "block";
});

document.getElementById("editBtn").addEventListener("click", function () {
  document.getElementById("registrationForm").style.display = "block";
  document.getElementById("confirmation").style.display = "none";
});

document.getElementById("submitBtn").addEventListener("click", function () {
  document.getElementById("registrationForm").submit();
});
