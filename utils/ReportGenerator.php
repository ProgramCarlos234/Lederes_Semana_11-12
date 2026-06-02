<?php
class ReportGenerator {
    public static function generateDashboardPDF($db, $patient, $inventory, $emergency) {
        try {
            $totalPatients = $patient->getTotal();
            $totalMedicines = $inventory->getTotal();
            $totalEmergencies = $emergency->getTotal();
            $inventoryData = $inventory->getChartData();
            
            $html = self::getDashboardHTML($totalPatients, $totalMedicines, $totalEmergencies, $inventoryData);
            
            if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
                @include_once(__DIR__ . '/../vendor/autoload.php');
                if (class_exists('Dompdf\Dompdf')) {
                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="Reporte_Dashboard_' . date('Ymd_His') . '.pdf"');
                    echo $dompdf->output();
                    return true;
                }
            }
            
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="Reporte_Dashboard_' . date('Ymd_His') . '.html"');
            echo $html;
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    private static function getDashboardHTML($totalPatients, $totalMedicines, $totalEmergencies, $inventoryData) {
        $fecha = date('d/m/Y H:i:s');
        $html = "<!DOCTYPE html><html><head>";
        $html .= "<meta charset=\"UTF-8\">";
        $html .= "<title>Reporte Dashboard</title>";
        $html .= "<style>body{font-family:Arial;margin:30px;} .header{text-align:center;border-bottom:2px solid #2E8B57;padding:20px 0;} ";
        $html .= "table{width:100%;border-collapse:collapse;} th{background:#2E8B57;color:white;padding:10px;} ";
        $html .= "td{border-bottom:1px solid #ddd;padding:8px;} .stats{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin:20px 0;} ";
        $html .= ".stat-box{border:2px solid #2E8B57;padding:15px;text-align:center;} .stat-box h3{color:#2E8B57;font-size:32px;margin:0;} ";
        $html .= ".footer{text-align:center;color:#999;font-size:10px;margin-top:40px;border-top:1px solid #ddd;padding-top:20px;}</style>";
        $html .= "</head><body>";
        
        $html .= "<div class=\"header\">";
        $html .= "<h1>REPORTE TOTAL DASHBOARD</h1>";
        $html .= "<p>RuralMed - Sistema de Gestion de Salud Rural</p>";
        $html .= "<p>Generado: " . $fecha . "</p>";
        $html .= "</div>";
        
        $html .= "<div class=\"stats\">";
        $html .= "<div class=\"stat-box\"><h3>" . $totalPatients . "</h3><p>Pacientes</p></div>";
        $html .= "<div class=\"stat-box\"><h3>" . $totalMedicines . "</h3><p>Medicamentos</p></div>";
        $html .= "<div class=\"stat-box\"><h3>" . $totalEmergencies . "</h3><p>Emergencias</p></div>";
        $html .= "</div>";
        
        $html .= "<h2>Inventario de Medicamentos</h2>";
        $html .= "<table><tr><th>Medicamento</th><th>Cantidad</th></tr>";
        foreach ($inventoryData as $item) {
            $html .= "<tr><td>" . htmlspecialchars($item['NOMBRE_MEDICAMENTO']) . "</td><td>" . (int)$item['CANTIDAD_STOCK'] . "</td></tr>";
        }
        $html .= "</table>";
        
        $html .= "<div class=\"footer\"><p>Reporte generado por RuralMed 2025</p></div>";
        $html .= "</body></html>";
        return $html;
    }
}
?>
