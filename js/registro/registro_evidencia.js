document.getElementById("confirm").addEventListener("click", function(){
  // Obtener valores
  var idEvidencia = document.getElementById("id_evidencia").value;
  var idCaso = document.getElementById("id_caso").value;
  var tipoEvidencia = document.getElementById("tipo_evidencia").value;
  var descripcion = document.getElementById("Descripcion").value;
  var nombreArchivo = document.getElementById("upload").files.length > 0 ? document.getElementById("upload").files[0].name : "";

  // Validar
  if (!idCaso || !tipoEvidencia || !descripcion) {
    alert("Por favor, completa todos los campos obligatorios.");
    return;
  }

  // Mostrar confirmación sin tocar el input
  document.getElementById("confirmid_evidencia").innerText = "ID Evidencia: " + (idEvidencia || "No especificado");
  document.getElementById("confirmid_caso").innerText = "ID Caso: " + (idCaso || "No especificado");
  document.getElementById("confirmtipo_evidencia").innerText = "Tipo de Evidencia: " + (tipoEvidencia || "No especificado");
  document.getElementById("confirmdescripcion").innerText = "Descripción: " + (descripcion || "No especificado");
  document.getElementById("confirmnombre_archivo").innerText = "Archivo: " + (nombreArchivo || "No especificado");

  // Mostrar sección de confirmación
  document.getElementById("registrationForm").style.display = "none";
  document.getElementById("confirmation").style.display = "block";
});

// Editar
document.getElementById("editBtn").addEventListener("click", function(){
  document.getElementById("registrationForm").style.display = "block";
  document.getElementById("confirmation").style.display = "none";
});

// Confirmar
document.getElementById("submitBtn").addEventListener("click", function(){
  document.getElementById("registrationForm").submit();
});
