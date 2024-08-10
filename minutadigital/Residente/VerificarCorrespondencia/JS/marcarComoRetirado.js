function marcarComoRetirado(idCorrespondencia) {
    var confirmar = confirm("¿Estás seguro de marcar como retirada esta correspondencia?");
  
    if (confirmar) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            alert(response.message);
            // Recargar la página si la actualización fue exitosa
            location.reload();
          } else {
            alert('Error al marcar la correspondencia como retirada.');
          }
        }
      };
  
      xhr.open("POST", "/minutadigital/Residente/VerificarCorrespondencia/PHP/actualizar_correspondencia.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("idCorrespondencia=" + idCorrespondencia);
    } else {
      alert("Operación cancelada");
    }
  }
  