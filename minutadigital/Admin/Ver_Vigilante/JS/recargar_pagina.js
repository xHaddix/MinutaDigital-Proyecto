function eliminarRegistro(cedula) {
  var confirmar = confirm(
    "¿Estás seguro de que deseas eliminar este registro con cédula: " +
      cedula +
      "?"
  );

  if (confirmar) {
    // Realizar una solicitud al servidor para eliminar el registro
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      console.log(xhr.readyState, xhr.status);
      if (xhr.readyState == 4 && xhr.status == 200) {
        var response = JSON.parse(xhr.responseText);
        console.log(response);
        alert(response.message);
        // Recargar la página si la eliminación fue exitosa
        location.reload();
      }
    };

    xhr.open("POST", "eliminar_registro.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("cedula=" + cedula);
  } else {
    alert("Eliminación cancelada");
  }
}
