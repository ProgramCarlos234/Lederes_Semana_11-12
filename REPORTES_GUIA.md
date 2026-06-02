# 📋 Guía de Uso: Sistema de Reportes RuralMed

## Descripción General
El Sistema RuralMed ahora incluye funcionalidad de generación de reportes en **PDF** y **Excel** directamente desde el Dashboard.

---

## 📊 Reportes Disponibles

### 1. **Reporte Total Dashboard (PDF)**
**Botón:** "Reporte Total" (color verde)

**Contenido:**
- Estadísticas principales (Pacientes, Medicamentos, Emergencias)
- Listado completo de medicamentos en inventario con cantidad
- Emergencias agrupadas por nivel de gravedad (Alta, Media, Baja)
- Tipos de emergencia reportados con cantidad de pacientes

**Formato:** PDF con tablas formateadas
**Descarga:** Automática con nombre: `Reporte_Dashboard_YYYYMMDD_HHMMSS.pdf`

---

### 2. **Reporte de Historial Clínico (PDF)**
**Botón:** "Historial (PDF)" (color azul)

**Contenido:**
- Título: "REPORTE DE HISTORIAL CLÍNICO ACTUAL"
- Total de registros
- Tabla con columnas:
  - ID Historial
  - DNI Paciente
  - Localidad
  - Fecha
  - Síntomas (primeros 30 caracteres)
  - Diagnóstico Preliminar (primeros 30 caracteres)
  - Tratamiento Sugerido (primeros 30 caracteres)

**Formato:** PDF con tabla detallada y estilos profesionales
**Descarga:** Automática con nombre: `Reporte_Historiales_Clinicos_YYYYMMDD_HHMMSS.pdf`

---

### 3. **Reporte de Historial Clínico (Excel)**
**Botón:** "Historial (Excel)" (color verde oscuro)

**Contenido:** Mismo contenido que PDF
- Encabezados con fondo verde y texto blanco
- Columnas ajustadas automáticamente
- Formato .xlsx (Excel moderno)

**Formato:** Excel (.xlsx)
**Descarga:** Automática con nombre: `Reporte_Historiales_Clinicos_YYYYMMDD_HHMMSS.xlsx`

**Nota:** Si no está disponible la librería PhpSpreadsheet, genera CSV como fallback.

---

## 🚀 Cómo Usar

### Pasos:
1. Inicia sesión en RuralMed (profesional o paciente)
2. Navega al **Dashboard** (página por defecto después de login)
3. En la parte superior, verás 3 botones de reportes
4. Haz clic en el botón deseado
5. El archivo se descargará automáticamente a tu carpeta de descargas

### Navegadores Soportados:
- Chrome/Chromium
- Firefox
- Safari
- Edge

---

## 📱 Características Técnicas

### Autenticación:
- Solo usuarios logueados pueden descargar reportes
- Se valida sesión activa antes de generar archivo

### Formato de Archivos:
| Reporte | Formato | Tamaño Aprox. | Formato Nomtbre Archivo |
|---------|---------|---------------|------------------------|
| Dashboard | PDF (HTML) | 50-200 KB | `Reporte_Dashboard_YYYYMMDD_HHMMSS.pdf` |
| Historial | PDF (HTML) | 100-500 KB | `Reporte_Historiales_Clinicos_YYYYMMDD_HHMMSS.pdf` |
| Historial | Excel/CSV | 50-300 KB | `Reporte_Historiales_Clinicos_YYYYMMDD_HHMMSS.xlsx` |

### Codificación:
- **UTF-8** para caracteres especiales (acentos, ñ, etc.)
- Compatible con sistemas latinoamericanos

---

## 🔐 Seguridad

- Los reportes se generan bajo demanda (no se almacenan en servidor)
- Solo usuarios autenticados pueden acceder
- Los datos mostrados se obtienen directamente de la BD
- Se registran intentos en logs del servidor

---

## 🛠️ Instalación de Librerías (Opcional)

Para mejorar la generación de PDFs con gráficos, puedes instalar:

### TCPDF (para gráficos en PDF):
```bash
composer require tecnickcom/tcpdf
```

### PhpSpreadsheet (para mejor soporte Excel):
```bash
composer require phpoffice/phpspreadsheet
```

**Nota:** El sistema funciona sin estas librerías, generando PDFs/Excel válidos pero sin gráficos integrados.

---

## 📝 Notas Importantes

1. **Datos en Tiempo Real:** Los reportes se generan con datos actuales de la BD
2. **Permisos:** Todos los roles (profesional/paciente) pueden descargar reportes
3. **Historial:** Los reportes incluyen TODOS los historiales (profesionales ven todos, pacientes ven los suyos si se implementa filtrado)
4. **Límites:** Sin límite en cantidad de registros (documentos grandes pueden tomar más tiempo)
5. **Errores:** Si hay error, se muestra mensaje y se registra en logs del servidor

---

## 🐛 Solución de Problemas

### El PDF/Excel no se descarga:
- Verifica que hayas iniciado sesión
- Comprueba que tu navegador permita descargas
- Revisa los logs: `C:\xampp\apache\logs\error.log`

### El PDF se abre como texto en lugar de descargar:
- Algunos navegadores pueden visualizar PDFs HTML como texto
- Usa "Guardar como" o "Download" si no aparece el botón de descarga

### Caracteres especiales ven mal (acentos, ñ):
- Verifica que tu navegador use UTF-8
- Los archivos se generan correctamente en UTF-8

---

## 📞 Contacto / Soporte

Para reportar problemas o solicitar mejoras en los reportes, contacta al equipo de desarrollo de RuralMed.

---

**Última actualización:** Noviembre 2025
**Versión:** 1.0 - Sistema de Reportes RuralMed
