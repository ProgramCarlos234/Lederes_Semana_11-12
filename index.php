<?php
// Activar mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión al PRINCIPIO
session_start();

// Incluir archivos
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Patient.php';
require_once 'models/Inventory.php';
require_once 'models/Emergency.php';
require_once 'models/Appointment.php';
require_once 'models/PasswordChange.php';
require_once 'models/ClinicalRecord.php';
require_once 'utils/ReportGenerator.php';
require_once 'utils/ClinicalReportGenerator.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Inicializar modelos
$user = new User($db);
$patient = new Patient($db);
$inventory = new Inventory($db);
$emergency = new Emergency($db);
$appointment = new Appointment($db);
$passwordChange = new PasswordChange($db);
$clinicalRecord = new ClinicalRecord($db);

// PROCESAR ACCIONES - ESTO DEBE ESTAR ANTES DE CUALQUIER SALIDA HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $userType = $_POST['user_type'] ?? 'profesional';
        
        $userData = $user->login($email, $password, $userType);
        
        if($userData) {
            $_SESSION['user'] = $userData;
            $_SESSION['user_type'] = $userType;
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Credenciales incorrectas";
        }
    }
    
    // REGISTRO DE NUEVO USUARIO
    if ($action === 'register') {
        $userType = $_POST['user_type'] ?? 'profesional';
        $data = [
            'dni' => $_POST['dni'] ?? '',
            'apellido_paterno' => $_POST['apellido_paterno'] ?? '',
            'apellido_materno' => $_POST['apellido_materno'] ?? '',
            'nombres' => $_POST['nombres'] ?? '',
            'edad' => $_POST['edad'] ?? 0,
            'direccion' => $_POST['direccion'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'cargo' => $_POST['cargo'] ?? 'Profesional'
        ];
        
        if ($user->register($data, $userType)) {
            $_SESSION['success'] = "Usuario registrado exitosamente. Ahora puede iniciar sesión.";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Error al registrar usuario. El DNI o email ya existen.";
        }
    }
    
    // REGISTRAR NUEVO PACIENTE
    if ($action === 'register_patient') {
        $patient->DNI_Paciente = $_POST['dni'] ?? '';
        $patient->Apellido_Paterno = $_POST['apellido_paterno'] ?? '';
        $patient->Apellido_Materno = $_POST['apellido_materno'] ?? '';
        $patient->Nombres_Completos = $_POST['nombres_completos'] ?? '';
        $patient->Edad_Paciente = $_POST['edad'] ?? 0;
    $patient->Direccion_Paciente = $_POST['direccion'] ?? '';
    $patient->Num_Telefono_Paciente = $_POST['telefono'] ?? '';
    $patient->Correo_Electronico_Paciente = $_POST['correo'] ?? '';
    $patient->Contrasena = password_hash($_POST['dni'] ?? '123456', PASSWORD_DEFAULT);
        
        if($patient->create()) {
            $_SESSION['success'] = "✅ Paciente registrado exitosamente";
            header("Location: index.php?page=patients");
            exit;
        } else {
            $_SESSION['error'] = "❌ Error al registrar paciente. Verifique que el DNI no exista.";
            header("Location: index.php?page=patients");
            exit;
        }
    }
    
    // AGREGAR MEDICAMENTO AL INVENTARIO
    if ($action === 'add_inventory') {
        // DEBUG: Mostrar datos recibidos
        error_log("Datos recibidos para inventario:");
        error_log(print_r($_POST, true));
        
        $dniProfesional = $_SESSION['user']['DNI_Profesional'] ?? '12345678';
        
        // Asignar valores directamente al modelo
        $inventory->NOMBRE_MEDICAMENTO = $_POST['nombre_medicamento'] ?? '';
        $inventory->FECHA_CADUCIDAD = $_POST['fecha_caducidad'] ?? '';
        $inventory->DESCRIPCION_BREVE = $_POST['descripcion_breve'] ?? '';
        $inventory->CANTIDAD_STOCK = $_POST['cantidad_stock'] ?? 0;
        $inventory->UNIDADES = $_POST['unidades'] ?? '';
        $inventory->DNIProfesional = $dniProfesional;
        
        // DEBUG: Mostrar datos que se enviarán
        error_log("Datos a insertar:");
        error_log("Nombre: " . $inventory->NOMBRE_MEDICAMENTO);
        error_log("Fecha: " . $inventory->FECHA_CADUCIDAD);
        error_log("Descripción: " . $inventory->DESCRIPCION_BREVE);
        error_log("Stock: " . $inventory->CANTIDAD_STOCK);
        error_log("Unidades: " . $inventory->UNIDADES);
        error_log("DNI: " . $inventory->DNIProfesional);
        
        if($inventory->create()) {
            $_SESSION['success'] = "✅ Medicamento agregado al inventario exitosamente";
            header("Location: index.php?page=inventory");
            exit;
        } else {
            $_SESSION['error'] = "❌ Error al agregar medicamento al inventario. Verifica los datos.";
            header("Location: index.php?page=inventory");
            exit;
        }
    }
    
    // ACTUALIZAR STOCK DE MEDICAMENTO
    if ($action === 'update_stock') {
        $id = $_POST['id'] ?? 0;
        $newStock = $_POST['stock'] ?? 0;
        
        if ($inventory->updateStock($id, $newStock)) {
            $_SESSION['success'] = "✅ Stock actualizado correctamente";
        } else {
            $_SESSION['error'] = "❌ Error al actualizar el stock";
        }
        header("Location: index.php?page=inventory");
        exit;
    }
    
    // ELIMINAR MEDICAMENTO
    if ($action === 'delete_inventory') {
        $id = $_POST['id'] ?? 0;
        
        if ($inventory->delete($id)) {
            $_SESSION['success'] = "✅ Medicamento eliminado correctamente";
        } else {
            $_SESSION['error'] = "❌ Error al eliminar el medicamento";
        }
        header("Location: index.php?page=inventory");
        exit;
    }
    
    // REPORTAR EMERGENCIA
    if ($action === 'report_emergency') {
        // Validar que el usuario esté logueado como profesional y tenga DNI
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['user_type'] ?? '') !== 'profesional') {
            $_SESSION['error'] = "Debe iniciar sesión como profesional para reportar una emergencia.";
            header("Location: index.php?page=emergencies");
            exit;
        }

        $dniProfesional = $_SESSION['user']['DNI_Profesional'] ?? null;
        if (empty($dniProfesional)) {
            $_SESSION['error'] = "No se encontró DNI del profesional en sesión. Por favor, verifica tu perfil.";
            header("Location: index.php?page=emergencies");
            exit;
        }

        $emergency->DNI_Paciente = $_POST['dni_paciente'] ?? '';
        $emergency->Tipo_de_emergencia = $_POST['tipo_emergencia'] ?? '';
        $emergency->Descripcion_emerge = $_POST['descripcion'] ?? '';
        $emergency->GRAVEDAD = $_POST['gravedad'] ?? 'Media';
        $emergency->DNIProfesional = $dniProfesional;

        // Validar DNI del paciente
        if (empty($emergency->DNI_Paciente) || strlen($emergency->DNI_Paciente) < 6) {
            $_SESSION['error'] = "DNI del paciente inválido";
            header("Location: index.php?page=emergencies");
            exit;
        }

        // Verificar que el paciente exista en REGISTRO_USUARIO_PACIENTE
        try {
            $pstmt = $db->prepare('SELECT DNI_Paciente FROM REGISTRO_USUARIO_PACIENTE WHERE DNI_Paciente = :dni LIMIT 1');
            $pstmt->bindParam(':dni', $emergency->DNI_Paciente);
            $pstmt->execute();
            if ($pstmt->rowCount() === 0) {
                $_SESSION['error'] = 'No se encontró paciente con ese DNI. Registre al paciente antes o verifique el DNI.';
                header('Location: index.php?page=emergencies');
                exit;
            }
        } catch (PDOException $e) {
            error_log('[report_emergency] Error al verificar paciente: ' . $e->getMessage());
            $_SESSION['error'] = 'Error interno al verificar paciente.';
            header('Location: index.php?page=emergencies');
            exit;
        }

        if($emergency->create()) {
            $_SESSION['success'] = "✅ Emergencia reportada exitosamente";
            header("Location: index.php?page=emergencies");
            exit;
        } else {
            $_SESSION['error'] = "❌ Error al reportar emergencia. Revisa los datos o los logs del servidor.";
            header("Location: index.php?page=emergencies");
            exit;
        }
    }

    // CREAR CITA MÉDICA
    if ($action === 'create_appointment') {
        // El formulario envía el dni del paciente; buscamos su ID_USUARIO en TBL_IDUSUARIOS
        $dniPaciente = $_POST['dni_paciente'] ?? '';
        $nombreDoctor = $_POST['nombre_doctor'] ?? '';
        $apellidoP = $_POST['apellido_paterno_doctor'] ?? '';
        $apellidoM = $_POST['apellido_materno_doctor'] ?? '';
        $tipoCita = $_POST['tipo_cita'] ?? 'presencial';
        $descripcion = $_POST['descripcion_breve'] ?? '';

        // Validaciones básicas
        if (empty($dniPaciente) || strlen($dniPaciente) < 6) {
            $_SESSION['error'] = 'DNI de paciente inválido';
            header('Location: index.php?page=appointments');
            exit;
        }

        // Buscar ID_USUARIO en TBL_IDUSUARIOS
        try {
            $stmt = $db->prepare('SELECT ID_USUARIO FROM TBL_IDUSUARIOS WHERE DNI_Paciente = :dni LIMIT 1');
            $stmt->bindParam(':dni', $dniPaciente);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                // Si no existe una fila en TBL_IDUSUARIOS, intentamos crearla usando el DNI como ID_USUARIO
                try {
                    $idUsuario = $dniPaciente; // usar DNI como identificador
                    $ins = $db->prepare('INSERT INTO TBL_IDUSUARIOS (ID_USUARIO, DNI_Paciente) VALUES (:id, :dni)');
                    $ins->bindParam(':id', $idUsuario);
                    $ins->bindParam(':dni', $dniPaciente);
                    $ins->execute();
                    error_log('[create_appointment] Se creó ID_USUARIO en TBL_IDUSUARIOS para DNI: ' . $dniPaciente . ' con ID_USUARIO=' . $idUsuario);
                    $_SESSION['warning'] = 'Se registró automáticamente el identificador del paciente para generar la cita.';
                } catch (PDOException $e) {
                    // Si falla la inserción, registrar error y abortar
                    error_log('[create_appointment] Error al crear ID_USUARIO en TBL_IDUSUARIOS: ' . $e->getMessage());
                    $_SESSION['error'] = 'No se pudo vincular el DNI con un ID de usuario. Contacte al administrador.';
                    header('Location: index.php?page=appointments');
                    exit;
                }
            } else {
                $idUsuario = $row['ID_USUARIO'];
            }
        } catch (PDOException $e) {
            error_log('[create_appointment] Error al buscar ID_USUARIO: ' . $e->getMessage());
            $_SESSION['error'] = 'Error interno al procesar la solicitud.';
            header('Location: index.php?page=appointments');
            exit;
        }

        // Asignar al modelo y crear
        $appointment->Nombre_Doctor_Asignado = $nombreDoctor;
        $appointment->Apellido_Paterno_Doctor = $apellidoP;
        $appointment->Apellido_Materno_Doctor = $apellidoM;
        $appointment->Tipo_cita_Virtual_presen = $tipoCita;
        $appointment->Descripcion_breve_cita = $descripcion;
        $appointment->Estad_cita = 'pendiente';
        $appointment->ID_USUARIO = $idUsuario;

        if ($appointment->create()) {
            $_SESSION['success'] = '✅ Cita médica generada correctamente';
            header('Location: index.php?page=appointments');
            exit;
        } else {
            $_SESSION['error'] = '❌ Error al generar la cita. Revisa los datos o los logs.';
            header('Location: index.php?page=appointments');
            exit;
        }
    }

    // CREAR HISTORIAL CLÍNICO
    if ($action === 'create_clinical_record') {
        // Validar que el usuario esté logueado como profesional
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['user_type'] ?? '') !== 'profesional') {
            $_SESSION['error'] = "Debe iniciar sesión como profesional para registrar un historial clínico.";
            header("Location: index.php?page=clinical_records");
            exit;
        }

        $dniProfesional = $_SESSION['user']['DNI_Profesional'] ?? null;
        if (empty($dniProfesional)) {
            $_SESSION['error'] = "No se encontró DNI del profesional en sesión. Por favor, verifica tu perfil.";
            header("Location: index.php?page=clinical_records");
            exit;
        }

    // Asignar datos del formulario
    $clinicalRecord->DNI_Paciente = $_POST['dni_paciente'] ?? '';
    $clinicalRecord->Localidad = $_POST['localidad'] ?? '';
        $clinicalRecord->Fecha_Generada_Clinica = $_POST['fecha'] ?? date('Y-m-d');
        $clinicalRecord->Descripcion_Sintomas = $_POST['sintomas'] ?? '';
        $clinicalRecord->Diagnostico_Preliminar = $_POST['diagnostico'] ?? '';
        $clinicalRecord->Tratamiento_Sugerido = $_POST['tratamiento'] ?? '';
        $clinicalRecord->DNIProfesional = $dniProfesional;

        // Validar campos obligatorios
        if (empty($clinicalRecord->DNI_Paciente) || strlen($clinicalRecord->DNI_Paciente) < 6) {
            $_SESSION['error'] = 'DNI del paciente inválido';
            header('Location: index.php?page=clinical_records');
            exit;
        }

        // Verificar que el paciente exista
        try {
            $pstmt = $db->prepare('SELECT DNI_Paciente FROM REGISTRO_USUARIO_PACIENTE WHERE DNI_Paciente = :dni LIMIT 1');
            $pstmt->bindParam(':dni', $clinicalRecord->DNI_Paciente);
            $pstmt->execute();
            if ($pstmt->rowCount() === 0) {
                $_SESSION['error'] = 'No se encontró paciente con ese DNI. Registre al paciente antes o verifique el DNI.';
                header('Location: index.php?page=clinical_records');
                exit;
            }
        } catch (PDOException $e) {
            error_log('[create_clinical_record] Error al verificar paciente: ' . $e->getMessage());
            $_SESSION['error'] = 'Error interno al verificar paciente.';
            header('Location: index.php?page=clinical_records');
            exit;
        }

        // Validar campos obligatorios
        if (empty($clinicalRecord->Localidad) || empty($clinicalRecord->Descripcion_Sintomas) || 
            empty($clinicalRecord->Diagnostico_Preliminar) || empty($clinicalRecord->Tratamiento_Sugerido)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header('Location: index.php?page=clinical_records');
            exit;
        }

        if ($clinicalRecord->create()) {
            $_SESSION['success'] = '✅ Historial clínico registrado correctamente';
            header('Location: index.php?page=clinical_records');
            exit;
        } else {
            $_SESSION['error'] = '❌ Error al registrar historial clínico. Revisa los datos o los logs.';
            header('Location: index.php?page=clinical_records');
            exit;
        }
    }

    // CAMBIAR CONTRASEÑA
    if ($action === 'change_password') {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error'] = 'Debe iniciar sesión para cambiar la contraseña.';
            header('Location: index.php?page=settings');
            exit;
        }

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($current) || empty($new) || empty($confirm)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: index.php?page=settings');
            exit;
        }

        if (strlen($new) < 6) {
            $_SESSION['error'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
            header('Location: index.php?page=settings');
            exit;
        }

        if ($new !== $confirm) {
            $_SESSION['error'] = 'La nueva contraseña y su confirmación no coinciden.';
            header('Location: index.php?page=settings');
            exit;
        }

        // Obtener hash almacenado (si no está en sesión, leer DB)
        $storedHash = $_SESSION['user']['Contraseña'] ?? null;
        $userType = $_SESSION['user_type'] ?? 'profesional';
        $userDni = null;
        if ($userType === 'profesional') {
            $userDni = $_SESSION['user']['DNI_Profesional'] ?? null;
        } else {
            $userDni = $_SESSION['user']['DNI_Paciente'] ?? null;
        }

        if (!$storedHash) {
            // intentar leer de la BD
            try {
                if ($userType === 'profesional') {
                    $s = $db->prepare('SELECT Contraseña FROM REGISTRO_Profesional_Salud WHERE DNI_Profesional = :dni LIMIT 1');
                } else {
                    $s = $db->prepare('SELECT Contraseña FROM REGISTRO_USUARIO_PACIENTE WHERE DNI_Paciente = :dni LIMIT 1');
                }
                $s->bindParam(':dni', $userDni);
                $s->execute();
                $row = $s->fetch(PDO::FETCH_ASSOC);
                $storedHash = $row['Contraseña'] ?? null;
            } catch (PDOException $e) {
                error_log('[change_password] Error al leer contraseña almacenada: ' . $e->getMessage());
                $_SESSION['error'] = 'Error interno al procesar la solicitud.';
                header('Location: index.php?page=settings');
                exit;
            }
        }

        // Verificar contraseña actual (soporte para hash y texto plano)
        $ok = false;
        if ($storedHash) {
            if (password_verify($current, $storedHash)) {
                $ok = true;
            } elseif ($current === $storedHash) {
                $ok = true; // compatibilidad si estaba en texto plano
            }
        }

        if (!$ok) {
            $_SESSION['error'] = 'La contraseña actual es incorrecta.';
            header('Location: index.php?page=settings');
            exit;
        }

        // Preparar nuevo hash y actualizar la tabla correspondiente
        $newHash = password_hash($new, PASSWORD_DEFAULT);

        try {
            if ($userType === 'profesional') {
                $u = $db->prepare('UPDATE REGISTRO_Profesional_Salud SET Contraseña = :hash WHERE DNI_Profesional = :dni');
            } else {
                $u = $db->prepare('UPDATE REGISTRO_USUARIO_PACIENTE SET Contraseña = :hash WHERE DNI_Paciente = :dni');
            }
            $u->bindParam(':hash', $newHash);
            $u->bindParam(':dni', $userDni);
            $updated = $u->execute();

            if ($updated) {
                // Registrar el cambio en CONTRASEÑAS_CAMBIADAS
                $passwordChange->ID_CONTRASEÑA = uniqid('chg_', true);
                // Guardamos hashes para mayor seguridad en logs; si prefieres guardar texto plano, indícamelo (no recomendado)
                $passwordChange->CONTRASEÑA_ACTUAL = password_hash($current, PASSWORD_DEFAULT);
                $passwordChange->CONTRASEÑA_NUEVA = $newHash;
                $passwordChange->create();

                // Actualizar sesión para evitar desincronización
                $_SESSION['user']['Contraseña'] = $newHash;

                $_SESSION['success'] = '✅ Contraseña actualizada correctamente.';
                header('Location: index.php?page=settings');
                exit;
            } else {
                error_log('[change_password] Falló la actualización de la contraseña en BD: ' . implode(' | ', $u->errorInfo()));
                $_SESSION['error'] = 'No se pudo actualizar la contraseña. Contacte al administrador.';
                header('Location: index.php?page=settings');
                exit;
            }

        } catch (PDOException $e) {
            error_log('[change_password] PDOException: ' . $e->getMessage());
            $_SESSION['error'] = 'Error interno al actualizar la contraseña.';
            header('Location: index.php?page=settings');
            exit;
        }
    }
}

