<?php
class AuthController {
    private $userModel;
    
    public function __construct($userModel) {
        $this->userModel = $userModel;
    }
    
    public function login($email, $password, $userType) {
        return $this->userModel->login($email, $password, $userType);
    }
    
    public function register($data, $userType) {
        return $this->userModel->register($data, $userType);
    }
    
    public function logout() {
        session_destroy();
        return true;
    }
}
?>