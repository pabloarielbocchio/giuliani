-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.33-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla giuliani.archivos
CREATE TABLE IF NOT EXISTS `archivos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `ruta` text,
  `fecha_hora` datetime DEFAULT NULL,
  `activo` int(11) DEFAULT '1',
  `cod_prod_na` int(11) DEFAULT NULL,
  `cod_prod_nb` int(11) DEFAULT NULL,
  `cod_prod_nc` int(11) DEFAULT NULL,
  `cod_prod_nd` int(11) DEFAULT NULL,
  `cod_prod_ne` int(11) DEFAULT NULL,
  `cod_prod_nf` int(11) DEFAULT NULL,
  `cod_prod_personalizado_id` int(11) DEFAULT NULL,
  `cod_prod_estandar_id` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.archivos: ~7 rows (aproximadamente)
DELETE FROM `archivos`;
/*!40000 ALTER TABLE `archivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `archivos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.archivo_destinos
CREATE TABLE IF NOT EXISTS `archivo_destinos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `archivo_id` int(11) DEFAULT NULL,
  `destino_id` int(11) DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.archivo_destinos: ~24 rows (aproximadamente)
DELETE FROM `archivo_destinos`;
/*!40000 ALTER TABLE `archivo_destinos` DISABLE KEYS */;
/*!40000 ALTER TABLE `archivo_destinos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) DEFAULT NULL,
  `descripcion` text,
  `direccion` text,
  `ciudad` text,
  `provincia` text,
  `pais` text,
  `forma_pago` int(11) DEFAULT NULL,
  `telefono` text,
  `celular` text,
  `condicion_iva` int(11) DEFAULT NULL,
  `cuit` text,
  `alicuota` decimal(10,2) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla giuliani.clientes: ~0 rows (aproximadamente)
DELETE FROM `clientes`;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.conf_controllers
CREATE TABLE IF NOT EXISTS `conf_controllers` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `menu` text NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.conf_controllers: ~0 rows (aproximadamente)
DELETE FROM `conf_controllers`;
/*!40000 ALTER TABLE `conf_controllers` DISABLE KEYS */;
INSERT INTO `conf_controllers` (`codigo`, `descripcion`, `menu`) VALUES
	(1, '/Giuliani/controller/usuarios.controller.php', 'usuarios.php'),
	(2, '/Giuliani/controller/usuario_destinos.controller.php', 'usuario_destinos.php'),
	(3, '/Giuliani/controller/usuarios.controller.php', 'usuarios_destinos.php');
/*!40000 ALTER TABLE `conf_controllers` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.conf_functions
CREATE TABLE IF NOT EXISTS `conf_functions` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `cod_controller` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.conf_functions: ~10 rows (aproximadamente)
DELETE FROM `conf_functions`;
/*!40000 ALTER TABLE `conf_functions` DISABLE KEYS */;
INSERT INTO `conf_functions` (`codigo`, `descripcion`, `cod_controller`) VALUES
	(1, 'getRegistrosFiltro', 1),
	(2, 'addUsuario', 1),
	(3, 'getUsuario', 1),
	(4, 'deleteUsuario', 1),
	(5, 'updateUsuario', 1),
	(6, 'getRegistrosFiltro', 2),
	(7, 'addUsuario_destino', 2),
	(8, 'getUsuario_destino', 2),
	(9, 'updateUsuario_destino', 2),
	(10, 'deleteUsuario_destino', 2);
/*!40000 ALTER TABLE `conf_functions` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.conf_usuarios
CREATE TABLE IF NOT EXISTS `conf_usuarios` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cod_function` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `habilitado` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.conf_usuarios: ~0 rows (aproximadamente)
DELETE FROM `conf_usuarios`;
/*!40000 ALTER TABLE `conf_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `conf_usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.destinos
CREATE TABLE IF NOT EXISTS `destinos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.destinos: ~4 rows (aproximadamente)
DELETE FROM `destinos`;
/*!40000 ALTER TABLE `destinos` DISABLE KEYS */;
INSERT INTO `destinos` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Corte', 'pbocchio', '2021-03-27 00:00:00'),
	(2, 'Plegado', 'pbocchio', '2021-03-27 00:00:00'),
	(3, 'Manufactura', 'pbocchio', '2021-03-27 00:00:00'),
	(4, 'Limpieza', 'pbocchio', '2021-03-27 00:00:00');
