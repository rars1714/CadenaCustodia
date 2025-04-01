// Al hacer clic en "Confirmar Datos", se recopilan y muestran los datos (excepto la contraseña)
document.getElementById("confirmBtn").addEventListener("click", function(){
  var idUsuario = document.getElementById("id_usuario").value;
  var nombre = document.getElementById("Nombre").value;
  var apellido = document.getElementById("Apellido").value;
  var despacho = document.getElementById("despacho").value;
  var correo = document.getElementById("Correo").value;
  var rol = document.getElementById("rol").value;

  // Puedes agregar validaciones adicionales antes de mostrar la confirmación
  if (!nombre || !apellido || !correo || !despacho || !rol || !document.getElementById("contrasena").value) {
    alert("Por favor, completa todos los campos.");
    return;
  }

// Mostrar los datos en la sección de confirmación
document.getElementById("confirmIdUsuario").innerText = "ID Usuario: " + (idUsuario ? idUsuario : "No especificado");
document.getElementById("confirmNombre").innerText = "Nombre: " + nombre;
document.getElementById("confirmApellido").innerText = "Apellido: " + apellido;
document.getElementById("confirmDespacho").innerText = "Despacho: " + despacho;
document.getElementById("confirmCorreo").innerText = "Correo: " + correo;
document.getElementById("confirmRol").innerText = "Rol: " + rol;

// Ocultar el formulario y mostrar la sección de confirmación
document.getElementById("registrationForm").style.display = "none";
document.getElementById("confirmation").style.display = "block";
});

// Botón "Editar": vuelve al formulario para hacer correcciones
document.getElementById("editBtn").addEventListener("click", function(){
document.getElementById("registrationForm").style.display = "block";
document.getElementById("confirmation").style.display = "none";
});

// Botón "Confirmar Registro": envía el formulario
document.getElementById("submitBtn").addEventListener("click", function(){
document.getElementById("registrationForm").submit();
});