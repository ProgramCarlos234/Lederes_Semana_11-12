<?php
// Vista: Configuración de Usuario - Cambio de contraseña
?>

<div class="page-content">
    <div class="header-section">
        <h2>⚙️ Configuración de Cuenta</h2>
        <p>Cambia tu contraseña de acceso. Será aplicado tanto para profesionales como para pacientes.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card form-section">
        <div class="card-header"><h3>Cambiar Contraseña</h3></div>
        <div class="card-body">
            <form method="POST" action="?action=change_password" class="form-grid">
                <input type="hidden" name="action" value="change_password">

                <div class="form-group full-width">
                    <label for="current_password">Contraseña actual <span class="required">*</span></label>
                    <input type="password" id="current_password" name="current_password" required placeholder="Introduce tu contraseña actual">
                </div>

                <div class="form-group">
                    <label for="new_password">Nueva contraseña <span class="required">*</span></label>
                    <input type="password" id="new_password" name="new_password" required placeholder="Nueva contraseña (mín 6 caracteres)">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar nueva contraseña <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repite la nueva contraseña">
                </div>

                <div class="form-group full-width">
                    <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
.form-section { background: #fff; padding: 20px; border-radius: 8px; }
.form-group { display:flex; flex-direction: column; margin-bottom: 12px; }
.form-group.full-width { width:100%; }
input[type=password] { padding:8px; border:1px solid #ccc; border-radius:4px }
.btn-primary { background:#3498db; color:#fff; padding:10px 14px; border:none; border-radius:4px }
.alert { padding:12px; border-radius:6px; margin-bottom:12px }
.alert-success { background:#d4edda; color:#155724 }
.alert-danger { background:#f8d7da; color:#721c24 }
</style>
