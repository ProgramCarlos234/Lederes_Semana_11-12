<?php
class ClinicalReportGenerator {
    
    public static function generateClinicalPDF($db, $clinicalRecord) {
        try {
            $records = $clinicalRecord->read();
            $data = $records->fetchAll(PDO::FETCH_ASSOC);
            
            $html = self::getClinicalHTML($data);
            
            if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
                @include_once(__DIR__ . '/../vendor/autoload.php');
                if (class_exists('Dompdf\Dompdf')) {
                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="Reporte_Historiales_' . date('Ymd_His') . '.pdf"');
                    echo $dompdf->output();
                    return true;
                }
            }
            
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="Reporte_Historiales_' . date('Ymd_His') . '.html"');
            echo $html;
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public static function generateClinicalExcel($db, $clinicalRecord) {
        try {
            $records = $clinicalRecord->read();
            $data = $records->fetchAll(PDO::FETCH_ASSOC);
            
            if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
                return self::generateCSV($data);
            }
            
            @include_once(__DIR__ . '/../vendor/autoload.php');
            
            if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                $headers = ['ID', 'DNI Paciente', 'Localidad', 'Fecha', 'Sintomas', 'Diagnostico', 'Tratamiento'];
                $sheet->fromArray($headers, NULL, 'A1');
                
                $row = 2;
                foreach ($data as $record) {
                    $sheet->setCellValue('A' . $row, $record['ID_clinica'] ?? '');
                    $sheet->setCellValue('B' . $row, $record['DNI_Paciente'] ?? '');
                    $sheet->setCellValue('C' . $row, $record['Localidad'] ?? '');
                    $sheet->setCellValue('D' . $row, $record['Fecha_Generada_Clinica'] ?? '');
                    $sheet->setCellValue('E' . $row, $record['Descripcion_Sintomas'] ?? '');
                    $sheet->setCellValue('F' . $row, $record['Diagnostico_Preliminar'] ?? '');
                    $sheet->setCellValue('G' . $row, $record['Tratamiento_Sugerido'] ?? '');
                    $row++;
                }
                
                $filename = 'Reporte_Historiales_' . date('Ymd_His') . '.xlsx';
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                return true;
            }
            
            return self::generateCSV($data);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    private static function generateCSV($data) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Reporte_Historiales_' . date('Ymd_His') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        fputcsv($output, ['ID', 'DNI Paciente', 'Localidad', 'Fecha', 'Sintomas', 'Diagnostico', 'Tratamiento'], ';');
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['ID_clinica'] ?? '',
                $row['DNI_Paciente'] ?? '',
                $row['Localidad'] ?? '',
                $row['Fecha_Generada_Clinica'] ?? '',
                $row['Descripcion_Sintomas'] ?? '',
                $row['Diagnostico_Preliminar'] ?? '',
                $row['Tratamiento_Sugerido'] ?? ''
            ], ';');
        }
        
        fclose($output);
        return true;
    }
    
    private static function getClinicalHTML($data) {
        $fecha = date('d/m/Y H:i:s');
        $totalRecords = count($data);
        
        $html = "<!DOCTYPE html><html><head>";
        $html .= "<meta charset=\"UTF-8\">";
        $html .= "<title>Reporte Historiales Clinicos</title>";
        $html .= "<style>body{font-family:Arial;margin:30px;} .header{text-align:center;border-bottom:2px solid #2E8B57;padding:20px 0;} ";
        $html .= "table{width:100%;border-collapse:collapse;margin:20px 0;} th{background:#2E8B57;color:white;padding:10px;} ";
        $html .= "td{border-bottom:1px solid #ddd;padding:8px;} .footer{text-align:center;color:#999;font-size:10px;margin-top:40px;}</style>";
        $html .= "</head><body>";
        
        $html .= "<div class=\"header\">";
        $html .= "<h1>REPORTE DE HISTORIAL CLINICO ACTUAL</h1>";
        $html .= "<p>RuralMed - Sistema de Gestion de Salud Rural</p>";
        $html .= "<p>Generado: " . $fecha . "</p>";
        $html .= "<p>Total de Registros: " . $totalRecords . "</p>";
        $html .= "</div>";
        
        $html .= "<table>";
        $html .= "<tr><th>ID</th><th>DNI Paciente</th><th>Localidad</th><th>Fecha</th><th>Sintomas</th><th>Diagnostico</th><th>Tratamiento</th></tr>";
        
        foreach ($data as $record) {
            $html .= "<tr>";
            $html .= "<td>" . htmlspecialchars($record['ID_clinica'] ?? '') . "</td>";
            $html .= "<td>" . htmlspecialchars($record['DNI_Paciente'] ?? '') . "</td>";
            $html .= "<td>" . htmlspecialchars($record['Localidad'] ?? '') . "</td>";
            $html .= "<td>" . htmlspecialchars($record['Fecha_Generada_Clinica'] ?? '') . "</td>";
            $html .= "<td>" . htmlspecialchars(substr($record['Descripcion_Sintomas'] ?? '', 0, 30)) . "</td>";
            $html .= "<td>" . htmlspecialchars(substr($record['Diagnostico_Preliminar'] ?? '', 0, 30)) . "</td>";
            $html .= "<td>" . htmlspecialchars(substr($record['Tratamiento_Sugerido'] ?? '', 0, 30)) . "</td>";
            $html .= "</tr>";
        }
        
        $html .= "</table>";
        $html .= "<div class=\"footer\"><p>Reporte generado por RuralMed 2025</p></div>";
        $html .= "</body></html>";
        
        return $html;
    }
}
?>
