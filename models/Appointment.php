<?php
class Appointment {
    private $conn;
    private $table_name = "GENERAR_CITA";

    public $IDCITAMEDICA;
    public $Nombre_Doctor_Asignado;
    public $Apellido_Paterno_Doctor;
    public $Apellido_Materno_Doctor;
    public $Tipo_cita_Virtual_presen;
    public $Descripcion_breve_cita;
    public $Estad_cita;
    public $ID_USUARIO;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            // Detectar si la tabla tiene una columna de fecha (varias convenciones posibles)
            $dbNameStmt = $this->conn->query('SELECT DATABASE()');
            $dbName = $dbNameStmt->fetchColumn();

            $dateColumns = ['fecha_registro','fecha_solicitud','fecha_generada','FECHA_REGISTRO','FECHA_SOLICITUD','FECHA_GENERADA'];
            $foundDateColumn = null;
            foreach ($dateColumns as $col) {
                $check = $this->conn->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table AND LOWER(COLUMN_NAME) = LOWER(:col)");
                $check->bindParam(':db', $dbName);
                $check->bindParam(':table', $this->table_name);
                $check->bindParam(':col', $col);
                $check->execute();
                if ($check->fetchColumn() > 0) {
                    $foundDateColumn = $col; // use provided convention
                    break;
                }
            }

            if ($foundDateColumn) {
                $query = "INSERT INTO `" . $this->table_name . "` 
                    (`Nombre_Doctor_Asignado`, `Apellido_Paterno_Doctor`, `Apellido_Materno_Doctor`, 
                     `Tipo_cita_Virtual_presen`, `Descripcion_breve_cita`, `Estad_cita`, `ID_USUARIO`, `" . $foundDateColumn . "`) 
                    VALUES (:nombre, :apellido_p, :apellido_m, :tipo, :descripcion, :estado, :id_usuario, NOW())";
            } else {
                $query = "INSERT INTO `" . $this->table_name . "` 
                    (`Nombre_Doctor_Asignado`, `Apellido_Paterno_Doctor`, `Apellido_Materno_Doctor`, 
                     `Tipo_cita_Virtual_presen`, `Descripcion_breve_cita`, `Estad_cita`, `ID_USUARIO`) 
                    VALUES (:nombre, :apellido_p, :apellido_m, :tipo, :descripcion, :estado, :id_usuario)";
            }

            $stmt = $this->conn->prepare($query);

            // Sanitizar
            $this->Nombre_Doctor_Asignado = htmlspecialchars(strip_tags($this->Nombre_Doctor_Asignado));
            $this->Apellido_Paterno_Doctor = htmlspecialchars(strip_tags($this->Apellido_Paterno_Doctor));
            $this->Apellido_Materno_Doctor = htmlspecialchars(strip_tags($this->Apellido_Materno_Doctor));
            $this->Tipo_cita_Virtual_presen = htmlspecialchars(strip_tags($this->Tipo_cita_Virtual_presen));
            $this->Descripcion_breve_cita = htmlspecialchars(strip_tags($this->Descripcion_breve_cita));
            $this->Estad_cita = htmlspecialchars(strip_tags($this->Estad_cita));

            $stmt->bindParam(':nombre', $this->Nombre_Doctor_Asignado);
            $stmt->bindParam(':apellido_p', $this->Apellido_Paterno_Doctor);
            $stmt->bindParam(':apellido_m', $this->Apellido_Materno_Doctor);
            $stmt->bindParam(':tipo', $this->Tipo_cita_Virtual_presen);
            $stmt->bindParam(':descripcion', $this->Descripcion_breve_cita);
            $stmt->bindParam(':estado', $this->Estad_cita);
            $stmt->bindParam(':id_usuario', $this->ID_USUARIO);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log('[Appointment::create] Execute failed: ' . implode(' | ', $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log('[Appointment::create] PDOException: ' . $e->getMessage());
            return false;
        }
    }

    public function read() {
        $query = "SELECT * FROM `" . $this->table_name . "` ORDER BY IDCITAMEDICA DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM `" . $this->table_name . "`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
}
?>
