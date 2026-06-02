<?php
// Vista: Formulario y listado de Inventario
// Variables disponibles desde index.php: $inventory (modelo inicializado)
?>

<div class="card">
    <h2>Registrar Medicamento</h2>
    <form method="POST" style="margin-top:15px;">
        <input type="hidden" name="action" value="add_inventory">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
                <label>Nombre del Medicamento</label>
                <input type="text" name="nombre_medicamento" required placeholder="Paracetamol" />
            </div>
            <div>
                <label>Fecha de Caducidad</label>
                <input type="date" name="fecha_caducidad" />
            </div>
            <div>
                <label>Cantidad en Stock</label>
                <input type="number" name="cantidad_stock" min="0" value="0" />
            </div>
            <div>
                <label>Unidades</label>
                <input type="text" name="unidades" placeholder="mg, ml, cajas" />
            </div>
            <div style="grid-column:1 / -1;">
                <label>Descripción breve</label>
                <textarea name="descripcion_breve" rows="3" style="width:100%"></textarea>
            </div>
        </div>

        <div style="margin-top:12px;">
            <button type="submit" class="btn">Agregar al Inventario</button>
        </div>
    </form>
</div>

<div class="card">
    <h2>Listado de Inventario</h2>
    <?php
    try {
        $stmt = $inventory->read();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $items = [];
        error_log("[views/inventory] Error al obtener inventario: " . $e->getMessage());
    }
    ?>

    <?php if (empty($items)): ?>
        <p>No hay medicamentos registrados.</p>
    <?php else: ?>
        <table style="width:100%;border-collapse:collapse;margin-top:12px;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">ID</th>
                    <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Nombre</th>
                    <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Caducidad</th>
                    <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Cantidad</th>
                    <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Unidades</th>
                    <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Profesional</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $row): ?>
                <tr>
                    <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?php echo htmlspecialchars($row['IDmedicamento']); ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?php echo htmlspecialchars($row['NOMBRE_MEDICAMENTO']); ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?php echo htmlspecialchars($row['FECHA_CADUCIDAD']); ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?php echo htmlspecialchars($row['CANTIDAD_STOCK']); ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?php echo htmlspecialchars($row['UNIDADES']); ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?php echo htmlspecialchars($row['DNIProfesional']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>