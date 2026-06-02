<?php
class Patient {
    private $conn;
    private $table_name = "REGISTRO_USUARIO_PACIENTE";

    public $DNI_Paciente;
    public $Apellido_Paterno;
    public $Apellido_Materno;
    public $Nombres_Completos;
    public $Edad_Paciente;
    public $Direccion_Paciente;
    public $Num_Telefono_Paciente;
    public $Correo_Electronico_Paciente;
    public $Contrasena;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
         (DNI_Paciente, Apellido_Paterno, Apellido_Materno, Nombres_Completos, 
         Edad_Paciente, `Dirección_Paciente`, Num_Telefono_Paciente, Correo_Electronico_Paciente, Contraseña)
         VALUES (:dni, :apellido_paterno, :apellido_materno, :nombres, :edad, :direccion, :telefono, :correo, :contrasena)";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->DNI_Paciente = htmlspecialchars(strip_tags($this->DNI_Paciente));
        $this->Apellido_Paterno = htmlspecialchars(strip_tags($this->Apellido_Paterno));
        $this->Apellido_Materno = htmlspecialchars(strip_tags($this->Apellido_Materno));
        $this->Nombres_Completos = htmlspecialchars(strip_tags($this->Nombres_Completos));
        $this->Edad_Paciente = htmlspecialchars(strip_tags($this->Edad_Paciente));
        $this->Direccion_Paciente = htmlspecialchars(strip_tags($this->Direccion_Paciente));
        $this->Num_Telefono_Paciente = htmlspecialchars(strip_tags($this->Num_Telefono_Paciente));
        $this->Correo_Electronico_Paciente = htmlspecialchars(strip_tags($this->Correo_Electronico_Paciente));

        // Bind parameters
        $stmt->bindParam(":dni", $this->DNI_Paciente);
        $stmt->bindParam(":apellido_paterno", $this->Apellido_Paterno);
        $stmt->bindParam(":apellido_materno", $this->Apellido_Materno);
        $stmt->bindParam(":nombres", $this->Nombres_Completos);
        $stmt->bindParam(":edad", $this->Edad_Paciente);
        $stmt->bindParam(":direccion", $this->Direccion_Paciente);
        $stmt->bindParam(":telefono", $this->Num_Telefono_Paciente);
        $stmt->bindParam(":correo", $this->Correo_Electronico_Paciente);
        $stmt->bindParam(":contrasena", $this->Contrasena);

        try {
            $this->conn->beginTransaction();
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                error_log('[Patient::create] Error al insertar paciente: ' . implode(' | ', $stmt->errorInfo()));
                return false;
            }

            // Asegurar que exista una entrada en TBL_IDUSUARIOS para este paciente
            // Usaremos el DNI como ID_USUARIO (cadena de hasta 15 caracteres) para mantener consistencia
            $idUsuario = $this->DNI_Paciente;

            // Verificar existencia
            $chk = $this->conn->prepare('SELECT ID_USUARIO FROM TBL_IDUSUARIOS WHERE DNI_Paciente = :dni OR ID_USUARIO = :id LIMIT 1');
            $chk->bindParam(':dni', $this->DNI_Paciente);
            $chk->bindParam(':id', $idUsuario);
            $chk->execute();
            $exists = $chk->fetch(PDO::FETCH_ASSOC);

            if (!$exists) {
                try {
                    $ins = $this->conn->prepare('INSERT INTO TBL_IDUSUARIOS (ID_USUARIO, DNI_Paciente) VALUES (:id, :dni)');
                    $ins->bindParam(':id', $idUsuario);
                    $ins->bindParam(':dni', $this->DNI_Paciente);
                    $ins->execute();
                } catch (PDOException $e) {
                    // Si falla por duplicado u otro motivo, loguear pero no impedir el registro del paciente
                    error_log('[Patient::create] No se pudo insertar en TBL_IDUSUARIOS: ' . $e->getMessage());
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            try { $this->conn->rollBack(); } catch (
                Exception $ee) {}
            error_log('[Patient::create] PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY DNI_Paciente DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getByDni($dni) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE DNI_Paciente = :dni";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $dni);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
    $query = "UPDATE " . $this->table_name . " 
         SET Apellido_Paterno=:apellido_paterno, Apellido_Materno=:apellido_materno, 
         Nombres_Completos=:nombres, Edad_Paciente=:edad, `Dirección_Paciente`=:direccion, 
         Num_Telefono_Paciente=:telefono, Correo_Electronico_Paciente=:correo 
         WHERE DNI_Paciente=:dni";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":dni", $this->DNI_Paciente);
        $stmt->bindParam(":apellido_paterno", $this->Apellido_Paterno);
        $stmt->bindParam(":apellido_materno", $this->Apellido_Materno);
        $stmt->bindParam(":nombres", $this->Nombres_Completos);
        $stmt->bindParam(":edad", $this->Edad_Paciente);
    $stmt->bindParam(":direccion", $this->Direccion_Paciente);
        $stmt->bindParam(":telefono", $this->Num_Telefono_Paciente);
        $stmt->bindParam(":correo", $this->Correo_Electronico_Paciente);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE DNI_Paciente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->DNI_Paciente);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>