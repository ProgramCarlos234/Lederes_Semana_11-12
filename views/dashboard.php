<!-- Dashboard con Gráficos y Estadísticas -->
<div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="margin: 0;">Bienvenido al Sistema RuralMed</h2>
        <p style="margin: 5px 0;">Hola, <?php echo $_SESSION['user_type'] == 'profesional' ? 
            htmlspecialchars($_SESSION['user']['Nombres_Profesional']) : 
            htmlspecialchars($_SESSION['user']['Nombres_Completos']); ?>!</p>
    </div>
    
    <!-- Botones de Descargar Reportes -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="?action=download_dashboard_pdf" style="background: #2E8B57; color: white; padding: 10px 16px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; transition: background 0.3s; border: none; cursor: pointer;">
            <i class="fas fa-file-pdf"></i> Reporte Total
        </a>
        <a href="?action=download_clinical_pdf" style="background: #1976d2; color: white; padding: 10px 16px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; transition: background 0.3s; border: none; cursor: pointer;">
            <i class="fas fa-file-pdf"></i> Historial (PDF)
        </a>
        <a href="?action=download_clinical_excel" style="background: #27ae60; color: white; padding: 10px 16px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; transition: background 0.3s; border: none; cursor: pointer;">
            <i class="fas fa-file-excel"></i> Historial (Excel)
        </a>
    </div>
</div>

<!-- Paleta de Estadísticas Principales (Grid Responsive) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 25px 0;">
    <div style="background: linear-gradient(135deg, #e8f5e8, #c8e6c9); padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="font-size: 36px; font-weight: bold; color: #2E8B57; margin-bottom: 10px;"><?php echo $patient->getTotal(); ?></div>
        <p style="color: #333; font-size: 14px; font-weight: 600;">Pacientes Registrados</p>
    </div>
    <div style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="font-size: 36px; font-weight: bold; color: #1976d2; margin-bottom: 10px;"><?php echo $inventory->getTotal(); ?></div>
        <p style="color: #333; font-size: 14px; font-weight: 600;">Medicamentos en Stock</p>
    </div>
    <div style="background: linear-gradient(135deg, #ffebee, #ffcdd2); padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="font-size: 36px; font-weight: bold; color: #d32f2f; margin-bottom: 10px;"><?php echo $emergency->getTotal(); ?></div>
        <p style="color: #333; font-size: 14px; font-weight: 600;">Emergencias Reportadas</p>
    </div>
</div>

<!-- Gráficos en Grid (2 columnas en desktop, 1 en mobile) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px; margin: 25px 0;">
    
    <!-- Gráfico de Barras: Inventario de Medicamentos -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 16px; font-weight: 600;">📊 Inventario por Medicamento</h3>
        <canvas id="inventoryChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Gráfico de Pastel: Emergencias por Gravedad -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 16px; font-weight: 600;">🥧 Emergencias por Nivel de Gravedad</h3>
        <canvas id="gravityChart" style="max-height: 300px;"></canvas>
    </div>

</div>

<!-- Gráfico de Rosquilla: Tipos de Emergencia (Full Width) -->
<div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 25px 0;">
    <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 16px; font-weight: 600;">🍩 Tipos de Emergencia Reportados</h3>
    <div style="display: flex; justify-content: center; align-items: center;">
        <div style="width: 100%; max-width: 500px;">
            <canvas id="typeChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 25px;">
    <h3 style="color: #2c3e50; margin-bottom: 15px;">⚡ Acciones Rápidas</h3>
    <div style="display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'profesional'): ?>
        <a href="?page=inventory" style="background: #1976d2; color: white; padding: 12px 18px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: background 0.3s;">
            <i class="fas fa-pills"></i> Ver Inventario
        </a>
        <a href="?page=emergencies" style="background: #d32f2f; color: white; padding: 12px 18px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: background 0.3s;">
            <i class="fas fa-ambulance"></i> Reportar Emergencia
        </a>
        <a href="?page=appointments" style="background: #f39c12; color: white; padding: 12px 18px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: background 0.3s;">
            <i class="fas fa-calendar-plus"></i> Generar Cita
        </a>
        <?php else: ?>
        <a href="?page=appointments" style="background: #f39c12; color: white; padding: 12px 18px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: background 0.3s;">
            <i class="fas fa-calendar-plus"></i> Generar Cita
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts para renderizar gráficos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para gráfico de inventario (Bar Chart)
    const inventoryData = <?php 
        $chartData = $inventory->getChartData();
        $names = array_column($chartData, 'NOMBRE_MEDICAMENTO');
        $quantities = array_column($chartData, 'CANTIDAD_STOCK');
        echo json_encode(['names' => $names, 'quantities' => $quantities]);
    ?>;

    if (inventoryData.names.length > 0) {
        const ctxInventory = document.getElementById('inventoryChart').getContext('2d');
        new Chart(ctxInventory, {
            type: 'bar',
            data: {
                labels: inventoryData.names,
                datasets: [{
                    label: 'Cantidad en Stock',
                    data: inventoryData.quantities,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#36A2EB'
                    ],
                    borderColor: '#2c3e50',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    } else {
        document.getElementById('inventoryChart').parentElement.innerHTML += '<p style="color:#999;text-align:center;">Sin datos de inventario</p>';
    }

    // Datos para gráfico de emergencias por gravedad (Pie Chart)
    const gravityData = <?php 
        $grav = $emergency->getEmergenciesByGravedad();
        $gravedades = array_column($grav, 'GRAVEDAD');
        $cantidades_grav = array_column($grav, 'cantidad');
        echo json_encode(['gravedades' => $gravedades, 'cantidades' => $cantidades_grav]);
    ?>;

    if (gravityData.gravedades.length > 0) {
        const ctxGravity = document.getElementById('gravityChart').getContext('2d');
        new Chart(ctxGravity, {
            type: 'pie',
            data: {
                labels: gravityData.gravedades,
                datasets: [{
                    data: gravityData.cantidades,
                    backgroundColor: [
                        '#FF6384', // Rojo (Alta)
                        '#FFCE56', // Amarillo (Media)
                        '#36A2EB'  // Azul (Baja)
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    } else {
        document.getElementById('gravityChart').parentElement.innerHTML += '<p style="color:#999;text-align:center;">Sin datos de emergencias</p>';
    }

    // Datos para gráfico de tipos de emergencia (Doughnut Chart)
    const typeData = <?php 
        $types = $emergency->getEmergenciesByType();
        $type_names = array_column($types, 'Tipo_de_emergencia');
        $type_counts = array_column($types, 'cantidad');
        echo json_encode(['tipos' => $type_names, 'cantidades' => $type_counts]);
    ?>;

    if (typeData.tipos.length > 0) {
        const ctxType = document.getElementById('typeChart').getContext('2d');
        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: typeData.tipos,
                datasets: [{
                    data: typeData.cantidades,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#C9CBCF'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    } else {
        document.getElementById('typeChart').parentElement.innerHTML += '<p style="color:#999;text-align:center;">Sin datos de tipos de emergencia</p>';
    }
});
</script>