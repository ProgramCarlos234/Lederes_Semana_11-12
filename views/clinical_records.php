<?php
// Vista: Historial Clínico
?>

<div class="page-content">
    <div class="header-section">
        <h2>📋 Historial Clínico</h2>
        <p>Registra y consulta los historiales clínicos de los pacientes</p>
    </div>

    <!-- Mostrar mensajes -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['warning'])): ?>
        <div class="alert alert-warning">
            <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para crear historial clínico (solo para profesionales) -->
    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'profesional'): ?>
    <div class="form-section">
        <h3>Registrar Nuevo Historial Clínico</h3>
        <form method="POST" action="?action=create_clinical_record" class="form-grid">
            <input type="hidden" name="action" value="create_clinical_record">
            <div class="form-group">
                <label for="dni_paciente">DNI del Paciente <span class="required">*</span></label>
                <select id="dni_paciente" name="dni_paciente" required>
                    <option value="">Seleccionar paciente</option>
                    <?php
                    // Llenar opciones con pacientes registrados
                    $patientsStmt = $patient->read();
                    while ($p = $patientsStmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <option value="<?php echo htmlspecialchars($p['DNI_Paciente']); ?>"><?php echo htmlspecialchars($p['DNI_Paciente'] . ' - ' . $p['Nombres_Completos']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="localidad">Localidad <span class="required">*</span></label>
                <input type="text" id="localidad" name="localidad" placeholder="Ej: Consultorio A, Sala de Emergencia" required>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha del Registro <span class="required">*</span></label>
                <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group full-width">
                <label for="sintomas">Descripción de Síntomas <span class="required">*</span></label>
                <textarea id="sintomas" name="sintomas" rows="4" placeholder="Describe los síntomas reportados por el paciente" required></textarea>
            </div>

            <div class="form-group full-width">
                <label for="diagnostico">Diagnóstico Preliminar <span class="required">*</span></label>
                <textarea id="diagnostico" name="diagnostico" rows="4" placeholder="Diagnóstico preliminar basado en síntomas" required></textarea>
            </div>

            <div class="form-group full-width">
                <label for="tratamiento">Tratamiento Sugerido <span class="required">*</span></label>
                <textarea id="tratamiento" name="tratamiento" rows="4" placeholder="Tratamiento recomendado" required></textarea>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn btn-primary">Registrar Historial Clínico</button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        ⚠️ Solo los profesionales de salud pueden registrar historiales clínicos.
    </div>
    <?php endif; ?>

    <!-- Listado de historiales clínicos -->
    <div class="list-section">
        <h3>Listado de Historiales Clínicos</h3>
        
        <?php
        // Si es profesional, mostrar solo sus historiales; si es administrador o paciente, mostrar todos
        $records = null;
        $total = 0;
        
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'profesional') {
            $dniProf = $_SESSION['user']['DNI_Profesional'] ?? null;
            if ($dniProf) {
                $records = $clinicalRecord->getByDniProfesional($dniProf);
                $total = $clinicalRecord->getTotalByDniProfesional($dniProf);
            }
        } elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'paciente') {
            $dniPac = $_SESSION['user']['DNI_Paciente'] ?? null;
            if ($dniPac) {
                $records = $clinicalRecord->getByDniPaciente($dniPac);
                // reuse getTotal by counting result
                $total = $records ? $records->rowCount() : 0;
            }
        } else {
            // otros roles (admin) ven todo
            $records = $clinicalRecord->read();
            $total = $clinicalRecord->getTotal();
        }
        ?>

        <p class="records-count">Total de registros: <strong><?php echo $total; ?></strong></p>

        <?php if ($records && $records->rowCount() > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente (DNI)</th>
                        <th>Fecha</th>
                        <th>Localidad</th>
                        <th>Síntomas</th>
                        <th>Diagnóstico</th>
                        <th>Tratamiento</th>
                        <th>DNI Profesional</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $records->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['IDHistorialClinico']); ?></td>
                        <td><?php echo htmlspecialchars($row['DNI_Paciente']); ?></td>
                        <td><?php echo htmlspecialchars($row['Fecha_Generada_Clinica']); ?></td>
                        <td><?php echo htmlspecialchars($row['Localidad']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['Descripción_Síntomas'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars(substr($row['Diagnóstico_Preliminar'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars(substr($row['Tratamiento_Sugerido'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($row['DNIProfesional']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="no-records">No hay historiales clínicos registrados.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    .page-content {
        padding: 20px;
    }

    .header-section {
        margin-bottom: 30px;
    }

    .header-section h2 {
        margin: 0 0 10px 0;
        color: #2c3e50;
        font-size: 28px;
    }

    .header-section p {
        color: #7f8c8d;
        margin: 0;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-weight: 500;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
    }

    .alert-info {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
    }

    .form-section {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-section h3 {
        margin-top: 0;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }

    .required {
        color: #e74c3c;
    }

    .form-group input,
    .form-group textarea {
        padding: 10px;
        border: 1px solid #bdc3c7;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
    }

    .form-group textarea {
        resize: vertical;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .list-section {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .list-section h3 {
        margin-top: 0;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .records-count {
        color: #7f8c8d;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .data-table thead {
        background: #34495e;
        color: white;
    }

    .data-table thead th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
    }

    .data-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #ecf0f1;
    }

    .data-table tbody tr:hover {
        background: #f8f9fa;
    }

    .no-records {
        text-align: center;
        color: #95a5a6;
        padding: 30px;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .data-table {
            font-size: 12px;
        }

        .data-table thead th,
        .data-table tbody td {
            padding: 8px;
        }
    }
</style>
