<?php
// conexion.php para para  la conexión con SQLite

// Clase Database: Encapsula la conexión a la base de datos SQLite
class Database {
    private $ruta = __DIR__ . "\database\becal_sqlite.db";  //ruta de la base de datos 
    private $conexion;

    //metodo para establecer la conexion
    public function conectar() {
        if (!file_exists($this->ruta)) {                                        // ver si el archivo existe en la ruta DIR
            die("Error: No se encontró la base de datos en: " . $this->ruta); //die() detiene inmediatamente la ejecución del script.
        }

        try {
            //crea una nueva conexion PDO a SQLite
            $this->conexion = new PDO("sqlite:" . $this->ruta);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion;
        } catch (PDOException $e) {
            //para manejar errores de conexion
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
