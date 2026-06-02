<?php
// Vista: Generar Cita Médica
// Variables disponibles desde index.php: $appointment (modelo inicializado)
?>

<div class="card">
    <h2>Generar Cita Médica</h2>
    <form method="POST" action="?page=appointments" style="margin-top:12px;">
        <input type="hidden" name="action" value="create_appointment">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
                <label>DNI del Paciente</label>
                <input type="text" name="dni_paciente" required maxlength="8" placeholder="12345678" />
            </div>
            <div>
                <label>Tipo de Cita</label>
                <select name="tipo_cita" required>
                    <option value="virtual">Virtual</option>
                    <option value="presencial">Presencial</option>
                </select>
            </div>
            <div>
                <label>Nombre del Doctor</label>
                <input type="text" name="nombre_doctor" required placeholder="Juan" />
            </div>
            <div>
                <label>Apellido Paterno del Doctor</label>
                <input type="text" name="apellido_paterno_doctor" required placeholder="Perez" />
            </div>
            <div>
                <label>Apellido Materno del Doctor</label>
                <input type="text" name="apellido_materno_doctor" placeholder="Lopez" />
            </div>
            <div style="grid-column:1 / -1;">
                <label>Descripción (motivo de la cita)</label>
                <textarea name="descripcion_breve" rows="3" style="width:100%"></textarea>
            </div>
        </div>

        <div style="margin-top:12px;">
            <button type="submit" class="btn">Crear Cita</button>
        </div>
    </form>
</div>

<div class="card">
    <h2>Listado de Citas</h2>
    <?php
    try {
        $stmt = $appointment->read();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $items = [];
        error_log('[views/appointments] Error al obtener citas: ' . $e->getMessage());
    }
    ?>

    <?php if (empty($items)): ?>
        <p>No hay citas registradas.</p>
    <?php else: ?>
        <table style="width:100%;border-collapse:collapse;margin-top:12px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Doctor</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>ID_USUARIO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['IDCITAMEDICA']); ?></td>
                    <td><?php echo htmlspecialchars($row['Nombre_Doctor_Asignado'] . ' ' . $row['Apellido_Paterno_Doctor'] . ' ' . ($row['Apellido_Materno_Doctor'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars($row['Tipo_cita_Virtual_presen']); ?></td>
                    <td><?php echo htmlspecialchars($row['Descripcion_breve_cita']); ?></td>
                    <td><?php echo htmlspecialchars(isset($row['fecha_registro']) ? date('d/m/Y H:i', strtotime($row['fecha_registro'])) : (isset($row['Fecha_registro']) ? date('d/m/Y H:i', strtotime($row['Fecha_registro'])) : (isset($row['FECHA_REGISTRO']) ? date('d/m/Y H:i', strtotime($row['FECHA_REGISTRO'])) : ''))); ?></td>
                    <td><?php echo htmlspecialchars($row['Estad_cita']); ?></td>
                    <td><?php echo htmlspecialchars($row['ID_USUARIO']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
