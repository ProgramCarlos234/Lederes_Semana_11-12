<?php
class PatientController {
    private $patientModel;
    
    public function __construct($patientModel) {
        $this->patientModel = $patientModel;
    }
    
    public function createPatient($data) {
        $this->patientModel->DNI_Paciente = $data['dni'];
        $this->patientModel->Apellido_Paterno = $data['apellido_paterno'];
        $this->patientModel->Apellido_Materno = $data['apellido_materno'];
        $this->patientModel->Nombres_Completos = $data['nombres_completos'];
        $this->patientModel->Edad_Paciente = $data['edad'];
    $this->patientModel->Direccion_Paciente = $data['direccion'];
        $this->patientModel->Num_Telefono_Paciente = $data['telefono'];
        $this->patientModel->Correo_Electronico_Paciente = $data['correo'];
    $this->patientModel->Contrasena = password_hash($data['dni'], PASSWORD_DEFAULT);
        
        return $this->patientModel->create();
    }
    
    public function getAllPatients() {
        return $this->patientModel->read();
    }
    
    public function getPatientCount() {
        return $this->patientModel->getTotal();
    }
    
    public function getPatientByDni($dni) {
        return $this->patientModel->getByDni($dni);
    }
}
?>