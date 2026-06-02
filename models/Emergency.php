<?php
class Emergency {
    private $conn;
    private $table_name = "REGISTRAR_EMERGENCIA";

    public $ID_EMERGENCIA;
    public $Tipo_de_emergencia;
    public $Descripcion_emerge;
    public $GRAVEDAD;
    public $DNIProfesional;
    public $DNI_Paciente;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
          $query = "INSERT INTO `" . $this->table_name . "` 
              (DNI_Paciente, `Tipo_de_emergencia`, `Descripción_emerge`, `GRAVEDAD`, `DNIProfesional`) 
              VALUES (:dni_paciente, :tipo, :descripcion, :gravedad, :dni_profesional)";

            $stmt = $this->conn->prepare($query);

            // Limpiar datos
            $this->DNI_Paciente = htmlspecialchars(strip_tags($this->DNI_Paciente));
            $this->Tipo_de_emergencia = htmlspecialchars(strip_tags($this->Tipo_de_emergencia));
            $this->Descripcion_emerge = htmlspecialchars(strip_tags($this->Descripcion_emerge));
            $this->GRAVEDAD = htmlspecialchars(strip_tags($this->GRAVEDAD));

            // Bind parameters
            $stmt->bindParam(":dni_paciente", $this->DNI_Paciente);
            $stmt->bindParam(":tipo", $this->Tipo_de_emergencia);
            $stmt->bindParam(":descripcion", $this->Descripcion_emerge);
            $stmt->bindParam(":gravedad", $this->GRAVEDAD);
            $stmt->bindParam(":dni_profesional", $this->DNIProfesional);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log("[Emergency::create] Execute failed: " . implode(" | ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log("[Emergency::create] PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ID_EMERGENCIA DESC";
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

    public function getByGravedad($gravedad) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE GRAVEDAD = :gravedad";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":gravedad", $gravedad);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " 
                 SET GRAVEDAD = :status 
                 WHERE ID_EMERGENCIA = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ID_EMERGENCIA = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEmergenciesByGravedad() {
        try {
            $query = "SELECT `GRAVEDAD`, COUNT(*) as cantidad FROM " . $this->table_name . " GROUP BY `GRAVEDAD`";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results ?: [];
        } catch (PDOException $e) {
            error_log("[Emergency::getEmergenciesByGravedad] PDOException: " . $e->getMessage());
            return [];
        }
    }

    public function getEmergenciesByType() {
        try {
            $query = "SELECT `Tipo_de_emergencia`, COUNT(*) as cantidad FROM " . $this->table_name . " GROUP BY `Tipo_de_emergencia`";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results ?: [];
        } catch (PDOException $e) {
            error_log("[Emergency::getEmergenciesByType] PDOException: " . $e->getMessage());
            return [];
        }
    }
}
?>