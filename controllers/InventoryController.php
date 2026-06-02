<?php
class InventoryController {
    private $inventoryModel;
    
    public function __construct($inventoryModel) {
        $this->inventoryModel = $inventoryModel;
    }
    
    public function addMedicamento($data, $dniProfesional) {
        // Asignar valores al modelo
        $this->inventoryModel->NOMBRE_MEDICAMENTO = $data['nombre_medicamento'];
        $this->inventoryModel->FECHA_CADUCIDAD = $data['fecha_caducidad'];
        $this->inventoryModel->DESCRIPCION_BREVE = $data['descripcion_breve'];
        $this->inventoryModel->CANTIDAD_STOCK = $data['cantidad_stock'];
        $this->inventoryModel->UNIDADES = $data['unidades'];
        $this->inventoryModel->DNIProfesional = $dniProfesional;
        
        return $this->inventoryModel->create();
    }
    
    public function getAllMedicamentos() {
        return $this->inventoryModel->read();
    }
    
    public function getMedicamentoCount() {
        return $this->inventoryModel->getTotal();
    }
    
    public function getLowStock($threshold = 10) {
        return $this->inventoryModel->getLowStock($threshold);
    }
    
    public function updateStock($id, $newStock) {
        return $this->inventoryModel->updateStock($id, $newStock);
    }
    
    public function deleteMedicamento($id) {
        return $this->inventoryModel->delete($id);
    }
    
    public function getMedicamentoById($id) {
        return $this->inventoryModel->getById($id);
    }
    
    public function searchMedicamentos($searchTerm) {
        return $this->inventoryModel->search($searchTerm);
    }
}
?>