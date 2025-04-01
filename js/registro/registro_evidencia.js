document.getElementById("confirm").addEventListener("click", function(){
  // Obtener valores de los campos del formulario de evidencia
  var idEvidencia = document.getElementById("id_evidencia").value;
  var idCaso = document.getElementById("id_caso").value;
  var tipoEvidencia = document.getElementById("tipo_evidencia").value;
  var descripcion = document.getElementById("Descripcion").value; // Asegúrate de que el id coincide, aquí "Descripcion" con D mayúscula
  var nombreArchivo = document.getElementById("upload").value; // Valor del input file (opcional)

  // Validar que los campos obligatorios estén completos
  if (!idCaso || !tipoEvidencia || !descripcion) {
    alert("Por favor, completa todos los campos obligatorios.");
    return;
  }

  // Mostrar los datos en la sección de confirmación, usando los IDs exactos del HTML
  document.getElementById("confirmid_evidencia").innerText = "ID Evidencia: " + (idEvidencia ? idEvidencia : "No especificado");
  document.getElementById("confirmid_caso").innerText = "ID Caso: " + (idCaso ? idCaso : "No especificado");
  document.getElementById("confirmtipo_evidencia").innerText = "Tipo de Evidencia: " + (tipoEvidencia ? tipoEvidencia : "No especificado");
  document.getElementById("confirmdescripcion").innerText = "Descripción: " + (descripcion ? descripcion : "No especificado");
  document.getElementById("confirmnombre_archivo").innerText = "Archivo: " + (nombreArchivo ? nombreArchivo : "No especificado");

  // Ocultar el formulario y mostrar la sección de confirmación
  document.getElementById("registrationForm").style.display = "none";
  document.getElementById("confirmation").style.display = "block";
});

// Botón "Editar": vuelve al formulario para correcciones
document.getElementById("editBtn").addEventListener("click", function(){
  document.getElementById("registrationForm").style.display = "block";
  document.getElementById("confirmation").style.display = "none";
});

// Botón "Confirmar Registro": envía el formulario
document.getElementById("submitBtn").addEventListener("click", function(){
  document.getElementById("registrationForm").submit();
});

