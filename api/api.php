<?php
// encabezado que indicar rpta en JSON
header("Content-Type: application/json; charset=UTF-8");

//incluye el archivo de conexion
require_once "conexion.php";

//instancia de conexion a la BD 
$db = new Database();           //creamos la instancia
$conexion = $db->conectar();    //se usa el metodo conectar para obtener el PDO p/ las consultas

// Obtener parámetro “endpoint”
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;    //Verifica si se consgiue el parámetro endpoint por URL, sino es nulo


// Función auxiliar para ejecutar SQL y devolver JSON
function consulta($conexion, $sql, $params = []) {
    $query = $conexion->prepare($sql);                  //Prepara la consulta SQL para evitar inyecciones.
    $query->execute($params);                           //Ejecuta la consulta con parámetros seguros.
    return $query->fetchAll(PDO::FETCH_ASSOC);          //para devolver todos los resultados como un array asociativo (clave = nombre de columna).
}

//Enrutamiento segun el endpoint solicitado
switch ($endpoint) {

    // Nombres
    case "nombres":
        $sql = "SELECT DISTINCT NOMBRES AS nombres 
        FROM BECAL_IMPORTADO 
        WHERE NOMBRES != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Apellidos
    case "apellidos":
        $sql = "SELECT DISTINCT APELLIDOS AS apellidos 
        FROM BECAL_IMPORTADO 
        WHERE APELLIDOS != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Departamentos (DISTINCT)
    case "departamentos":
        $sql = "SELECT DISTINCT DEPARTAMENTO_RESIDENCIA AS departamento 
        FROM BECAL_IMPORTADO 
        WHERE DEPARTAMENTO_RESIDENCIA != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Tipos de Beca
    case "tipos_beca":
        $sql = "SELECT DISTINCT TIPO_BECA_RESUMEN AS tipo_beca 
        FROM BECAL_IMPORTADO
        WHERE TIPO_BECA_RESUMEN != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Países destino
    case "paises":
        $sql = "SELECT DISTINCT PAIS_DESTINO AS pais 
        FROM BECAL_IMPORTADO
        WHERE PAIS_DESTINO != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Sexo
    case "sexos":
        $sql = "SELECT DISTINCT SEXO AS sexo 
        FROM BECAL_IMPORTADO 
        WHERE SEXO != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Cod postulacion
    case "codigos_postu":
        $sql = "SELECT DISTINCT COD_POSTULACION AS codigos 
        FROM BECAL_IMPORTADO 
        WHERE COD_POSTULACION != ''";
        echo json_encode(consulta($conexion, $sql));
        break;     

    // Condicion
    case "condiciones":
        $sql = "SELECT DISTINCT CONDICION AS condicion 
        FROM BECAL_IMPORTADO 
        WHERE CONDICION != ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Condicion contractual
    case "contratos":
        $sql = "SELECT DISTINCT CONDICION_CONTRACTUAL AS condicion_contractual 
        FROM BECAL_IMPORTADO 
        WHERE CONDICION_CONTRACTUAL!= ''";
        echo json_encode(consulta($conexion, $sql));
        break;

    // Componente
    case "componente":
        $sql = "SELECT DISTINCT COMPONENTE AS componente 
        FROM BECAL_IMPORTADO 
        WHERE COMPONENTE != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
    // Area Ciencia
    case "areas_ciencia":
        $sql = "SELECT DISTINCT AREA_CIENCIA AS area_ciencia 
        FROM BECAL_IMPORTADO 
        WHERE AREA_CIENCIA != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
    // SubArea Ciencia
    case "subareas_ciencia":
        $sql = "SELECT DISTINCT SUBAREA_CIENCIA AS subarea_ciencia 
        FROM BECAL_IMPORTADO 
        WHERE SUBAREA_CIENCIA != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
    // Sector Prio Conacyt
    case "sectores_conacyt":
        $sql = "SELECT DISTINCT SECTOR_PRIO_CONACYT AS sector_conac 
        FROM BECAL_IMPORTADO 
        WHERE SECTOR_PRIO_CONACYT != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
    // Nombre programa de estudio
    case "programas_estudio":
        $sql = "SELECT DISTINCT NOMBRE_PROGRAMA_ESTUDIO AS programa_estudio 
        FROM BECAL_IMPORTADO 
        WHERE NOMBRE_PROGRAMA_ESTUDIO != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
    // Universidad
    case "universidades":
        $sql = "SELECT DISTINCT UNIVERSIDAD AS universidad 
        FROM BECAL_IMPORTADO 
        WHERE UNIVERSIDAD != ''";
        echo json_encode(consulta($conexion, $sql));
        break;  
    
    // Ciudad destino
    case "ciudades":
        $sql = "SELECT DISTINCT CIUDAD_DESTINO AS ciudad 
        FROM BECAL_IMPORTADO 
        WHERE CIUDAD_DESTINO != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
        
    // CVPY 
    case "cvpy":
        $sql = "SELECT DISTINCT CVPY AS cvpy 
        FROM BECAL_IMPORTADO 
        WHERE CVPY != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
    
    // becarios
    case "becarios":
        $sql = "SELECT NOMBRES, APELLIDOS, SEXO, DEPARTAMENTO_RESIDENCIA, TIPO_BECA_RESUMEN, AREA_CIENCIA, SUBAREA_CIENCIA, NOMBRE_PROGRAMA_ESTUDIO, UNIVERSIDAD, PAIS_DESTINO, CIUDAD_DESTINO, CVPY
        FROM BECAL_IMPORTADO
        WHERE NOMBRES != '' AND APELLIDOS != ''";
        echo json_encode(consulta($conexion, $sql));
        break;
        
        case "filtrar_avanzado":
        $sql = "SELECT * FROM BECAL_IMPORTADO WHERE 1=1";
        $params = [];

        if (!empty($_GET['nombre'])) {
            $sql .= " AND (NOMBRES LIKE ? OR APELLIDOS LIKE ?)";
            $nombre = "%" . $_GET['nombre'] . "%";
            $params[] = $nombre;
            $params[] = $nombre;
        }

        if (!empty($_GET['pais'])) {
            $sql .= " AND PAIS_DESTINO = ?";
            $params[] = $_GET['pais'];
        }

        if (!empty($_GET['departamento'])) {
            $sql .= " AND DEPARTAMENTO_RESIDENCIA = ?";
            $params[] = $_GET['departamento'];
        }

        if (!empty($_GET['tipo_beca'])){
            $sql .= "AND TIPO_BECA_RESUMEN = ?";
            $params[] = $_GET['tipo_beca'];
        }

        if (!empty($_GET['sexo'])) {
            $sql .= " AND SEXO = ?";
            $params[] = $_GET['sexo'];
        }

        echo json_encode(consulta($conexion, $sql, $params));
        break;

    // Endpoint desconocido
    default:
        echo json_encode(["error" => "Endpoint no válido"]);
}
