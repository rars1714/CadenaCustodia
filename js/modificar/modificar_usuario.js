document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function () {
        let row = this.closest("tr");
        row.querySelectorAll("input, select").forEach(input => input.removeAttribute("disabled"));
        row.querySelector(".edit-btn").style.display = "none";
        row.querySelector(".save-btn").style.display = "inline-block";
    });
});

document.querySelectorAll(".save-btn").forEach(button => {
    button.addEventListener("click", function () {
        let row = this.closest("tr");
        let id = row.getAttribute("data-id");
        let nombre = row.cells[1].querySelector("input").value;
        let apellido = row.cells[2].querySelector("input").value;
        let despacho = row.cells[3].querySelector("input").value;
        let correo = row.cells[4].querySelector("input").value;
        let rol = row.cells[5].querySelector("select").value;

        let formData = new FormData();
        formData.append("id", id);
        formData.append("Nombre", nombre);
        formData.append("Apellido", apellido);
        formData.append("despacho", despacho);
        formData.append("Correo", correo);
        formData.append("rol", rol);

        fetch("actualizar_usuario.php", {
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