// GENERAR REPORTES (GET actions antes del HTML)
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'download_dashboard_pdf') {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error'] = 'Debes iniciar sesión para descargar reportes.';
            header('Location: index.php');
            exit;
        }
        
        try {
            ReportGenerator::generateDashboardPDF($db, $patient, $inventory, $emergency);
            exit;
        } catch (Exception $e) {
            error_log("[download_dashboard_pdf] Error: " . $e->getMessage());
            $_SESSION['error'] = 'Error al generar el PDF del dashboard.';
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
    
    if ($_GET['action'] === 'download_clinical_pdf') {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error'] = 'Debes iniciar sesión para descargar reportes.';
            header('Location: index.php');
            exit;
        }
        
        try {
            ClinicalReportGenerator::generateClinicalPDF($db, $clinicalRecord);
            exit;
        } catch (Exception $e) {
            error_log("[download_clinical_pdf] Error: " . $e->getMessage());
            $_SESSION['error'] = 'Error al generar el PDF del historial clínico.';
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
    
    if ($_GET['action'] === 'download_clinical_excel') {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error'] = 'Debes iniciar sesión para descargar reportes.';
            header('Location: index.php');
            exit;
        }
        
        try {
            ClinicalReportGenerator::generateClinicalExcel($db, $clinicalRecord);
            exit;
        } catch (Exception $e) {
            error_log("[download_clinical_excel] Error: " . $e->getMessage());
            $_SESSION['error'] = 'Error al generar el Excel del historial clínico.';
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}

// PROCESAR LOGOUT POR GET (esto debe estar ANTES del HTML)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    session_start(); // Reiniciar sesión para nuevos mensajes
    $_SESSION['success'] = "Sesión cerrada correctamente";
    header("Location: index.php");
    exit;
}

    // Obtener página actual
    $page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuralMed - Sistema de Gestión de Salud Rural</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <script>
        // Función para cambiar tema
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }

        // Función para actualizar el ícono del botón
        function updateThemeIcon() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'light';
            const icon = document.querySelector('.theme-toggle i');
            if (icon) {
                icon.className = currentTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        // Cargar tema guardado al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon();

            // Función para menú móvil
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            
            if (menuToggle && sidebarOverlay) {
                const toggleMenu = () => {
                    document.body.classList.toggle('sidebar-open');
                };
                menuToggle.addEventListener('click', toggleMenu);
                sidebarOverlay.addEventListener('click', toggleMenu);
            }
        });
    </script>
