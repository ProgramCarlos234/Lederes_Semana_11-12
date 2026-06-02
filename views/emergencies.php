<div class="emergencies-page">
    <div class="page-header">
        <h2>Gestión de Emergencias</h2>
        <p>Registro y seguimiento de emergencias médicas</p>
    </div>

    <!-- Estadísticas de Emergencias -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e74c3c;">
                <i class="fas fa-ambulance"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $emergency->getTotal(); ?></h3>
                <p>Total Emergencias</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f39c12;">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $emergency->getByGravedad('Alta'); ?></h3>
                <p>Alta Gravedad</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #3498db;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $emergency->getByGravedad('Media'); ?></h3>
                <p>Gravedad Media</p>
            </div>
        </div>
    </div>

    <!-- Formulario de Reporte de Emergencia -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-plus-circle"></i> Reportar Nueva Emergencia</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="?page=emergencies">
                <input type="hidden" name="action" value="report_emergency">
                <div class="form-row">
                    <div class="form-group">
                        <label>DNI del Paciente *</label>
                        <input type="text" name="dni_paciente" placeholder="Ingrese DNI del paciente (8 dígitos)" required pattern="[0-9]{6,8}">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Emergencia *</label>
                        <select name="tipo_emergencia" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="Accidente">Accidente</option>
                            <option value="Enfermedad Aguda">Enfermedad Aguda</option>
                            <option value="Complicación Post-Operatoria">Complicación Post-Operatoria</option>
                            <option value="Parto">Parto</option>
                            <option value="Intoxicación">Intoxicación</option>
                            <option value="Trauma">Trauma</option>
                            <option value="Paro Cardíaco">Paro Cardíaco</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nivel de Gravedad *</label>
                        <select name="gravedad" required>
                            <option value="">Seleccionar gravedad</option>
                            <option value="Baja">Baja</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                            <option value="Crítica">Crítica</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción Detallada *</label>
                    <textarea name="descripcion" rows="4" required 
                              placeholder="Describa la emergencia en detalle, incluyendo síntomas, ubicación y cualquier información relevante..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-bell"></i> Reportar Emergencia
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Emergencias -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Emergencias Registradas</h3>
            <div class="card-actions">
                <span class="badge">Total: <?php echo $emergency->getTotal(); ?> emergencias</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente (DNI)</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Gravedad</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $emergency->read();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            $gravedad = $row['GRAVEDAD'];
                            $estadoClass = '';
                            switch($gravedad) {
                                case 'Crítica': $estadoClass = 'critica'; break;
                                case 'Alta': $estadoClass = 'alta'; break;
                                case 'Media': $estadoClass = 'media'; break;
                                default: $estadoClass = 'baja';
                            }
                        ?>
                        <tr>
                            <td><strong>#<?php echo $row['ID_EMERGENCIA']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['DNI_Paciente'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['Tipo_de_emergencia']); ?></td>
                            <td><?php echo htmlspecialchars($row['Descripción_emerge']); ?></td>
                            <td>
                                <span class="gravedad-badge gravedad-<?php echo $estadoClass; ?>">
                                    <?php echo $gravedad; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'] ?? 'now')); ?></td>
                            <td>
                                <span class="status-badge status-activa">
                                    Activa
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" title="Atender">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" title="Reasignar">
                                        <i class="fas fa-exchange-alt"></i>
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
.emergencies-page .gravedad-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.gravedad-critica { background: #f8d7da; color: #721c24; }
.gravedad-alta { background: #ffeaea; color: #e74c3c; }
.gravedad-media { background: #fff3cd; color: #856404; }
.gravedad-baja { background: #e8f5e8; color: #27ae60; }

.status-activa { background: #ffeaea; color: #e74c3c; }
.status-atendida { background: #e8f5e8; color: #27ae60; }
</style>