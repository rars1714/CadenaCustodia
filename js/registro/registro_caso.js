document.getElementById("confirmBtn").addEventListener("click", function () {
  const idCaso = document.getElementById("id_caso").value;
  const fecha = document.getElementById("fecha_inicio").value;
  const nombre = document.getElementById("nombre_caso").value.trim();
  const descripcion = document.getElementById("descripcion").value.trim();

  if (!nombre || !descripcion) {
    alert("Por favor, completa los campos obligatorios.");
    return;
  }

  document.getElementById("confirmIdCaso").innerText = "ID del Caso: " + idCaso;
  document.getElementById("confirmFechaInicio").innerText = "Fecha de Inicio: " + fecha;
  document.getElementById("confirmNombreCaso").innerText = "Nombre del Caso: " + nombre;
  document.getElementById("confirmEstado").innerText = "Estado: Abierto";
  document.getElementById("confirmDescripcion").innerText = "Descripción: " + descripcion;

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
