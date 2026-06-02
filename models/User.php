<?php
class User {
    private $conn;
    private $table_profesional = "REGISTRO_Profesional_Salud";
    private $table_paciente = "REGISTRO_USUARIO_PACIENTE";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password, $userType) {
        try {
            // Seleccionar por email y luego verificar la contraseña (soporte para hashes)
            if ($userType === 'profesional') {
                $query = "SELECT * FROM " . $this->table_profesional . " WHERE Correo_Profesional = :email LIMIT 1";
            } else {
                $query = "SELECT * FROM " . $this->table_paciente . " WHERE Correo_Electronico_Paciente = :email LIMIT 1";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $storedHash = $row['Contraseña'] ?? null;

            // Si la contraseña está hasheada
            if ($storedHash && password_verify($password, $storedHash)) {
                return $row;
            }

            // Compatibilidad: si la contraseña en DB está en texto plano
            if ($storedHash && $password === $storedHash) {
                // Re-hashear y actualizar la base de datos para mejorar seguridad
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                try {
                    if ($userType === 'profesional') {
                        $updateQuery = "UPDATE " . $this->table_profesional . " SET Contraseña = :hash WHERE Correo_Profesional = :email";
                    } else {
                        $updateQuery = "UPDATE " . $this->table_paciente . " SET Contraseña = :hash WHERE Correo_Electronico_Paciente = :email";
                    }
                    $uStmt = $this->conn->prepare($updateQuery);
                    $uStmt->bindParam(':hash', $newHash);
                    $uStmt->bindParam(':email', $email);
                    $uStmt->execute();
                } catch (PDOException $e) {
                    error_log("[User::login] Error al actualizar hash: " . $e->getMessage());
                }

                return $row;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    // FUNCIÓN DE REGISTRO NUEVA
    public function register($data, $userType) {
        try {
            // Primero verificar si el DNI o email ya existen
            if ($userType === 'profesional') {
                $checkQuery = "SELECT COUNT(*) FROM " . $this->table_profesional . " 
                              WHERE DNI_Profesional = :dni OR Correo_Profesional = :email";
            } else {
                $checkQuery = "SELECT COUNT(*) FROM " . $this->table_paciente . " 
                              WHERE DNI_Paciente = :dni OR Correo_Electronico_Paciente = :email";
            }

            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':dni', $data['dni']);
            $checkStmt->bindParam(':email', $data['email']);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                return false; // DNI o email ya existen
            }

            // Insertar nuevo usuario
            if ($userType === 'profesional') {
                $query = "INSERT INTO " . $this->table_profesional . " 
                         (DNI_Profesional, Apellido_Paterno_Profesional, Apellido_Materno_Profesional, 
                         Nombres_Profesional, Edad_Profesional, Dirección_Profesional, 
                         Num_Telefono_Profesional, Correo_Profesional, Cargo_Profesional, Contraseña) 
                         VALUES (:dni, :apellido_paterno, :apellido_materno, :nombres, :edad, 
                         :direccion, :telefono, :email, :cargo, :password)";
            } else {
                $query = "INSERT INTO " . $this->table_paciente . " 
                         (DNI_Paciente, Apellido_Paterno, Apellido_Materno, Nombres_Completos, 
                         Edad_Paciente, Dirección_Paciente, Num_Telefono_Paciente, 
                         Correo_Electronico_Paciente, Contraseña) 
                         VALUES (:dni, :apellido_paterno, :apellido_materno, :nombres, :edad, 
                         :direccion, :telefono, :email, :password)";
            }

            $stmt = $this->conn->prepare($query);
            
            // Hashear la contraseña antes de guardar
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Bind parameters
            $stmt->bindParam(":dni", $data['dni']);
            $stmt->bindParam(":apellido_paterno", $data['apellido_paterno']);
            $stmt->bindParam(":apellido_materno", $data['apellido_materno']);
            $stmt->bindParam(":nombres", $data['nombres']);
            $stmt->bindParam(":edad", $data['edad']);
            $stmt->bindParam(":direccion", $data['direccion']);
            $stmt->bindParam(":telefono", $data['telefono']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":password", $hashedPassword);
            
            if ($userType === 'profesional') {
                $stmt->bindParam(":cargo", $data['cargo']);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en registro: " . $e->getMessage());
            return false;
        }
    }
}
?>