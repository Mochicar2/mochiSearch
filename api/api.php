<?php
// encabezado que indica que la respuesta esta en JSON
header("Content-Type: application/json; charset=UTF-8");

//incluye el archivo de conexion
require_once "conexion.php";

$db = new Database();
$conexion = $db->conectar();

// Obtener parámetro “endpoint”
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;

// Función auxiliar para ejecutar SQL y devolver JSON
function consulta($conexion, $sql, $params = []) {
    $query = $conexion->prepare($sql);
    $query->execute($params);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

//Enrutamiento segun el endpoint solicitado
switch ($endpoint) {

    // 1) Departamentos (DISTINCT)
    case "departamentos":
        $sql = "SELECT DISTINCT DEPARTAMENTO_RESIDENCIA AS departamento 
                FROM BECAL_IMPORTADO 
                WHERE DEPARTAMENTO_RESIDENCIA != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // 2) Tipos de Beca
    case "tipos_beca":
        $sql = "SELECT DISTINCT TIPO_BECA_RESUMEN AS tipo_beca 
                FROM BECAL_IMPORTADO
                WHERE TIPO_BECA_RESUMEN != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // 3) Países destino
    case "paises":
        $sql = "SELECT DISTINCT PAIS_DESTINO AS pais 
                FROM BECAL_IMPORTADO
                WHERE PAIS_DESTINO != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // 4) Búsqueda por nombre o apellido
    //    Ejemplo: /api.php?endpoint=buscar&nombre=carlos
    case "buscar":
        $nombre = "%" . $_GET['nombre'] . "%";

        $sql = "SELECT NOMBRES, APELLIDOS, PAIS_DESTINO, TIPO_BECA_RESUMEN
                FROM BECAL_IMPORTADO
                WHERE NOMBRES LIKE ? OR APELLIDOS LIKE ?";
        echo json_encode(consulta($conexion, $sql, [$nombre, $nombre]));
        break;

    // 5) Becarios filtrados por tipo de beca + país destino
    //    Ejemplo:
    //    /api.php?endpoint=filtrar&beca=Maestria&pais=España
    case "filtrar":
        $beca = $_GET['beca'];
        $pais = $_GET['pais'];

        $sql = "SELECT * FROM BECAL_IMPORTADO
                WHERE TIPO_BECA_RESUMEN = ?
                AND PAIS_DESTINO = ?";
        echo json_encode(consulta($conexion, $sql, [$beca, $pais]));
        break;

    // Endpoint desconocido
    default:
        echo json_encode(["error" => "Endpoint no válido"]);
}
