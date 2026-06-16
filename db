-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2026 a las 01:33:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ruralmed`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_usuario`
--

CREATE TABLE `configuracion_usuario` (
  `IDConfiguración` varchar(15) NOT NULL,
  `AÑADIR_Correo_nuevo` varchar(100) DEFAULT NULL,
  `Numero_nuevo_teléfono` varchar(9) DEFAULT NULL,
  `Cambiar_nombre_usuario` varchar(100) DEFAULT NULL,
  `Añadir_Foto_Perfil` varchar(255) DEFAULT NULL,
  `ID_CONTRASEÑA` varchar(30) DEFAULT NULL,
  `ID_Usuario` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contraseñas_cambiadas`
--

CREATE TABLE `contraseñas_cambiadas` (
  `ID_CONTRASEÑA` varchar(30) NOT NULL,
  `CONTRASEÑA_ACTUAL` varchar(30) NOT NULL,
  `CONTRASEÑA_NUEVA` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contraseñas_cambiadas`
--

INSERT INTO `contraseñas_cambiadas` (`ID_CONTRASEÑA`, `CONTRASEÑA_ACTUAL`, `CONTRASEÑA_NUEVA`) VALUES
('chg_6913d8499947f2.03295246', '$2y$10$xyGpsAws2G6jtruirdddBO4', '$2y$10$Z54vbtMpHwQtx4sdTDjq2.g');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha_registro_clínico`
--

CREATE TABLE `ficha_registro_clínico` (
  `IDHistorialClinico` int(11) NOT NULL,
  `DNI_Paciente` char(8) NOT NULL,
  `Fecha_Generada_Clinica` date NOT NULL DEFAULT curdate(),
  `Localidad` varchar(100) NOT NULL,
  `Descripción_Síntomas` varchar(255) NOT NULL,
  `Diagnóstico_Preliminar` varchar(255) NOT NULL,
  `Tratamiento_Sugerido` varchar(255) NOT NULL,
  `DNIProfesional` char(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ficha_registro_clínico`
--

INSERT INTO `ficha_registro_clínico` (`IDHistorialClinico`, `DNI_Paciente`, `Fecha_Generada_Clinica`, `Localidad`, `Descripción_Síntomas`, `Diagnóstico_Preliminar`, `Tratamiento_Sugerido`, `DNIProfesional`) VALUES
(1, '74023635', '2025-11-11', 'Cusco Hospital Regional', 'Tos aguda, Dolor estomacal', 'Cáncer de páncreas', 'Comer bien , reposo absoluto, analgesicos como paracetamol 500g 2x3dias', '72200169'),
(2, '76543210', '2024-01-15', 'Centro Poblado Santa Rosa', 'Dolor abdominal, nauseas', 'Gastritis', 'Dieta blanda, omeprazol', '12345678'),
(3, '76543211', '2024-01-16', 'Comunidad San Juan', 'Fiebre 38.5°, tos', 'Infección respiratoria', 'Antibióticos, reposo', '12345679'),
(4, '76543212', '2024-01-17', 'Caserío Los Andes', 'Dolor articular, inflamación', 'Artritis', 'Antiinflamatorios', '12345680'),
(5, '76543213', '2024-01-18', 'Centro Poblado Bella Vista', 'Presión arterial elevada', 'Hipertensión', 'Medicación antihipertensiva', '12345681'),
(6, '76543214', '2024-01-19', 'Comunidad El Porvenir', 'Palpitaciones, mareos', 'Arritmia cardíaca', 'Estudio ECG, medicación', '12345682'),
(7, '76543215', '2024-01-20', 'Caserío La Esperanza', 'Dolor de cabeza crónico', 'Migraña', 'Analgésicos específicos', '12345683'),
(8, '76543216', '2024-01-21', 'Centro Poblado Nuevo Amanecer', 'Esguince de tobillo', 'Trauma articular', 'Inmovilización, hielo', '12345684'),
(9, '76543217', '2024-01-22', 'Comunidad San Pedro', 'Irregularidad menstrual', 'Desequilibrio hormonal', 'Estudios hormonales', '12345685'),
(10, '76543218', '2024-01-23', 'Caserío La Unión', 'Ansiedad, insomnio', 'Trastorno de ansiedad', 'Terapia, relajación', '12345686'),
(11, '76543219', '2024-01-24', 'Centro Poblado El Progreso', 'Dolor lumbar', 'Lumbalgia', 'Fisioterapia, ejercicios', '12345687'),
(12, '76543217', '2025-11-24', 'Seguro Cusco', 'Tos, Dolores en el pecho', 'Bronconeumonía Severa', 'Ibuprofeno, Paracetamol, 500mg 3 veces al día/ 1 semana', '72200169');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generar_cita`
--

CREATE TABLE `generar_cita` (
  `IDCITAMEDICA` int(11) NOT NULL,
  `Nombre_Doctor_Asignado` varchar(100) NOT NULL,
  `Apellido_Paterno_Doctor` varchar(50) NOT NULL,
  `Apellido_Materno_Doctor` varchar(50) NOT NULL,
  `Tipo_cita_Virtual_presen` varchar(20) NOT NULL,
  `Descripcion_breve_cita` varchar(200) NOT NULL,
  `Estad_cita` varchar(20) NOT NULL DEFAULT 'pendiente',
  `ID_USUARIO` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `generar_cita`
--

INSERT INTO `generar_cita` (`IDCITAMEDICA`, `Nombre_Doctor_Asignado`, `Apellido_Paterno_Doctor`, `Apellido_Materno_Doctor`, `Tipo_cita_Virtual_presen`, `Descripcion_breve_cita`, `Estad_cita`, `ID_USUARIO`) VALUES
(1, 'Paul', 'Acurio', 'Jara', 'presencial', 'Tratamiento no funciona', 'pendiente', '74023635'),
(2, 'Ricardo Andres', 'Villanueva', 'Castro', 'presencial', 'Control rutinario de presión arterial', 'pendiente', 'USU001'),
(3, 'Elena Margarita', 'Salazar', 'Mendez', 'virtual', 'Consulta por fiebre y malestar general', 'confirmada', 'USU002'),
(4, 'Jorge Luis', 'Ramirez', 'Toledo', 'presencial', 'Dolor en articulaciones', 'atendida', 'USU003'),
(5, 'Patricia Isabel', 'Morales', 'Santos', 'virtual', 'Control de niño sano', 'pendiente', 'USU004'),
(6, 'Fernando Jose', 'Silva', 'Reyes', 'presencial', 'Evaluación cardiaca', 'cancelada', 'USU005'),
(7, 'Gabriela Maria', 'Ortega', 'Paz', 'virtual', 'Dolor de cabeza persistente', 'pendiente', 'USU006'),
(8, 'Raul Antonio', 'Delgado', 'Quispe', 'presencial', 'Revisión por caída', 'confirmada', 'USU007'),
(9, 'Silvia Carolina', 'Rios', 'Campos', 'virtual', 'Consulta ginecológica', 'pendiente', 'USU008'),
(10, 'Oscar Eduardo', 'Mendoza', 'Vega', 'presencial', 'Sesión de terapia psicológica', 'atendida', 'USU009'),
(11, 'Monica Lucia', 'Lozano', 'Herrada', 'virtual', 'Rehabilitación de lesión', 'pendiente', 'USU010');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrar_emergencia`
--

CREATE TABLE `registrar_emergencia` (
  `ID_EMERGENCIA` int(11) NOT NULL,
  `DNI_Paciente` char(8) NOT NULL,
  `Tipo_de_emergencia` varchar(50) NOT NULL,
  `Descripción_emerge` varchar(255) NOT NULL,
  `GRAVEDAD` varchar(20) NOT NULL,
  `DNIProfesional` char(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registrar_emergencia`
--

INSERT INTO `registrar_emergencia` (`ID_EMERGENCIA`, `DNI_Paciente`, `Tipo_de_emergencia`, `Descripción_emerge`, `GRAVEDAD`, `DNIProfesional`) VALUES
(1, '74023635', 'Accidente', 'Se cayo de la moto', 'Media', '72200169'),
(2, '76543220', 'Accidente', 'Caída de altura, fractura expuesta', 'alta', '12345678'),
(3, '76543221', 'Respiratoria', 'Dificultad respiratoria severa', 'critica', '12345679'),
(4, '76543222', 'Cardiovascular', 'Dolor torácico intenso', 'alta', '12345682'),
(5, '76543223', 'Trauma', 'Accidente vehicular, múltiples heridas', 'media', '12345684'),
(6, '76543224', 'Neurológica', 'Convulsiones prolongadas', 'critica', '12345683');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrar_inventario`
--

CREATE TABLE `registrar_inventario` (
  `IDmedicamento` int(11) NOT NULL,
  `NOMBRE_MEDICAMENTO` varchar(100) NOT NULL,
  `FECHA_CADUCIDAD` date NOT NULL,
  `DESCRIPCION_BREVE` varchar(200) NOT NULL,
  `CANTIDAD_STOCK` int(11) NOT NULL,
  `UNIDADES` varchar(20) NOT NULL,
  `FECHA_ACTUALIZACIÓN_STOCK` date NOT NULL DEFAULT curdate(),
  `DNIProfesional` char(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registrar_inventario`
--

INSERT INTO `registrar_inventario` (`IDmedicamento`, `NOMBRE_MEDICAMENTO`, `FECHA_CADUCIDAD`, `DESCRIPCION_BREVE`, `CANTIDAD_STOCK`, `UNIDADES`, `FECHA_ACTUALIZACIÓN_STOCK`, `DNIProfesional`) VALUES
(1, 'Paracetamol 500g', '2026-06-04', 'para tomar analgésico', 10, 'Tabletas', '2025-11-11', '72200169'),
(2, 'Paracetamol 500mg', '2025-06-30', 'Analgésico y antipirético', 150, 'cajas', '2024-01-15', '12345678'),
(3, 'Amoxicilina 500mg', '2024-12-31', 'Antibiótico de amplio espectro', 80, 'cajas', '2024-01-15', '12345679'),
(4, 'Ibuprofeno 400mg', '2025-03-31', 'Antiinflamatorio no esteroideo', 120, 'cajas', '2024-01-16', '12345680'),
(5, 'Omeprazol 20mg', '2025-01-31', 'Protector gástrico', 95, 'cajas', '2024-01-16', '12345681'),
(6, 'Losartán 50mg', '2024-11-30', 'Antihipertensivo', 60, 'cajas', '2024-01-17', '12345682'),
(7, 'Sumatriptán 50mg', '2025-02-28', 'Para migraña aguda', 45, 'cajas', '2024-01-17', '12345683'),
(8, 'Ketorolaco 10mg', '2024-10-31', 'Analgésico potente', 75, 'cajas', '2024-01-18', '12345684'),
(9, 'Metformina 850mg', '2025-04-30', 'Para diabetes tipo 2', 110, 'cajas', '2024-01-18', '12345685'),
(10, 'Sertralina 50mg', '2025-05-31', 'Antidepresivo', 65, 'cajas', '2024-01-19', '12345686'),
(11, 'Diclofenaco 50mg', '2024-09-30', 'Antiinflamatorio muscular', 85, 'cajas', '2024-01-19', '12345687');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_profesional_salud`
--

CREATE TABLE `registro_profesional_salud` (
  `DNI_Profesional` char(8) NOT NULL,
  `Apellido_Paterno_Profesional` varchar(50) NOT NULL,
  `Apellido_Materno_Profesional` varchar(50) NOT NULL,
  `Nombres_Profesional` varchar(100) NOT NULL,
  `Edad_Profesional` int(11) NOT NULL,
  `Dirección_Profesional` varchar(150) NOT NULL,
  `Num_Telefono_Profesional` varchar(15) NOT NULL,
  `Correo_Profesional` varchar(100) NOT NULL,
  `Cargo_Profesional` varchar(80) NOT NULL,
  `Contraseña` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_profesional_salud`
--

INSERT INTO `registro_profesional_salud` (`DNI_Profesional`, `Apellido_Paterno_Profesional`, `Apellido_Materno_Profesional`, `Nombres_Profesional`, `Edad_Profesional`, `Dirección_Profesional`, `Num_Telefono_Profesional`, `Correo_Profesional`, `Cargo_Profesional`, `Contraseña`) VALUES
('12345678', 'Villanueva', 'Castro', 'Ricardo Andres', 42, 'Av. Medicina 123', '987654336', 'ricardo.villanueva@ruralmed.com', 'Medico General', '$2y$10$hpiEjdGoglsrRzCdOTX8.uMAoO/QcC52rGxPrc07WH0au1am6gK2O'),
('12345679', 'Salazar', 'Mendez', 'Elena Margarita', 38, 'Calle Salud 456', '987654337', 'elena.salazar@ruralmed.com', 'Enfermera Jefe', 'elena1234'),
('12345680', 'Ramirez', 'Toledo', 'Jorge Luis', 51, 'Jr. Hospital 789', '987654338', 'jorge.ramirez@ruralmed.com', 'Medico Cirujano', 'jorge1234'),
('12345681', 'Morales', 'Santos', 'Patricia Isabel', 36, 'Av. Clinica 234', '987654339', 'patricia.morales@ruralmed.com', 'Pediatra', 'patricia1234'),
('12345682', 'Silva', 'Reyes', 'Fernando Jose', 45, 'Calle Especialidades 567', '987654340', 'fernando.silva@ruralmed.com', 'Cardiologo', 'fernando1234'),
('12345683', 'Ortega', 'Paz', 'Gabriela Maria', 33, 'Jr. Emergencia 890', '987654341', 'gabriela.ortega@ruralmed.com', 'Medico General', 'gabriela1234'),
('12345684', 'Delgado', 'Quispe', 'Raul Antonio', 48, 'Av. Consultorios 123', '987654342', 'raul.delgado@ruralmed.com', 'Traumatologo', 'raul1234'),
('12345685', 'Rios', 'Campos', 'Silvia Carolina', 40, 'Calle Diagnostico 456', '987654343', 'silvia.rios@ruralmed.com', 'Ginecologa', 'silvia1234'),
('12345686', 'Mendoza', 'Vega', 'Oscar Eduardo', 44, 'Jr. Terapia 789', '987654344', 'oscar.mendoza@ruralmed.com', 'Psicologo', 'oscar1234'),
('12345687', 'Lozano', 'Herrada', 'Monica Lucia', 37, 'Av. Rehabilitacion 234', '987654345', 'monica.lozano@ruralmed.com', 'Fisioterapeuta', 'monica1234'),
('72200169', 'Acurio', 'Jara', 'Paul Gonzalo', 23, 'Picchu San Isidro Pueblo joven E-7', '961059476', 'acuariojaragonzalo@gmail.com', 'Medico', '$2y$10$Z54vbtMpHwQtx4sdTDjq2.g67a27y8MtgoQfIO5hDTOmMj/iBRTQ6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_usuario_paciente`
--

CREATE TABLE `registro_usuario_paciente` (
  `DNI_Paciente` char(8) NOT NULL,
  `Apellido_Paterno` varchar(50) NOT NULL,
  `Apellido_Materno` varchar(50) NOT NULL,
  `Nombres_Completos` varchar(100) NOT NULL,
  `Edad_Paciente` int(11) NOT NULL,
  `Dirección_Paciente` varchar(150) DEFAULT NULL,
  `Num_Telefono_Paciente` varchar(9) NOT NULL,
  `Correo_Electronico_Paciente` varchar(100) NOT NULL,
  `Contraseña` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_usuario_paciente`
--

INSERT INTO `registro_usuario_paciente` (`DNI_Paciente`, `Apellido_Paterno`, `Apellido_Materno`, `Nombres_Completos`, `Edad_Paciente`, `Dirección_Paciente`, `Num_Telefono_Paciente`, `Correo_Electronico_Paciente`, `Contraseña`) VALUES
('12345678', 'Perez', 'Martinez', 'Juan hugo', 18, 'Av.begonias', '981334440', 'juan@gmail.com', '$2y$10$.fsbbAAroNa/whGdeb2eJuPIkzCb79mypVAgh8b0ydursP1kCJ5yy'),
('74023635', 'Loaiza', 'Martinez', 'Carlos Eduardo', 20, 'Av.El sol -275', '999999999', '74023635@continental.edu.pe', '$2y$10$5wWuBk1xyi/ApPtUudC5lOs9ja28TyBfvv4rogrxTyB6.lEjf8LzW'),
('76543210', 'Gonzales', 'Ramirez', 'Maria Elena', 45, 'Av. Lima 123', '987654321', 'maria.gonzales@email.com', 'maria1234'),
('76543211', 'Rodriguez', 'Silva', 'Carlos Alberto', 32, 'Calle Los Pinos 456', '987654322', 'carlos.rodriguez@email.com', 'carlos1234'),
('76543212', 'Martinez', 'Lopez', 'Ana Cecilia', 28, 'Jr. Union 789', '987654323', 'ana.martinez@email.com', 'ana1234'),
('76543213', 'Hernandez', 'Garcia', 'Jose Manuel', 67, 'Av. Progreso 234', '987654324', 'jose.hernandez@email.com', 'jose1234'),
('76543214', 'Torres', 'Vargas', 'Luis Fernando', 29, 'Calle Real 567', '987654325', 'luis.torres@email.com', 'luis1234'),
('76543215', 'Diaz', 'Mendoza', 'Rosa Isabel', 53, 'Jr. Bolivar 890', '987654326', 'rosa.diaz@email.com', 'rosa1234'),
('76543216', 'Castillo', 'Rojas', 'Miguel Angel', 38, 'Av. Central 123', '987654327', 'miguel.castillo@email.com', 'miguel1234'),
('76543217', 'Romero', 'Salas', 'Elena Patricia', 41, 'Calle Sol 456', '987654328', 'elena.romero@email.com', 'elena1234'),
('76543218', 'Suarez', 'Flores', 'Pedro Pablo', 60, 'Jr. Libertad 789', '987654329', 'pedro.suarez@email.com', 'pedro1234'),
('76543219', 'Chavez', 'Paredes', 'Laura Beatriz', 35, 'Av. Peru 234', '987654330', 'laura.chavez@email.com', 'laura1234'),
('76543220', 'Alvarez', 'Cruz', 'Juan Carlos', 47, 'Calle Luna 567', '987654331', 'juan.alvarez@email.com', 'juan1234'),
('76543221', 'Ruiz', 'Ortega', 'Carmen Rosa', 39, 'Jr. Ayacucho 890', '987654332', 'carmen.ruiz@email.com', 'carmen1234'),
('76543222', 'Medina', 'Guerrero', 'Roberto Jose', 44, 'Av. Tacna 123', '987654333', 'roberto.medina@email.com', 'roberto1234'),
('76543223', 'Perez', 'Nunez', 'Sofia Alejandra', 26, 'Calle Primavera 456', '987654334', 'sofia.perez@email.com', 'sofia1234'),
('76543224', 'Castro', 'Leon', 'Daniel Augusto', 31, 'Jr. San Martin 789', '987654335', 'daniel.castro@email.com', 'daniel1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_idusuarios`
--

CREATE TABLE `tbl_idusuarios` (
  `ID_USUARIO` varchar(15) NOT NULL,
  `DNI_Paciente` char(8) DEFAULT NULL,
  `DNIProfesional` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_idusuarios`
--

INSERT INTO `tbl_idusuarios` (`ID_USUARIO`, `DNI_Paciente`, `DNIProfesional`) VALUES
('74023635', '74023635', NULL),
('USU001', '76543210', NULL),
('USU002', '76543211', NULL),
('USU003', '76543212', NULL),
('USU004', '76543213', NULL),
('USU005', '76543214', NULL),
('USU006', '76543215', NULL),
('USU007', '76543216', NULL),
('USU008', '76543217', NULL),
('USU009', '76543218', NULL),
('USU010', '76543219', NULL),
('USU011', '76543220', NULL),
('USU012', '76543221', NULL),
('USU013', '76543222', NULL),
('USU014', '76543223', NULL),
('USU015', '76543224', NULL),
('USU016', NULL, '12345678'),
('USU017', NULL, '12345679'),
('USU018', NULL, '12345680'),
('USU019', NULL, '12345681'),
('USU020', NULL, '12345682');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_paciente`
--

CREATE TABLE `usuario_paciente` (
  `ID_USU_PACIENTE` varchar(15) NOT NULL,
  `DNI_Paciente` char(8) NOT NULL,
  `Contraseña` varchar(100) NOT NULL,
  `ID_USUARIO` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_profesional`
--

CREATE TABLE `usuario_profesional` (
  `ID_USU_PROFE` int(11) NOT NULL,
  `DNIProfesional` char(8) NOT NULL,
  `Contraseña` varchar(100) NOT NULL,
  `ID_USUARIO` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion_usuario`
--
ALTER TABLE `configuracion_usuario`
  ADD PRIMARY KEY (`IDConfiguración`),
  ADD KEY `FK_CONFIG_CONTRASEÑA` (`ID_CONTRASEÑA`),
  ADD KEY `FK_CONFIG_USUARIO` (`ID_Usuario`);

--
-- Indices de la tabla `contraseñas_cambiadas`
--
ALTER TABLE `contraseñas_cambiadas`
  ADD PRIMARY KEY (`ID_CONTRASEÑA`);

--
-- Indices de la tabla `ficha_registro_clínico`
--
ALTER TABLE `ficha_registro_clínico`
  ADD PRIMARY KEY (`IDHistorialClinico`),
  ADD KEY `FK_FICHA_PROFESIONAL` (`DNIProfesional`),
  ADD KEY `FK_FICHA_PACIENTE` (`DNI_Paciente`);

--
-- Indices de la tabla `generar_cita`
--
ALTER TABLE `generar_cita`
  ADD PRIMARY KEY (`IDCITAMEDICA`),
  ADD KEY `FK_CITA_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `registrar_emergencia`
--
ALTER TABLE `registrar_emergencia`
  ADD PRIMARY KEY (`ID_EMERGENCIA`),
  ADD KEY `FK_EMERGENCIA_PROFESIONAL` (`DNIProfesional`),
  ADD KEY `FK_EMERGENCIA_PACIENTE` (`DNI_Paciente`);

--
-- Indices de la tabla `registrar_inventario`
--
ALTER TABLE `registrar_inventario`
  ADD PRIMARY KEY (`IDmedicamento`),
  ADD KEY `FK_INVENTARIO_PROFESIONAL` (`DNIProfesional`);

--
-- Indices de la tabla `registro_profesional_salud`
--
ALTER TABLE `registro_profesional_salud`
  ADD PRIMARY KEY (`DNI_Profesional`);

--
-- Indices de la tabla `registro_usuario_paciente`
--
ALTER TABLE `registro_usuario_paciente`
  ADD PRIMARY KEY (`DNI_Paciente`);

--
-- Indices de la tabla `tbl_idusuarios`
--
ALTER TABLE `tbl_idusuarios`
  ADD PRIMARY KEY (`ID_USUARIO`);

--
-- Indices de la tabla `usuario_paciente`
--
ALTER TABLE `usuario_paciente`
  ADD PRIMARY KEY (`ID_USU_PACIENTE`),
  ADD KEY `FK_USUPAC_DNI` (`DNI_Paciente`),
  ADD KEY `FK_USUPAC_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `usuario_profesional`
--
ALTER TABLE `usuario_profesional`
  ADD PRIMARY KEY (`ID_USU_PROFE`),
  ADD KEY `FK_USUPROF_DNI` (`DNIProfesional`),
  ADD KEY `FK_USUPROF_USUARIO` (`ID_USUARIO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ficha_registro_clínico`
--
ALTER TABLE `ficha_registro_clínico`
  MODIFY `IDHistorialClinico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `generar_cita`
--
ALTER TABLE `generar_cita`
  MODIFY `IDCITAMEDICA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `registrar_emergencia`
--
ALTER TABLE `registrar_emergencia`
  MODIFY `ID_EMERGENCIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `registrar_inventario`
--
ALTER TABLE `registrar_inventario`
  MODIFY `IDmedicamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuario_profesional`
--
ALTER TABLE `usuario_profesional`
  MODIFY `ID_USU_PROFE` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `configuracion_usuario`
--
ALTER TABLE `configuracion_usuario`
  ADD CONSTRAINT `FK_CONFIG_CONTRASEÑA` FOREIGN KEY (`ID_CONTRASEÑA`) REFERENCES `contraseñas_cambiadas` (`ID_CONTRASEÑA`),
  ADD CONSTRAINT `FK_CONFIG_USUARIO` FOREIGN KEY (`ID_Usuario`) REFERENCES `tbl_idusuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `ficha_registro_clínico`
--
ALTER TABLE `ficha_registro_clínico`
  ADD CONSTRAINT `FK_FICHA_PACIENTE` FOREIGN KEY (`DNI_Paciente`) REFERENCES `registro_usuario_paciente` (`DNI_Paciente`),
  ADD CONSTRAINT `FK_FICHA_PROFESIONAL` FOREIGN KEY (`DNIProfesional`) REFERENCES `registro_profesional_salud` (`DNI_Profesional`);

--
-- Filtros para la tabla `generar_cita`
--
ALTER TABLE `generar_cita`
  ADD CONSTRAINT `FK_CITA_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tbl_idusuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `registrar_emergencia`
--
ALTER TABLE `registrar_emergencia`
  ADD CONSTRAINT `FK_EMERGENCIA_PACIENTE` FOREIGN KEY (`DNI_Paciente`) REFERENCES `registro_usuario_paciente` (`DNI_Paciente`),
  ADD CONSTRAINT `FK_EMERGENCIA_PROFESIONAL` FOREIGN KEY (`DNIProfesional`) REFERENCES `registro_profesional_salud` (`DNI_Profesional`);

--
-- Filtros para la tabla `registrar_inventario`
--
ALTER TABLE `registrar_inventario`
  ADD CONSTRAINT `FK_INVENTARIO_PROFESIONAL` FOREIGN KEY (`DNIProfesional`) REFERENCES `registro_profesional_salud` (`DNI_Profesional`);

--
-- Filtros para la tabla `usuario_paciente`
--
ALTER TABLE `usuario_paciente`
  ADD CONSTRAINT `FK_USUPAC_DNI` FOREIGN KEY (`DNI_Paciente`) REFERENCES `registro_usuario_paciente` (`DNI_Paciente`),
  ADD CONSTRAINT `FK_USUPAC_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tbl_idusuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `usuario_profesional`
--
ALTER TABLE `usuario_profesional`
  ADD CONSTRAINT `FK_USUPROF_DNI` FOREIGN KEY (`DNIProfesional`) REFERENCES `registro_profesional_salud` (`DNI_Profesional`),
  ADD CONSTRAINT `FK_USUPROF_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `tbl_idusuarios` (`ID_USUARIO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
