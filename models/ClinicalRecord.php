<?php
class ClinicalRecord {
    private $conn;
    private $table_name = "Ficha_Registro_Clínico";

    public $IDHistorialClinico;
    public $Fecha_Generada_Clinica;
    public $DNI_Paciente;
    public $Localidad;
    public $Descripcion_Sintomas;
    public $Diagnostico_Preliminar;
    public $Tratamiento_Sugerido;
    public $DNIProfesional;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            // Si no se especifica fecha, usar hoy
            if (empty($this->Fecha_Generada_Clinica)) {
                $this->Fecha_Generada_Clinica = date('Y-m-d');
            }

            $query = "INSERT INTO `" . $this->table_name . "` 
                (DNI_Paciente, Fecha_Generada_Clinica, Localidad, `Descripción_Síntomas`, `Diagnóstico_Preliminar`, 
                `Tratamiento_Sugerido`, DNIProfesional) 
                VALUES (:dni_paciente, :fecha, :localidad, :sintomas, :diagnostico, :tratamiento, :dni_profesional)";

            $stmt = $this->conn->prepare($query);

            // Sanitizar
            $this->DNI_Paciente = htmlspecialchars(strip_tags($this->DNI_Paciente));
            $this->Localidad = htmlspecialchars(strip_tags($this->Localidad));
            $this->Descripcion_Sintomas = htmlspecialchars(strip_tags($this->Descripcion_Sintomas));
            $this->Diagnostico_Preliminar = htmlspecialchars(strip_tags($this->Diagnostico_Preliminar));
            $this->Tratamiento_Sugerido = htmlspecialchars(strip_tags($this->Tratamiento_Sugerido));

            $stmt->bindParam(':dni_paciente', $this->DNI_Paciente);
            $stmt->bindParam(':fecha', $this->Fecha_Generada_Clinica);
            $stmt->bindParam(':localidad', $this->Localidad);
            $stmt->bindParam(':sintomas', $this->Descripcion_Sintomas);
            $stmt->bindParam(':diagnostico', $this->Diagnostico_Preliminar);
            $stmt->bindParam(':tratamiento', $this->Tratamiento_Sugerido);
            $stmt->bindParam(':dni_profesional', $this->DNIProfesional);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log('[ClinicalRecord::create] Execute failed: ' . implode(' | ', $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log('[ClinicalRecord::create] PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function read() {
        try {
            $query = "SELECT * FROM `" . $this->table_name . "` ORDER BY IDHistorialClinico DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log('[ClinicalRecord::read] PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function getByDniProfesional($dniProfesional) {
        try {
            $query = "SELECT * FROM `" . $this->table_name . "` WHERE DNIProfesional = :dni ORDER BY IDHistorialClinico DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':dni', $dniProfesional);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log('[ClinicalRecord::getByDniProfesional] PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function getByDniPaciente($dniPaciente) {
        try {
            $query = "SELECT * FROM `" . $this->table_name . "` WHERE DNI_Paciente = :dni ORDER BY IDHistorialClinico DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':dni', $dniPaciente);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log('[ClinicalRecord::getByDniPaciente] PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function getTotal() {
        try {
            $query = "SELECT COUNT(*) as total FROM `" . $this->table_name . "`";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('[ClinicalRecord::getTotal] PDOException: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalByDniProfesional($dniProfesional) {
        try {
            $query = "SELECT COUNT(*) as total FROM `" . $this->table_name . "` WHERE DNIProfesional = :dni";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':dni', $dniProfesional);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('[ClinicalRecord::getTotalByDniProfesional] PDOException: ' . $e->getMessage());
            return 0;
        }
    }
}
?>
