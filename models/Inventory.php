<?php
class Inventory {
    private $conn;
    private $table_name = "REGISTRAR_INVENTARIO";

    public $IDmedicamento;
    public $NOMBRE_MEDICAMENTO;
    public $FECHA_CADUCIDAD;
    public $DESCRIPCION_BREVE;
    public $CANTIDAD_STOCK;
    public $UNIDADES;
    // Usar nombres de propiedades sin caracteres especiales
    public $FECHA_ACTUALIZACION_STOCK;
    public $DNIProfesional;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            // Usar backticks para nombres de columnas (evita problemas con caracteres especiales)
            $query = "INSERT INTO `" . $this->table_name . "` 
                     (`NOMBRE_MEDICAMENTO`, `FECHA_CADUCIDAD`, `DESCRIPCION_BREVE`, 
                      `CANTIDAD_STOCK`, `UNIDADES`, `FECHA_ACTUALIZACIÓN_STOCK`, `DNIProfesional`) 
                     VALUES (:nombre, :fecha_caducidad, :descripcion, :cantidad, :unidades, NOW(), :dni_profesional)";

            $stmt = $this->conn->prepare($query);

            // Limpiar y normalizar datos
            $this->NOMBRE_MEDICAMENTO = htmlspecialchars(strip_tags($this->NOMBRE_MEDICAMENTO));
            $this->DESCRIPCION_BREVE = htmlspecialchars(strip_tags($this->DESCRIPCION_BREVE));
            $this->UNIDADES = htmlspecialchars(strip_tags($this->UNIDADES));

            // Normalizar fecha y cantidad
            $fecha = !empty($this->FECHA_CADUCIDAD) ? $this->FECHA_CADUCIDAD : null;
            $cantidad = is_numeric($this->CANTIDAD_STOCK) ? (int)$this->CANTIDAD_STOCK : 0;

            // Bind parameters
            $stmt->bindParam(":nombre", $this->NOMBRE_MEDICAMENTO);
            $stmt->bindParam(":fecha_caducidad", $fecha);
            $stmt->bindParam(":descripcion", $this->DESCRIPCION_BREVE);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":unidades", $this->UNIDADES);
            $stmt->bindParam(":dni_profesional", $this->DNIProfesional);

            if($stmt->execute()) {
                return true;
            } else {
                error_log("[Inventory::create] Execute failed: " . implode(" | ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log("[Inventory::create] PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY CANTIDAD_STOCK ASC, FECHA_CADUCIDAD ASC";
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

    public function getLowStock($threshold = 10) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE CANTIDAD_STOCK <= :threshold";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":threshold", $threshold);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function updateStock($id, $newStock) {
        $query = "UPDATE " . $this->table_name . " 
                 SET CANTIDAD_STOCK = :stock, FECHA_ACTUALIZACIÓN_STOCK = NOW() 
                 WHERE IDmedicamento = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":stock", $newStock);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE IDmedicamento = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE IDmedicamento = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChartData() {
        try {
            $query = "SELECT `NOMBRE_MEDICAMENTO`, `CANTIDAD_STOCK` FROM " . $this->table_name . " ORDER BY `CANTIDAD_STOCK` DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results ?: [];
        } catch (PDOException $e) {
            error_log("[Inventory::getChartData] PDOException: " . $e->getMessage());
            return [];
        }
    }
}
?>