<?php
class EmergencyController {
    private $emergencyModel;
    
    public function __construct($emergencyModel) {
        $this->emergencyModel = $emergencyModel;
    }
    
    public function reportEmergency($data, $dniProfesional) {
        $this->emergencyModel->Tipo_de_emergencia = $data['tipo_emergencia'];
    $this->emergencyModel->Descripcion_emerge = $data['descripcion'];
        $this->emergencyModel->GRAVEDAD = $data['gravedad'];
        $this->emergencyModel->DNIProfesional = $dniProfesional;
        
        return $this->emergencyModel->create();
    }
    
    public function getAllEmergencies() {
        return $this->emergencyModel->read();
    }
    
    public function getEmergencyCount() {
        return $this->emergencyModel->getTotal();
    }
    
    public function getEmergenciesByGravedad($gravedad) {
        return $this->emergencyModel->getByGravedad($gravedad);
    }
    
    public function updateEmergencyStatus($id, $status) {
        return $this->emergencyModel->updateStatus($id, $status);
    }
}
?>