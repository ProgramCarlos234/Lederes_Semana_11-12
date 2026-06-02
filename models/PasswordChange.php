<?php
class PasswordChange {
    private $conn;
    private $table_name = "CONTRASEÑAS_CAMBIADAS";

    public $ID_CONTRASEÑA;
    public $CONTRASEÑA_ACTUAL;
    public $CONTRASEÑA_NUEVA;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            // Generar ID único si no está establecido
            if (empty($this->ID_CONTRASEÑA)) {
                $this->ID_CONTRASEÑA = uniqid('chg_', true);
            }

            $query = "INSERT INTO `" . $this->table_name . "` 
                (ID_CONTRASEÑA, CONTRASEÑA_ACTUAL, CONTRASEÑA_NUEVA) 
                VALUES (:id, :actual, :nueva)";

            $stmt = $this->conn->prepare($query);

            // Bind
            $stmt->bindParam(':id', $this->ID_CONTRASEÑA);
            $stmt->bindParam(':actual', $this->CONTRASEÑA_ACTUAL);
            $stmt->bindParam(':nueva', $this->CONTRASEÑA_NUEVA);

            if ($stmt->execute()) {
                return true;
            }

            error_log('[PasswordChange::create] Execute failed: ' . implode(' | ', $stmt->errorInfo()));
            return false;
        } catch (PDOException $e) {
            error_log('[PasswordChange::create] PDOException: ' . $e->getMessage());
            return false;
        }
    }
}
?>