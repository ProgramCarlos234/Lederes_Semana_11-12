<div class="patients-page">
    <div class="page-header">
        <h2>Gestión de Pacientes</h2>
        <p>Registro y administración de pacientes del sistema</p>
    </div>

    <!-- Formulario de Registro -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="?action=register_patient">
                <div class="form-row">
                    <div class="form-group">
                        <label>DNI *</label>
                        <input type="text" name="dni" maxlength="8" pattern="[0-9]{8}" required 
                               placeholder="Ingrese DNI (8 dígitos)">
                    </div>
                    <div class="form-group">
                        <label>Edad *</label>
                        <input type="number" name="edad" min="0" max="120" required 
                               placeholder="Edad del paciente">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Apellido Paterno *</label>
                        <input type="text" name="apellido_paterno" required 
                               placeholder="Apellido paterno">
                    </div>
                    <div class="form-group">
                        <label>Apellido Materno *</label>
                        <input type="text" name="apellido_materno" required 
                               placeholder="Apellido materno">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nombres Completos *</label>
                    <input type="text" name="nombres_completos" required 
                           placeholder="Nombres completos del paciente">
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion" rows="2" placeholder="Dirección completa"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono *</label>
                        <input type="tel" name="telefono" required 
                               placeholder="Número de teléfono">
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico *</label>
                        <input type="email" name="correo" required 
                               placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Paciente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Pacientes -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Lista de Pacientes Registrados</h3>
            <div class="card-actions">
                <span class="badge">Total: <?php echo $patient->getTotal(); ?> pacientes</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Edad</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $patient->read();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['DNI_Paciente']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['Nombres_Completos']); ?></td>
                            <td><?php echo htmlspecialchars($row['Edad_Paciente']); ?> años</td>
                            <td><?php echo htmlspecialchars($row['Num_Telefono_Paciente']); ?></td>
                            <td><?php echo htmlspecialchars($row['Correo_Electronico_Paciente']); ?></td>
                            <td><?php echo htmlspecialchars($row['Dirección_Paciente']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.patients-page .page-header {
    margin-bottom: 30px;
}

.patients-page .page-header h2 {
    color: var(--dark);
    margin-bottom: 5px;
}

.patients-page .page-header p {
    color: var(--gray);
}

.card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    margin-bottom: 30px;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    color: var(--dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 20px;
}

.badge {
    background: var(--primary);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--dark);
}

.data-table tr:hover {
    background: #f8f9fa;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-sm {
    padding: 6px 10px;
    font-size: 12px;
}

.btn-info { background: #17a2b8; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-danger { background: #dc3545; }
</style>