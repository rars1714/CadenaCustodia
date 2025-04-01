    // Evento para habilitar edición en una fila (basado en el código de usuarios)
    document.querySelectorAll(".edit-btn").forEach(button => {
      button.addEventListener("click", function () {
        let row = this.closest("tr");
        row.querySelectorAll("input, select").forEach(input => input.removeAttribute("disabled"));
        row.querySelector(".edit-btn").style.display = "none";
        row.querySelector(".save-btn").style.display = "inline-block";
      });
    });

    // Evento para guardar cambios y enviarlos mediante fetch a actualizar_evidencia.php
    document.querySelectorAll(".save-btn").forEach(button => {
      button.addEventListener("click", function () {
        let row = this.closest("tr");
        let id = row.getAttribute("data-id");
        let idCaso = row.cells[1].querySelector("input").value;
        let idUsuario = row.cells[2].querySelector("input").value;
        let tipoEvidencia = row.cells[3].querySelector("select").value;
        let descripcion = row.cells[4].querySelector("input").value;
        let nombreArchivo = row.cells[5].querySelector("input").value;

        let formData = new FormData();
        formData.append("id", id);
        formData.append("id_caso", idCaso);
        formData.append("id_usuario", idUsuario);
        formData.append("tipo_evidencia", tipoEvidencia);
        formData.append("descripcion", descripcion);
        formData.append("nombre_archivo", nombreArchivo);

        fetch("actualizar_evidencia.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
          row.querySelectorAll("input, select").forEach(input => input.setAttribute("disabled", "disabled"));
          row.querySelector(".edit-btn").style.display = "inline-block";
          row.querySelector(".save-btn").style.display = "none";
        })
        .catch(error => console.error("Error:", error));
      });
    });