</head>
<body>
    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <!-- Main App Layout -->
        <div class="app-container">
            <!-- Sidebar Overlay -->
            <div class="sidebar-overlay"></div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-header">
                    <div class="logo">
                        <i class="fas fa-heartbeat"></i>
                        <span>RuralMed</span>
                    </div>
                    <div class="sidebar-subtitle">Gestión de Salud</div>
                </div>
                
                <nav class="sidebar-nav">
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'profesional'): ?>
                    <a href="?page=dashboard" class="nav-item <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="?page=inventory" class="nav-item <?php echo $page == 'inventory' ? 'active' : ''; ?>">
                        <i class="fas fa-pills"></i>
                        <span>Inventario</span>
                    </a>
                    <a href="?page=emergencies" class="nav-item <?php echo $page == 'emergencies' ? 'active' : ''; ?>">
                        <i class="fas fa-ambulance"></i>
                        <span>Emergencias</span>
                    </a>
                    <a href="?page=appointments" class="nav-item <?php echo $page == 'appointments' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Generar Cita</span>
                    </a>
                    <a href="?page=clinical_records" class="nav-item <?php echo $page == 'clinical_records' ? 'active' : ''; ?>">
                        <i class="fas fa-file-medical"></i>
                        <span>Historial Clínico</span>
                    </a>
                    <a href="?page=settings" class="nav-item <?php echo $page == 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'paciente'): ?>
                    <a href="?page=appointments" class="nav-item <?php echo $page == 'appointments' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Generar Cita</span>
                    </a>
                    <a href="?page=clinical_records" class="nav-item <?php echo $page == 'clinical_records' ? 'active' : ''; ?>">
                        <i class="fas fa-file-medical"></i>
                        <span>Historial Clínico</span>
                    </a>
                    <a href="?page=settings" class="nav-item <?php echo $page == 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                    <?php else: ?>
                    <!-- Roles no autenticados o no reconocidos: mínimo links -->
                    <a href="?page=appointments" class="nav-item <?php echo $page == 'appointments' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Generar Cita</span>
                    </a>
                    <a href="?page=settings" class="nav-item <?php echo $page == 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                    <?php endif; ?>
                </nav>
                
                <div class="sidebar-footer">
                    <button class="btn btn-primary" onclick="alert('Nuevo registro - puedes redirigir a la página que corresponda')">
                        <i class="fas fa-plus"></i>
                        <span>Nuevo Registro</span>
                    </button>
                    
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php 
                            $nombre = $_SESSION['user_type'] == 'profesional' 
                                ? ($_SESSION['user']['Nombres_Profesional'] ?? 'U') 
                                : ($_SESSION['user']['Nombres_Completos'] ?? 'P');
                            echo strtoupper(substr($nombre, 0, 1));
                            ?>
                        </div>
                        <div class="user-details">
                            <div class="user-name">
                                <?php 
                                if ($_SESSION['user_type'] == 'profesional') {
                                    echo 'Dr. ' . htmlspecialchars($_SESSION['user']['Nombres_Profesional'] ?? 'Usuario');
                                } else {
                                    echo htmlspecialchars($_SESSION['user']['Nombres_Completos'] ?? 'Paciente');
                                }
                                ?>
                            </div>
                            <div class="user-role">
                                <?php echo $_SESSION['user_type'] == 'profesional' ? 'Profesional de Salud' : 'Paciente'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <a href="?action=logout" class="logout-btn" onclick="return confirm('¿Está seguro de que desea cerrar sesión?')">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Bar -->
                <div class="top-bar">
                    <div class="top-bar-left">
                        <button class="menu-toggle" aria-label="Abrir menú" title="Menú">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="page-title">
                            <?php 
                            $titles = [
                                'dashboard' => 'Dashboard Principal',
                                'patients' => 'Gestión de Pacientes',
                                'inventory' => 'Control de Inventario',
                                'emergencies' => 'Gestión de Emergencias'
                            ];
                            echo $titles[$page] ?? 'Dashboard';
                            ?>
                        </h1>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Buscar...">
                        </div>
                    </div>
                    <div class="top-bar-right">
                        <button class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema">
                            <i class="fas fa-moon"></i>
                        </button>
                        <div class="notifications" title="Notificaciones">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge"></span>
                        </div>
                        <div class="top-bar-divider"></div>
                        <div class="top-bar-user">
                            <div class="top-bar-user-info">
                                <div class="top-bar-user-name">
                                    <?php 
                                    if ($_SESSION['user_type'] == 'profesional') {
                                        echo 'Dr. ' . htmlspecialchars($_SESSION['user']['Nombres_Profesional'] ?? 'Usuario');
                                    } else {
                                        echo htmlspecialchars($_SESSION['user']['Nombres_Completos'] ?? 'Paciente');
                                    }
                                    ?>
                                </div>
                                <div class="top-bar-user-role">
                                    <?php echo $_SESSION['user_type'] == 'profesional' ? 'Profesional de Salud' : 'Paciente'; ?>
                                </div>
                            </div>
                            <div class="top-bar-avatar">
                                <?php 
                                $nombre = $_SESSION['user_type'] == 'profesional' 
                                    ? ($_SESSION['user']['Nombres_Profesional'] ?? 'U') 
                                    : ($_SESSION['user']['Nombres_Completos'] ?? 'P');
                                echo strtoupper(substr($nombre, 0, 1));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    <?php
                    // Mostrar mensajes
                    if(isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
                        unset($_SESSION['success']);
                    }
                    if(isset($_SESSION['warning'])) {
                        echo '<div class="alert" style="background:#fff3cd;color:#856404;border:1px solid #ffeeba;">'.$_SESSION['warning'].'</div>';
                        unset($_SESSION['warning']);
                    }
                    if(isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
                        unset($_SESSION['error']);
                    }

                    // Cargar página correspondiente
                    $page_file = "views/" . $page . ".php";
                    if(file_exists($page_file)) {
                        include $page_file;
                    } else {
                        // Dashboard por defecto
                        ?>
                        <div class="card">
                            <h2>Bienvenido al Sistema RuralMed</h2>
                            <p>Hola, <?php 
                                if ($_SESSION['user_type'] == 'profesional') {
                                    echo 'Dr. ' . htmlspecialchars($_SESSION['user']['Nombres_Profesional'] ?? 'Usuario');
                                } else {
                                    echo htmlspecialchars($_SESSION['user']['Nombres_Completos'] ?? 'Paciente');
                                }
                            ?>!</p>
                        </div>

                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: #2E8B57;">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $patient->getTotal(); ?></h3>
                                    <p>Pacientes Registrados</p>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon" style="background: #3498db;">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $inventory->getTotal(); ?></h3>
                                    <p>Medicamentos en Stock</p>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon" style="background: #e74c3c;">
                                    <i class="fas fa-ambulance"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $emergency->getTotal(); ?></h3>
                                    <p>Emergencias Activas</p>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon" style="background: #f39c12;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>15</h3>
                                    <p>Citas del Día</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <h3>Acciones Rápidas</h3>
                            <div style="display: flex; gap: 15px; margin-top: 20px;">
                                <a href="?page=patients" style="background: #2E8B57; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-user-plus"></i> Registrar Paciente
                                </a>
                                <a href="?page=inventory" style="background: #3498db; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-pills"></i> Gestionar Inventario
                                </a>
                                <a href="?page=emergencies" style="background: #e74c3c; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-ambulance"></i> Reportar Emergencia
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Login Page con TABS para Login y Registro -->
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo">
                        <i class="fas fa-heartbeat"></i>
                        <span>RuralMed</span>
                    </div>
                    <p>Sistema de Gestión de Salud Rural</p>
                </div>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="login-tabs">
                    <div class="tab active" data-tab="login">Iniciar Sesión</div>
                    <div class="tab" data-tab="register">Registrarse</div>
                </div>

                <!-- FORMULARIO DE LOGIN -->
                <form method="POST" class="login-form active" id="loginForm">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="email" placeholder="usuario@ruralmed.com" required value="ricardo.villanueva@ruralmed.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" placeholder="••••••••" required value="ricardo1234">
                    </div>
                    
                    <div class="form-group">
                        <label>Tipo de Usuario</label>
                        <select name="user_type" required>
                            <option value="profesional" selected>Profesional de Salud</option>
                            <option value="paciente">Paciente</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

                <!-- FORMULARIO DE REGISTRO -->
                <form method="POST" class="login-form" id="registerForm">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="form-group">
                        <label>DNI *</label>
                        <input type="text" name="dni" maxlength="8" pattern="[0-9]{8}" required placeholder="12345678">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Apellido Paterno *</label>
                            <input type="text" name="apellido_paterno" required placeholder="Pérez">
                        </div>
                        <div class="form-group">
                            <label>Apellido Materno *</label>
                            <input type="text" name="apellido_materno" required placeholder="Gómez">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nombres Completos *</label>
                        <input type="text" name="nombres" required placeholder="Juan Carlos">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Edad *</label>
                            <input type="number" name="edad" min="18" max="100" required placeholder="30">
                        </div>
                        <div class="form-group">
                            <label>Teléfono *</label>
                            <input type="tel" name="telefono" required placeholder="987654321">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" placeholder="Av. Principal 123">
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico *</label>
                        <input type="email" name="email" required placeholder="usuario@ruralmed.com">
                    </div>

                    <div class="form-group">
                        <label>Contraseña *</label>
                        <input type="password" name="password" minlength="6" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label>Tipo de Usuario *</label>
                        <select name="user_type" required id="userTypeSelect">
                            <option value="profesional">Profesional de Salud</option>
                            <option value="paciente">Paciente</option>
                        </select>
                    </div>

                    <div class="form-group" id="cargoField">
                        <label>Cargo/Especialidad *</label>
                        <input type="text" name="cargo" placeholder="Médico, Enfermero, etc.">
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </button>
                </form>

                <div style="text-align: center; margin: 0 30px 30px; padding: 15px; background: var(--surface-container); border-radius: 8px; border: 1px solid var(--outline-variant);">
                    <small><strong>Credenciales de prueba:</strong><br>
                    <span style="color: var(--secondary); font-weight: 600;">Profesional:</span> ricardo.villanueva@ruralmed.com | ricardo1234<br>
                    <span style="color: var(--secondary); font-weight: 600;">Paciente:</span> juan@gmail.com | juan1234</small>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sistema de Tabs para Login/Registro
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const forms = document.querySelectorAll('.login-form');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabName = tab.getAttribute('data-tab');
                    
                    // Actualizar tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    
                    // Actualizar formularios
                    forms.forEach(form => form.classList.remove('active'));
                    document.getElementById(tabName + 'Form').classList.add('active');
                });
            });

            // Mostrar/ocultar campo cargo según tipo de usuario
            const userTypeSelect = document.getElementById('userTypeSelect');
            const cargoField = document.getElementById('cargoField');
            
            if (userTypeSelect && cargoField) {
                userTypeSelect.addEventListener('change', function() {
                    if (this.value === 'profesional') {
                        cargoField.style.display = 'block';
                    } else {
                        cargoField.style.display = 'none';
                    }
                });
                
                // Ejecutar al cargar
                if (userTypeSelect.value !== 'profesional') {
                    cargoField.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>