/*!40000 ALTER TABLE `destinos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla giuliani.empresas: ~0 rows (aproximadamente)
DELETE FROM `empresas`;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Giuliani', 'pbocchio', '2019-08-26 00:00:00');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.estados
CREATE TABLE IF NOT EXISTS `estados` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `abrev` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.estados: ~4 rows (aproximadamente)
DELETE FROM `estados`;
/*!40000 ALTER TABLE `estados` DISABLE KEYS */;
INSERT INTO `estados` (`codigo`, `descripcion`, `abrev`, `usuario_m`, `fecha_m`) VALUES
	(1, 'PENDIENTE', 'PEND', 'pbocchio', '2021-03-27 00:00:00'),
	(2, 'EN PROCESO', 'PROC', 'pbocchio', '2021-03-27 00:00:00'),
	(3, 'APROBADO', 'APRO', 'pbocchio', '2021-03-27 00:00:00'),
	(4, 'ANULADO', 'ANUL', 'pbocchio', '2021-03-27 00:00:00');
/*!40000 ALTER TABLE `estados` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.eventos
CREATE TABLE IF NOT EXISTS `eventos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.eventos: ~11 rows (aproximadamente)
DELETE FROM `eventos`;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Subir Archivo', 'pbocchio', '2021-03-27 00:00:00'),
	(2, 'Anular Archivo', 'pbocchio', '2021-03-27 00:00:00'),
	(3, 'Habilitar Archivo', 'pbocchio', '2021-03-27 00:00:00'),
	(4, 'Eliminar Archivo', 'pbocchio', '2021-03-27 00:00:00'),
	(5, 'Descargar Archivo', 'pbocchio', '2021-03-27 00:00:00'),
	(6, 'OT - Cambio de estado', 'pbocchio', '2021-03-27 00:00:00'),
	(7, 'OT - Generacion', 'pbocchio', '2021-03-27 00:00:00'),
	(8, 'OT - Actualizacion', 'pbocchio', '2021-03-27 00:00:00'),
	(9, 'OT Produccion - Generacion', 'pbocchio', '2021-03-27 00:00:00'),
	(10, 'OT Produccion - Cambio de estado', 'pbocchio', '2021-03-27 00:00:00'),
	(11, 'OT Produccion - Actualizacion', 'pbocchio', '2021-03-27 00:00:00'),
	(12, 'OT Produccion - Restablecer Progreso', 'pbocchio', '2021-03-27 00:00:00');
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.menus
CREATE TABLE IF NOT EXISTS `menus` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `nombre` text NOT NULL,
  `destino` text NOT NULL,
  `nivel` int(11) NOT NULL,
  `icono` text NOT NULL,
  `subnivel` int(11) NOT NULL,
  `orden` int(11) NOT NULL,
  `visible` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.menus: ~37 rows (aproximadamente)
DELETE FROM `menus`;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` (`codigo`, `descripcion`, `nombre`, `destino`, `nivel`, `icono`, `subnivel`, `orden`, `visible`) VALUES
	(1, 'inicio.php', 'Home', 'inicio.php', 0, 'fa-home', 0, 1, 1),
	(2, 'usuarios.php', 'Usuarios', 'usuarios.php', 0, 'fa-user', 0, 98, 1),
	(3, 'cerrar.php', 'Salir', 'cerrar.php', 0, 'fa-sign-out', 0, 99, 1),
	(4, 'configuraciones.php', 'CONFIGURACION', 'configuraciones.php', 1, 'fa-gears', 0, 90, 1),
	(5, 'unidades.php', 'Unidades', 'unidades.php', -1, 'fa-gears', 0, 7, 1),
	(6, 'roles.php', 'Roles', 'roles.php', -1, 'fa-gears', 0, 4, 1),
	(7, 'sectores.php', 'Sectores', 'sectores.php', -1, 'fa-gears', 0, 5, 1),
	(8, 'secciones.php', 'Secciones', 'secciones.php', -1, 'fa-gears', 0, 6, 1),
	(9, 'destinos.php', 'Destinos', 'destinos.php', -1, 'fa-gears', 0, 2, 1),
	(10, 'estados.php', 'Estados', 'estados.php', -1, 'fa-gears', 0, 2, 1),
	(11, 'eventos.php', 'Eventos', 'eventos.php', -1, 'fa-gears', 0, 2, 1),
	(12, 'articulos.php', 'ARTICULOS', 'articulos.php', 2, 'fa-list', 0, 3, 1),
	(13, 'productos_a.php', 'Nivel 1', 'productos_a.php', -2, 'fa-gift', 0, 4, 1),
	(14, 'prioridades.php', 'Prioridades', 'prioridades.php', -1, 'fa-gears', 0, 3, 1),
	(15, 'ots.php', 'ORDENES', 'ots.php', 3, 'fa-check-square', 0, 3, 1),
	(16, 'ot_listados.php', 'Listado', 'ot_listados.php', -3, 'fa-check-square', 0, 4, 1),
	(17, 'productos_p.php', 'Personalizados', 'productos_p.php', -2, 'fa-gift', 0, 11, 1),
	(18, 'productos_b.php', 'Nivel 2', 'productos_b.php', -2, 'fa-gift', 0, 5, 1),
	(19, 'ot_detalles.php', 'Detalles', 'ot_detalles.php', -3, 'fa-check-square', 0, 5, 1),
	(20, 'productos_c.php', 'Nivel 3', 'productos_c.php', -2, 'fa-gift', 0, 6, 1),
	(21, 'usuario_destinos.php', 'Usuarios-Destinos', 'usuario_destinos.php', -1, 'fa-gears', 0, 7, 1),
	(22, 'ot_produccions.php', 'Produccion', 'ot_produccions.php', -3, 'fa-check-square', 0, 6, 1),
	(23, 'productos_d.php', 'Nivel 4', 'productos_d.php', -2, 'fa-gift', 0, 7, 1),
	(24, 'ot_eventos.php', 'Eventos', 'ot_eventos.php', -3, 'fa-check-square', 0, 7, 1),
	(25, 'ot_estados.php', 'Estados', 'ot_estados.php', -3, 'fa-check-square', 0, 8, 1),
	(26, 'productos_e.php', 'Nivel 5', 'productos_e.php', -2, 'fa-gift', 0, 8, 1),
	(27, 'productos_s.php', 'Estandards', 'productos_s.php', -2, 'fa-gift', 0, 10, 1),
	(28, 'productos_f.php', 'Nivel 6', 'productos_f.php', -2, 'fa-gift', 0, 9, 1),
	(29, 'archivos.php', 'FILES', 'archivos.php', 4, 'fa-file-o', 0, 4, 1),
	(30, 'archivos.php', 'Listado', 'archivos.php', -4, 'fa-file-o', 0, 4, 1),
	(31, 'productos_x.php', 'Configuraciones', 'productos_x.php', -4, 'fa-file-o', 0, 5, 1),
	(32, 'archivo_destinos.php', 'Destinos', 'archivo_destinos.php', -4, 'fa-file-o', 0, 6, 1),
	(33, 'archivo_produccions.php', 'Produccion', 'archivo_produccions.php', -4, 'fa-file-o', 0, 7, 1),
	(34, 'productos.php', 'PRODUCTOS', 'productos.php', 0, 'fa-gift', 0, 4, 1),
	(35, 'archivos_files.php', 'ARCHIVOS', 'archivos_files.php', 0, 'fa-file', 0, 4, 1),
	(36, 'ot_listados.php', 'OT\'s', 'ot_listados.php', 0, 'fa-list', 0, 5, 1),
	(37, 'ot_seguimientos.php', 'MONITOREO', 'ot_seguimientos.php', 0, 'fa-dashboard', 0, 6, 1);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.menus_usuarios
CREATE TABLE IF NOT EXISTS `menus_usuarios` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cod_menu` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  `edit` int(11) NOT NULL,
  `eliminar` int(11) NOT NULL,
  `new` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  `permiso` int(11) NOT NULL,
  `usuario_m` text NOT NULL,
  `fecha_m` datetime NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.menus_usuarios: ~95 rows (aproximadamente)
DELETE FROM `menus_usuarios`;
/*!40000 ALTER TABLE `menus_usuarios` DISABLE KEYS */;
INSERT INTO `menus_usuarios` (`codigo`, `cod_menu`, `cod_usuario`, `view`, `edit`, `eliminar`, `new`, `access`, `permiso`, `usuario_m`, `fecha_m`) VALUES
	(1, 1, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-05 16:10:43'),
	(2, 2, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(3, 3, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(4, 4, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(5, 5, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(6, 6, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(7, 7, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(8, 8, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(9, 9, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(10, 10, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(11, 11, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(12, 12, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(13, 13, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(14, 14, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(15, 15, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(16, 16, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(17, 17, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(18, 18, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(19, 19, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(20, 20, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(21, 21, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(22, 22, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(23, 23, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(24, 24, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(25, 25, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(26, 26, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(27, 27, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(28, 28, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(29, 29, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(30, 30, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(31, 31, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(32, 32, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(33, 33, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(34, 34, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(35, 35, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(46, 36, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(48, 37, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-18 17:19:35'),
	(50, 1, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(51, 2, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(52, 3, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(53, 4, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(54, 5, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(55, 7, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(56, 8, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(57, 9, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(58, 34, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(59, 36, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(60, 37, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(61, 35, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-04 14:55:59'),
	(62, 1, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(63, 2, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(64, 3, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(65, 4, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(66, 5, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(67, 7, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(68, 8, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(69, 9, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(70, 34, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(71, 36, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(72, 37, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-05-06 17:12:51'),
	(84, 1, 6, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-06 17:37:36'),
	(85, 3, 6, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-06 17:37:36'),
	(86, 34, 6, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-06 17:37:36'),
	(87, 35, 6, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-06 17:37:36'),
	(88, 36, 6, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-06 17:37:36'),
	(89, 37, 6, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-06 17:37:36'),
	(90, 1, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(91, 3, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(92, 4, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(93, 5, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(94, 7, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(95, 8, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(96, 9, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(97, 34, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(98, 35, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(99, 36, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(100, 37, 7, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:18:27'),
	(101, 1, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(102, 2, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(103, 3, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(104, 4, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(105, 5, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(106, 7, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(107, 8, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(108, 9, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(109, 34, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(110, 35, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(111, 36, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(112, 37, 8, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:00'),
	(113, 1, 9, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:16'),
	(114, 3, 9, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:16'),
	(115, 34, 9, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:16'),
	(116, 35, 9, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:16'),
	(117, 36, 9, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:16'),
	(118, 37, 9, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-05-08 15:19:16');
/*!40000 ALTER TABLE `menus_usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos
CREATE TABLE IF NOT EXISTS `orden_trabajos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `nro_serie` text,
  `cliente` text,
  `fecha` date DEFAULT NULL,
  `finalizada` int(11) DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos: ~2 rows (aproximadamente)
DELETE FROM `orden_trabajos`;
/*!40000 ALTER TABLE `orden_trabajos` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_archivos
CREATE TABLE IF NOT EXISTS `orden_trabajos_archivos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `archivo_id` int(11) DEFAULT NULL,
  `ot_produccion_id` int(11) DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_archivos: ~17 rows (aproximadamente)
DELETE FROM `orden_trabajos_archivos`;
/*!40000 ALTER TABLE `orden_trabajos_archivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_archivos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_detalles
CREATE TABLE IF NOT EXISTS `orden_trabajos_detalles` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `orden_trabajo_id` int(11) DEFAULT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `sector_id` int(11) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `prioridad_id` int(11) DEFAULT NULL,
  `finalizada` int(11) DEFAULT NULL,
  `item_vendido` text,
  `observaciones` text,
  `cantidad` float DEFAULT NULL,
  `avance` float DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_detalles: ~25 rows (aproximadamente)
DELETE FROM `orden_trabajos_detalles`;
/*!40000 ALTER TABLE `orden_trabajos_detalles` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_detalles` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_estados
CREATE TABLE IF NOT EXISTS `orden_trabajos_estados` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `ot_prod_id` int(11) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `ing_alcance` int(11) DEFAULT NULL,
  `ing_planos` int(11) DEFAULT NULL,
  `ingenieria` int(11) DEFAULT NULL,
  `produccion` int(11) DEFAULT NULL,
  `calidad` int(11) DEFAULT NULL,
  `gerencia` int(11) DEFAULT NULL,
  `destino_id` int(11) DEFAULT NULL,
  `avance` float DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_estados: ~10 rows (aproximadamente)
DELETE FROM `orden_trabajos_estados`;
/*!40000 ALTER TABLE `orden_trabajos_estados` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_estados` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_eventos
CREATE TABLE IF NOT EXISTS `orden_trabajos_eventos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` int(11) NOT NULL DEFAULT '0',
  `ot_detalle_id` int(11) NOT NULL DEFAULT '0',
  `ot_produccion_id` int(11) NOT NULL DEFAULT '0',
  `destino_id` int(11) NOT NULL DEFAULT '0',
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_eventos: ~48 rows (aproximadamente)
DELETE FROM `orden_trabajos_eventos`;
/*!40000 ALTER TABLE `orden_trabajos_eventos` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_eventos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_produccion
CREATE TABLE IF NOT EXISTS `orden_trabajos_produccion` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `ot_detalle_id` int(11) DEFAULT NULL,
  `prod_estandar_id` int(11) DEFAULT NULL,
  `prod_personalizado_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `unidad_id` int(11) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `avance` int(11) DEFAULT NULL,
  `prioridad_id` int(11) DEFAULT NULL,
  `standar` int(11) DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_produccion: ~2 rows (aproximadamente)
DELETE FROM `orden_trabajos_produccion`;
/*!40000 ALTER TABLE `orden_trabajos_produccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_produccion` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.prioridades
CREATE TABLE IF NOT EXISTS `prioridades` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `prioridad` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.prioridades: ~3 rows (aproximadamente)
DELETE FROM `prioridades`;
/*!40000 ALTER TABLE `prioridades` DISABLE KEYS */;
INSERT INTO `prioridades` (`codigo`, `descripcion`, `prioridad`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Muy Alta', 1, 'pbocchio', '2021-03-27 00:00:00'),
	(2, 'Alta', 2, 'pbocchio', '2021-03-27 00:00:00'),
	(3, 'Normal', 3, 'pbocchio', '2021-03-27 00:00:00');
/*!40000 ALTER TABLE `prioridades` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_configuraciones
CREATE TABLE IF NOT EXISTS `productos_configuraciones` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `prod_standar_id` int(11) DEFAULT NULL,
  `prod_f_id` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_configuraciones: ~8 rows (aproximadamente)
DELETE FROM `productos_configuraciones`;
/*!40000 ALTER TABLE `productos_configuraciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_configuraciones` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_estandar
CREATE TABLE IF NOT EXISTS `productos_estandar` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `cod_prod_nd` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_estandar: ~4 rows (aproximadamente)
DELETE FROM `productos_estandar`;
/*!40000 ALTER TABLE `productos_estandar` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_estandar` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_a
CREATE TABLE IF NOT EXISTS `productos_nivel_a` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_a: ~2 rows (aproximadamente)
DELETE FROM `productos_nivel_a`;
/*!40000 ALTER TABLE `productos_nivel_a` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_a` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_b
CREATE TABLE IF NOT EXISTS `productos_nivel_b` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `cod_prod_na` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_b: ~3 rows (aproximadamente)
DELETE FROM `productos_nivel_b`;
/*!40000 ALTER TABLE `productos_nivel_b` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_b` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_c
CREATE TABLE IF NOT EXISTS `productos_nivel_c` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `cod_prod_nb` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_c: ~2 rows (aproximadamente)
DELETE FROM `productos_nivel_c`;
/*!40000 ALTER TABLE `productos_nivel_c` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_c` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_d
CREATE TABLE IF NOT EXISTS `productos_nivel_d` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `unidad_id` int(11) DEFAULT NULL,
  `cod_prod_nc` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_d: ~0 rows (aproximadamente)
DELETE FROM `productos_nivel_d`;
/*!40000 ALTER TABLE `productos_nivel_d` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_d` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_e
CREATE TABLE IF NOT EXISTS `productos_nivel_e` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `cod_prod_nd` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_e: ~1 rows (aproximadamente)
DELETE FROM `productos_nivel_e`;
/*!40000 ALTER TABLE `productos_nivel_e` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_e` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_f
CREATE TABLE IF NOT EXISTS `productos_nivel_f` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `cod_prod_ne` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_f: ~4 rows (aproximadamente)
DELETE FROM `productos_nivel_f`;
/*!40000 ALTER TABLE `productos_nivel_f` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_f` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_personalizados
CREATE TABLE IF NOT EXISTS `productos_personalizados` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `unidad_id` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_personalizados: ~4 rows (aproximadamente)
DELETE FROM `productos_personalizados`;
/*!40000 ALTER TABLE `productos_personalizados` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_personalizados` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) DEFAULT NULL,
  `descripcion` text,
  `direccion` text,
  `ciudad` text,
  `provincia` text,
  `pais` text,
  `forma_pago` int(11) DEFAULT NULL,
  `telefono` text,
  `celular` text,
  `condicion_iva` int(11) DEFAULT NULL,
  `cuit` text,
  `alicuota` decimal(10,2) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla giuliani.proveedores: ~0 rows (aproximadamente)
DELETE FROM `proveedores`;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `usuario_m` text NOT NULL,
  `fecha_m` datetime NOT NULL,
  PRIMARY KEY (`codigo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.roles: ~5 rows (aproximadamente)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Administrador', 'pbocchio', '2021-03-27 08:04:50'),
	(2, 'Gerencial', 'pbocchio', '2021-03-27 08:04:50'),
	(3, 'Calidad', 'pbocchio', '2021-03-27 08:04:50'),
	(4, 'Producción', 'pbocchio', '2021-03-27 08:04:50'),
	(5, 'Ingeniería', 'pbocchio', '2021-03-27 08:04:50');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.secciones
CREATE TABLE IF NOT EXISTS `secciones` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.secciones: ~4 rows (aproximadamente)
DELETE FROM `secciones`;
/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `secciones` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.sectores
CREATE TABLE IF NOT EXISTS `sectores` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.sectores: ~8 rows (aproximadamente)
DELETE FROM `sectores`;
/*!40000 ALTER TABLE `sectores` DISABLE KEYS */;
/*!40000 ALTER TABLE `sectores` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.unidades
CREATE TABLE IF NOT EXISTS `unidades` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `descrip_abrev` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.unidades: ~2 rows (aproximadamente)
DELETE FROM `unidades`;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` (`codigo`, `descripcion`, `descrip_abrev`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Unidad', 'Unid.', 'pbocchio', '2021-03-27 08:06:14');
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `usuario_m` varchar(20) DEFAULT NULL,
  `fecha_m` date DEFAULT NULL,
  `cargo` text,
  `nombre` text,
  `id_cliente` int(11) DEFAULT NULL,
  `last_cliente` int(11) DEFAULT NULL,
  `name` text,
  `surname` text,
  `mail` text,
  `phone` text,
  `reintentos` int(11) DEFAULT NULL,
  `bloqueado` int(11) DEFAULT NULL,
  `sistemas` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `fk_usuario_cliente_idx` (`id_cliente`),
  KEY `fk_usuario_last_cliente_idx` (`last_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla giuliani.usuarios: ~5 rows (aproximadamente)
DELETE FROM `usuarios`;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`codigo`, `usuario`, `password`, `usuario_m`, `fecha_m`, `cargo`, `nombre`, `id_cliente`, `last_cliente`, `name`, `surname`, `mail`, `phone`, `reintentos`, `bloqueado`, `sistemas`, `id_rol`) VALUES
	(1, 'pbocchio', '827ccb0eea8a706c4c34a16891f84e7b', 'pbocchio', '2019-12-30', 'Administrador', 'Pablo Bocchio', NULL, 1, 'Pablo', 'Bocchio', 'pablobocchio@gmail.com', '3406411105', 0, 0, 1, 1),
	(4, 'giuliani', 'e36be0d03158c871e082da61a808bfaf', '4', '2021-05-04', NULL, 'Giuliani Hnos.', NULL, NULL, 'Giuliani', 'Hnos.', '-', NULL, NULL, NULL, NULL, 1),
	(6, 'calidad', 'd8911124a43dbc68e306f7d1420fdb54', '1', '2021-05-06', NULL, 'calidad calidad', NULL, NULL, 'calidad', 'calidad', 'calidad', NULL, NULL, NULL, NULL, 3),
	(7, 'ingenieria', '01bf897db0326f3ad5bdeff53a54f2e6', '1', '2021-05-08', NULL, 'ingenieria ingenieria', NULL, NULL, 'ingenieria', 'ingenieria', 'ingenieria', NULL, NULL, NULL, NULL, 5),
	(8, 'gerencial', '9e55e9bc9b034364f1848bee6eb5402a', '1', '2021-05-08', NULL, 'gerencial gerencial', NULL, NULL, 'gerencial', 'gerencial', 'gerencial', NULL, NULL, NULL, NULL, 2),
	(9, 'produccion', '6b9dac4cec5a347829b2a2241c458139', '1', '2021-05-08', NULL, 'produccion produccion', NULL, NULL, 'produccion', 'produccion', 'produccion', NULL, NULL, NULL, NULL, 4);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.usuarios_destinos
CREATE TABLE IF NOT EXISTS `usuarios_destinos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `destino_id` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.usuarios_destinos: ~11 rows (aproximadamente)
DELETE FROM `usuarios_destinos`;
/*!40000 ALTER TABLE `usuarios_destinos` DISABLE KEYS */;
INSERT INTO `usuarios_destinos` (`codigo`, `usuario_id`, `destino_id`, `usuario_m`, `fecha_m`) VALUES
	(1, 1, 1, 'pbocchio', '2021-03-29 16:41:04'),
	(2, 1, 2, 'pbocchio', '2021-03-29 16:41:04'),
	(3, 1, 3, 'pbocchio', '2021-03-29 16:41:04'),
	(4, 1, 4, 'pbocchio', '2021-03-29 16:41:04'),
	(13, 3, 4, 'pbocchio', '2021-04-14 00:00:00'),
	(14, 3, 2, 'pbocchio', '2021-04-14 00:00:00'),
	(19, 9, 3, 'pbocchio', '2021-05-13 00:00:00'),
	(20, 9, 2, 'pbocchio', '2021-05-13 00:00:00'),
	(21, 4, 1, 'giuliani', '2021-05-14 00:00:00'),
	(22, 4, 4, 'giuliani', '2021-05-14 00:00:00'),
	(23, 4, 3, 'giuliani', '2021-05-14 00:00:00'),
	(24, 4, 2, 'giuliani', '2021-05-14 00:00:00');
/*!40000 ALTER TABLE `usuarios_destinos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.utils
CREATE TABLE IF NOT EXISTS `utils` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `valor` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.utils: ~0 rows (aproximadamente)
DELETE FROM `utils`;
/*!40000 ALTER TABLE `utils` DISABLE KEYS */;
/*!40000 ALTER TABLE `utils` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
