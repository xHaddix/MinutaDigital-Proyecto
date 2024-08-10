<?php
require_once 'db_connection.php';

// Verifica si se recibe la variable 'idCorrespondencia' mediante POST
if (isset($_POST['idCorrespondencia'])) {
    $idCorrespondencia = $_POST['idCorrespondencia'];

    // Realiza la actualización del estado de la correspondencia
    $sql = "UPDATE correspondencia SET estado = 'Retirado' WHERE id_correspondencia = $idCorrespondencia";
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Correspondencia marcada como retirada exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al marcar la correspondencia como retirada.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó el ID de correspondencia.']);
}

$conn->close();

?>