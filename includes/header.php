<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RuralMed - Sistema de Gestión de Salud Rural</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>RuralMed</h2>
                <button class="close-btn" id="closeSidebar">&times;</button>
            </div>
            <nav class="sidebar-nav">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <a href="index.php" class="nav-link">Dashboard</a>
                    <?php if ($_SESSION['user_type'] === 'profesional'): ?>
                        <a href="index.php?page=inventory" class="nav-link">Inventario</a>
                        <a href="index.php?page=emergencies" class="nav-link">Emergencias</a>
                        <a href="index.php?page=appointments" class="nav-link">Citas Médicas</a>
                        <a href="index.php?page=clinical_records" class="nav-link">Historial Clínico</a>
                        <a href="index.php?page=settings" class="nav-link">Configuración</a>
                    <?php else: ?>
                        <a href="index.php?page=appointments" class="nav-link">Mis Citas</a>
                        <a href="index.php?page=clinical_records" class="nav-link">Mi Historial</a>
                    <?php endif; ?>
                    <a href="index.php?page=chatbot" class="nav-link">
                        <i class="fas fa-comments" style="margin-right:8px;"></i>Asistente Médico
                    </a>
                    <a href="index.php?action=logout" class="nav-link logout">Cerrar Sesión</a>
                <?php endif; ?>
            </nav>
            <div class="sidebar-footer">
                <p>&copy; 2025 RuralMed</p>
            </div>
        </aside>

        <!-- Overlay para cerrar sidebar en mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header/Navbar -->
            <header class="navbar">
                <div class="navbar-container">
                    <button class="menu-toggle" id="menuToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="navbar-brand">
                        <h1>RuralMed</h1>
                    </div>
                    <div class="navbar-right">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                            <span class="user-info">
                                <?php 
                                $userName = isset($_SESSION['user']['NOMBRE']) ? $_SESSION['user']['NOMBRE'] : 'Usuario';
                                $userType = $_SESSION['user_type'] === 'profesional' ? '(Profesional)' : '(Paciente)';
                                echo htmlspecialchars($userName) . ' ' . $userType;
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
