-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.33-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             11.3.0.6295
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
  `cod_ot_detalle_id` int(11) DEFAULT NULL,
  `cod_ot` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.archivos: ~0 rows (aproximadamente)
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

-- Volcando datos para la tabla giuliani.archivo_destinos: ~0 rows (aproximadamente)
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.conf_controllers: ~17 rows (aproximadamente)
DELETE FROM `conf_controllers`;
/*!40000 ALTER TABLE `conf_controllers` DISABLE KEYS */;
INSERT INTO `conf_controllers` (`codigo`, `descripcion`, `menu`) VALUES
	(1, '/Giuliani/controller/usuarios.controller.php', 'usuarios.php'),
	(2, '/Giuliani/controller/usuario_destinos.controller.php', 'usuario_destinos.php'),
	(3, '/Giuliani/controller/usuarios.controller.php', 'usuarios_destinos.php'),
	(4, '/Giuliani/menus.php', 'inicio.php'),
	(5, '/Giuliani/controller/menus.controller.php', 'menus.php'),
	(6, '/Giuliani/menus.php', 'menus.php'),
	(7, '/Giuliani/monitoreos.php', ''),
	(8, '/Giuliani/controller/monitoreos.controller.php', 'monitoreos.php'),
	(9, '/Giuliani/monitoreos.php', 'monitoreos.php'),
	(10, '/Giuliani/menus.php', 'usuarios.php'),
	(11, '/Giuliani/menus.php', 'ot_seguimientos.php'),
	(12, '/Giuliani/controller/monitoreos.controller.php', ''),
	(13, '/Giuliani/menus.php', 'roles.php'),
	(14, '/Giuliani/menus.php', 'monitoreos.php'),
	(15, '/Giuliani/menus.php', 'ot_listados.php'),
	(16, '/Giuliani/menus.php', 'productos.php'),
	(17, '/Giuliani/controller/menus.controller.php', 'monitoreos.php');
/*!40000 ALTER TABLE `conf_controllers` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.conf_functions
CREATE TABLE IF NOT EXISTS `conf_functions` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `cod_controller` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.conf_functions: ~17 rows (aproximadamente)
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
	(10, 'deleteUsuario_destino', 2),
	(11, 'getMenusAll', 4),
	(12, 'getRegistrosFiltro', 5),
	(13, 'cambiar_estadoRolMenu', 5),
	(14, 'getMonitoreosAll', 7),
	(15, 'getRoles', 7),
	(16, 'getRegistrosFiltro', 8),
	(17, 'cambiar_estadoRolMonitoreo', 8);
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
  `orden` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.destinos: ~13 rows (aproximadamente)
DELETE FROM `destinos`;
/*!40000 ALTER TABLE `destinos` DISABLE KEYS */;
INSERT INTO `destinos` (`codigo`, `descripcion`, `orden`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Ingenieria', 1, 'giuliani', '2021-07-21 10:54:28'),
	(2, 'Requisitos de Compra', 2, 'pbocchio', '2021-03-27 00:00:00'),
	(3, 'Compras', 3, 'pbocchio', '2021-03-27 00:00:00'),
	(4, 'Corte', 4, 'pbocchio', '2021-03-27 00:00:00'),
	(5, 'Plegado', 5, 'pbocchio', '2021-03-27 00:00:00'),
	(6, 'Torneria', 6, 'pbocchio', '2021-03-27 00:00:00'),
	(7, 'Chaperia', 7, 'pbocchio', '2021-07-20 11:14:50'),
	(8, 'Pintura', 8, 'pbocchio', '2021-07-20 11:14:53'),
	(9, 'Inst. Electricas', 9, 'pbocchio', '2021-07-20 11:14:53'),
	(10, 'Armado', 10, 'pbocchio', '2021-07-20 11:14:53'),
	(11, 'Despacho', 11, 'pbocchio', '2021-07-20 11:14:53'),
	(12, 'Calidad', 12, 'pbocchio', '2021-07-20 11:14:53'),
	(13, 'Gerencia', 13, 'pbocchio', '2021-07-20 11:14:53');
/*!40000 ALTER TABLE `destinos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla giuliani.empresas: ~1 rows (aproximadamente)
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

-- Volcando datos para la tabla giuliani.eventos: ~12 rows (aproximadamente)
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
  `publico` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.menus: ~41 rows (aproximadamente)
DELETE FROM `menus`;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` (`codigo`, `descripcion`, `nombre`, `destino`, `nivel`, `icono`, `subnivel`, `orden`, `visible`, `publico`) VALUES
	(1, 'inicio.php', 'Home', 'inicio.php', 0, 'fa-home', 0, 1, 1, 1),
	(2, 'usuarios.php', 'Usuarios', 'usuarios.php', 0, 'fa-user', 0, 98, 1, 1),
	(3, 'cerrar.php', 'Salir', 'cerrar.php', 0, 'fa-sign-out', 0, 99, 1, 1),
	(4, 'configuraciones.php', 'CONFIGURACION', 'configuraciones.php', 1, 'fa-gears', 0, 90, 1, 1),
	(5, 'unidades.php', 'Unidades', 'unidades.php', -1, 'fa-gears', 0, 7, 1, 1),
	(6, 'roles.php', 'Roles', 'roles.php', -1, 'fa-gears', 0, 4, 1, 1),
	(7, 'sectores.php', 'Sectores', 'sectores.php', -1, 'fa-gears', 0, 5, 1, 0),
	(8, 'secciones.php', 'Secciones', 'secciones.php', -1, 'fa-gears', 0, 6, 1, 0),
	(9, 'destinos.php', 'Areas', 'destinos.php', -1, 'fa-gears', 0, 2, 1, 1),
	(10, 'estados.php', 'Estados', 'estados.php', -1, 'fa-gears', 0, 2, 1, 0),
	(11, 'eventos.php', 'Eventos', 'eventos.php', -1, 'fa-gears', 0, 2, 1, 0),
	(12, 'articulos.php', 'ARTICULOS', 'articulos.php', 2, 'fa-list', 0, 3, 1, 0),
	(13, 'productos_a.php', 'Nivel 1', 'productos_a.php', -2, 'fa-gift', 0, 4, 1, 0),
	(14, 'prioridades.php', 'Prioridades', 'prioridades.php', -1, 'fa-gears', 0, 3, 1, 0),
	(15, 'ots.php', 'ORDENES', 'ots.php', 3, 'fa-check-square', 0, 3, 1, 0),
	(16, 'ot_listados.php', 'Listado', 'ot_listados.php', -3, 'fa-check-square', 0, 4, 1, 0),
	(17, 'productos_p.php', 'Personalizados', 'productos_p.php', -2, 'fa-gift', 0, 11, 1, 0),
	(18, 'productos_b.php', 'Nivel 2', 'productos_b.php', -2, 'fa-gift', 0, 5, 1, 0),
	(19, 'ot_detalles.php', 'Detalles', 'ot_detalles.php', -3, 'fa-check-square', 0, 5, 1, 0),
	(20, 'productos_c.php', 'Nivel 3', 'productos_c.php', -2, 'fa-gift', 0, 6, 1, 0),
	(21, 'usuario_destinos.php', 'Usuarios-Destinos', 'usuario_destinos.php', -1, 'fa-gears', 0, 7, 1, 0),
	(22, 'ot_produccions.php', 'Produccion', 'ot_produccions.php', -3, 'fa-check-square', 0, 6, 1, 0),
	(23, 'productos_d.php', 'Nivel 4', 'productos_d.php', -2, 'fa-gift', 0, 7, 1, 0),
	(24, 'ot_eventos.php', 'Eventos', 'ot_eventos.php', -3, 'fa-check-square', 0, 7, 1, 0),
	(25, 'ot_estados.php', 'Estados', 'ot_estados.php', -3, 'fa-check-square', 0, 8, 1, 0),
	(26, 'productos_e.php', 'Nivel 5', 'productos_e.php', -2, 'fa-gift', 0, 8, 1, 0),
	(27, 'productos_s.php', 'Estandards', 'productos_s.php', -2, 'fa-gift', 0, 10, 1, 0),
	(28, 'productos_f.php', 'Nivel 6', 'productos_f.php', -2, 'fa-gift', 0, 9, 1, 0),
	(29, 'archivos.php', 'FILES', 'archivos.php', 4, 'fa-file-o', 0, 4, 1, 0),
	(30, 'archivos.php', 'Listado', 'archivos.php', -4, 'fa-file-o', 0, 4, 1, 0),
	(31, 'productos_x.php', 'Configuraciones', 'productos_x.php', -4, 'fa-file-o', 0, 5, 1, 0),
	(32, 'archivo_destinos.php', 'Destinos', 'archivo_destinos.php', -4, 'fa-file-o', 0, 6, 1, 0),
	(33, 'archivo_produccions.php', 'Produccion', 'archivo_produccions.php', -4, 'fa-file-o', 0, 7, 1, 0),
	(34, 'productos.php', 'PRODUCTOS', 'productos.php', 0, 'fa-gift', 0, 4, 1, 1),
	(35, 'archivos_files.php', 'PLANOS', 'archivos_files.php', 0, 'fa-file', 0, 4, 1, 1),
	(36, 'ot_listados.php', 'OT\'s', 'ot_listados.php', 0, 'fa-list', 0, 5, 1, 1),
	(37, 'ot_seguimientos.php', 'MONITOREO', 'ot_seguimientos.php', 0, 'fa-dashboard', 0, 6, 1, 1),
	(38, 'utils.php', 'Frase Inicial', 'utils.php', -1, 'fa-gears', 0, 2, 1, 1),
	(39, 'menus.php', 'Permisos Menu ', 'menus.php', -1, 'fa-gears', 0, 7, 1, 1),
	(40, 'monitoreos.php', 'Permisos OT\'s', 'monitoreos.php', -1, 'fa-gears', 0, 7, 1, 1),
	(41, 'archivos_mapa.php', 'MAPA DESARROLLO', 'archivos_mapa.php', 0, 'fa-gear', 0, 4, 1, 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla giuliani.menus_usuarios: ~41 rows (aproximadamente)
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
	(120, 38, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-05 16:10:43'),
	(122, 39, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-05 16:10:43'),
	(124, 40, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-05 16:10:43'),
	(152, 41, 1, 1, 1, 1, 1, 1, 1, '1', '2020-05-05 16:10:43'),
	(169, 1, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:29'),
	(170, 2, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:29'),
	(171, 3, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:29'),
	(172, 4, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:29'),
	(173, 5, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:29'),
	(174, 9, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(175, 34, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(176, 35, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(177, 36, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(178, 37, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(179, 38, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(180, 39, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(181, 40, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(182, 41, 2, 1, 1, 1, 1, 1, 1, 'pbocchio', '2021-08-09 07:05:30'),
	(183, 1, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(184, 2, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(185, 3, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(186, 4, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(187, 5, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(188, 9, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(189, 34, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(190, 35, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(191, 36, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(192, 37, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(193, 38, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(194, 39, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(195, 40, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(196, 41, 3, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:22'),
	(197, 1, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(198, 2, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(199, 3, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(200, 4, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(201, 5, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(202, 9, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(203, 34, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(204, 35, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(205, 36, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(206, 37, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(207, 38, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(208, 39, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(209, 40, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(210, 41, 4, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:06:41'),
	(211, 1, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(212, 2, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(213, 3, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(214, 4, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(215, 5, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(216, 9, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(217, 34, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(218, 35, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(219, 36, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(220, 37, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(221, 38, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(222, 39, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(223, 40, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(224, 41, 5, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:01'),
	(225, 1, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(226, 2, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(227, 3, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(228, 4, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(229, 5, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(230, 9, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(231, 34, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(232, 35, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(233, 36, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(234, 37, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(235, 38, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(236, 39, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(237, 40, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(238, 41, 6, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:19'),
	(239, 1, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(240, 2, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(241, 3, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(242, 4, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(243, 5, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(244, 9, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(245, 34, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(246, 35, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(247, 36, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(248, 37, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(249, 38, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(250, 39, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(251, 40, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(252, 41, 7, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:35'),
	(253, 1, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(254, 2, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(255, 3, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(256, 4, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(257, 5, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(258, 9, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(259, 34, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(260, 35, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(261, 36, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(262, 37, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(263, 38, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(264, 39, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(265, 40, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(266, 41, 8, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:07:50'),
	(267, 1, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(268, 2, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(269, 3, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(270, 4, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(271, 5, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(272, 9, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(273, 34, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(274, 35, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(275, 36, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(276, 37, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(277, 38, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(278, 39, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(279, 40, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06'),
	(280, 41, 9, 1, 1, 1, 1, 1, 1, 'giuliani', '2021-08-09 07:08:06');
/*!40000 ALTER TABLE `menus_usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos
CREATE TABLE IF NOT EXISTS `orden_trabajos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `nro_serie` text,
  `cliente` text,
  `fecha` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `finalizada` int(11) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `avance` float DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos: ~0 rows (aproximadamente)
DELETE FROM `orden_trabajos`;
/*!40000 ALTER TABLE `orden_trabajos` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_archivos
CREATE TABLE IF NOT EXISTS `orden_trabajos_archivos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `archivo_id` int(11) DEFAULT NULL,
  `ot_produccion_id` int(11) DEFAULT NULL,
  `ot_detalle_id` int(11) DEFAULT NULL,
  `ot_id` int(11) DEFAULT NULL,
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_archivos: ~0 rows (aproximadamente)
DELETE FROM `orden_trabajos_archivos`;
/*!40000 ALTER TABLE `orden_trabajos_archivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_archivos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_detalles
CREATE TABLE IF NOT EXISTS `orden_trabajos_detalles` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `orden_trabajo_id` int(11) DEFAULT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `seccion` text,
  `sector_id` int(11) DEFAULT NULL,
  `sector` text,
  `estado_id` int(11) DEFAULT NULL,
  `prioridad_id` int(11) DEFAULT NULL,
  `finalizada` int(11) DEFAULT NULL,
  `item_vendido` text,
  `observaciones` text,
  `cantidad` float DEFAULT NULL,
  `pu` float DEFAULT NULL,
  `pu_cant` float DEFAULT NULL,
  `pu_neto` float DEFAULT NULL,
  `clasificacion` text,
  `avance` float DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_detalles: ~0 rows (aproximadamente)
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

-- Volcando datos para la tabla giuliani.orden_trabajos_estados: ~0 rows (aproximadamente)
DELETE FROM `orden_trabajos_estados`;
/*!40000 ALTER TABLE `orden_trabajos_estados` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_estados` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_eventos
CREATE TABLE IF NOT EXISTS `orden_trabajos_eventos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` int(11) NOT NULL DEFAULT '0',
  `ot_id` int(11) NOT NULL DEFAULT '0',
  `ot_detalle_id` int(11) NOT NULL DEFAULT '0',
  `ot_produccion_id` int(11) NOT NULL DEFAULT '0',
  `destino_id` int(11) NOT NULL DEFAULT '0',
  `observaciones` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.orden_trabajos_eventos: ~0 rows (aproximadamente)
DELETE FROM `orden_trabajos_eventos`;
/*!40000 ALTER TABLE `orden_trabajos_eventos` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_trabajos_eventos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.orden_trabajos_produccion
CREATE TABLE IF NOT EXISTS `orden_trabajos_produccion` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `numero` text,
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

-- Volcando datos para la tabla giuliani.orden_trabajos_produccion: ~0 rows (aproximadamente)
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

-- Volcando datos para la tabla giuliani.productos_configuraciones: ~0 rows (aproximadamente)
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

-- Volcando datos para la tabla giuliani.productos_estandar: ~0 rows (aproximadamente)
DELETE FROM `productos_estandar`;
/*!40000 ALTER TABLE `productos_estandar` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_estandar` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_a
CREATE TABLE IF NOT EXISTS `productos_nivel_a` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `ing_estado` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_a: ~0 rows (aproximadamente)
DELETE FROM `productos_nivel_a`;
/*!40000 ALTER TABLE `productos_nivel_a` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_a` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_b
CREATE TABLE IF NOT EXISTS `productos_nivel_b` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `ing_estado` int(11) DEFAULT NULL,
  `cod_prod_na` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_b: ~0 rows (aproximadamente)
DELETE FROM `productos_nivel_b`;
/*!40000 ALTER TABLE `productos_nivel_b` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_b` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_c
CREATE TABLE IF NOT EXISTS `productos_nivel_c` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `ing_estado` int(11) DEFAULT NULL,
  `cod_prod_nb` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_c: ~0 rows (aproximadamente)
DELETE FROM `productos_nivel_c`;
/*!40000 ALTER TABLE `productos_nivel_c` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_c` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_d
CREATE TABLE IF NOT EXISTS `productos_nivel_d` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `ing_estado` int(11) DEFAULT NULL,
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
  `ing_estado` int(11) DEFAULT NULL,
  `cod_prod_nd` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_e: ~0 rows (aproximadamente)
DELETE FROM `productos_nivel_e`;
/*!40000 ALTER TABLE `productos_nivel_e` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_nivel_e` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.productos_nivel_f
CREATE TABLE IF NOT EXISTS `productos_nivel_f` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `ing_estado` int(11) DEFAULT NULL,
  `cod_prod_ne` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.productos_nivel_f: ~0 rows (aproximadamente)
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

-- Volcando datos para la tabla giuliani.productos_personalizados: ~0 rows (aproximadamente)
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.roles: ~8 rows (aproximadamente)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'Administrador', 'pbocchio', '2021-03-27 08:04:50'),
	(2, 'Gerencial', 'pbocchio', '2021-03-27 08:04:50'),
	(3, 'Calidad', 'pbocchio', '2021-03-27 08:04:50'),
	(4, 'Producción', 'pbocchio', '2021-03-27 08:04:50'),
	(5, 'Ingeniería', 'pbocchio', '2021-03-27 08:04:50'),
	(6, 'Compras', 'pbocchio', '2021-07-18 07:42:18'),
	(7, 'Logistica', 'pbocchio', '2021-07-18 07:42:25'),
	(8, 'Planificacion', 'pbocchio', '2021-07-18 07:42:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.roles_destinos
CREATE TABLE IF NOT EXISTS `roles_destinos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destino_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `permiso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.roles_destinos: ~83 rows (aproximadamente)
DELETE FROM `roles_destinos`;
/*!40000 ALTER TABLE `roles_destinos` DISABLE KEYS */;
INSERT INTO `roles_destinos` (`id`, `destino_id`, `rol_id`, `permiso`) VALUES
	(4, 10, 3, 1),
	(6, 7, 3, 1),
	(7, 3, 3, 1),
	(11, 4, 3, 1),
	(12, 11, 3, 1),
	(13, 13, 3, 1),
	(14, 1, 3, 1),
	(15, 9, 3, 1),
	(16, 8, 3, 1),
	(17, 5, 3, 1),
	(18, 2, 3, 1),
	(19, 6, 3, 1),
	(20, 12, 3, 2),
	(21, 10, 6, 1),
	(22, 12, 6, 1),
	(23, 7, 6, 1),
	(25, 4, 6, 1),
	(26, 11, 6, 1),
	(27, 13, 6, 1),
	(28, 1, 6, 1),
	(29, 9, 6, 1),
	(30, 8, 6, 1),
	(31, 5, 6, 1),
	(33, 6, 6, 1),
	(35, 3, 6, 2),
	(37, 2, 6, 1),
	(39, 10, 2, 2),
	(41, 12, 2, 2),
	(43, 7, 2, 2),
	(45, 3, 2, 2),
	(47, 6, 2, 2),
	(49, 2, 2, 2),
	(52, 5, 2, 2),
	(53, 8, 2, 2),
	(55, 9, 2, 2),
	(57, 1, 2, 2),
	(59, 13, 2, 2),
	(61, 11, 2, 2),
	(63, 4, 2, 2),
	(64, 12, 5, 1),
	(65, 13, 5, 1),
	(67, 1, 5, 2),
	(69, 2, 5, 2),
	(71, 3, 5, 2),
	(72, 10, 7, 1),
	(73, 12, 7, 1),
	(74, 7, 7, 1),
	(75, 3, 7, 1),
	(76, 4, 7, 1),
	(81, 13, 7, 1),
	(82, 1, 7, 1),
	(83, 9, 7, 1),
	(84, 8, 7, 1),
	(85, 5, 7, 1),
	(86, 2, 7, 1),
	(87, 6, 7, 1),
	(88, 11, 7, 2),
	(90, 12, 8, 1),
	(95, 13, 8, 1),
	(96, 1, 8, 1),
	(100, 2, 8, 1),
	(102, 4, 8, 2),
	(103, 5, 8, 2),
	(104, 6, 8, 2),
	(106, 8, 8, 2),
	(107, 9, 8, 2),
	(108, 10, 8, 2),
	(109, 11, 8, 2),
	(111, 12, 4, 1),
	(117, 1, 4, 1),
	(121, 2, 4, 1),
	(123, 7, 4, 2),
	(124, 10, 4, 2),
	(127, 3, 4, 1),
	(128, 11, 4, 2),
	(131, 13, 4, 1),
	(132, 9, 4, 2),
	(133, 8, 4, 2),
	(134, 5, 4, 2),
	(135, 6, 4, 2),
	(136, 4, 4, 2),
	(137, 7, 8, 2),
	(139, 3, 8, 1);
/*!40000 ALTER TABLE `roles_destinos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.roles_menus
CREATE TABLE IF NOT EXISTS `roles_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `permiso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.roles_menus: ~62 rows (aproximadamente)
DELETE FROM `roles_menus`;
/*!40000 ALTER TABLE `roles_menus` DISABLE KEYS */;
INSERT INTO `roles_menus` (`id`, `menu_id`, `rol_id`, `permiso`) VALUES
	(1, 1, 1, 2),
	(2, 1, 2, 2),
	(4, 1, 4, 2),
	(5, 1, 5, 2),
	(7, 1, 7, 2),
	(8, 1, 8, 2),
	(19, 1, 3, 2),
	(42, 3, 3, 2),
	(51, 37, 3, 2),
	(63, 34, 2, 2),
	(65, 35, 2, 2),
	(67, 36, 2, 2),
	(69, 37, 2, 2),
	(72, 4, 2, 2),
	(73, 9, 2, 2),
	(75, 38, 2, 2),
	(77, 6, 2, 2),
	(79, 5, 2, 2),
	(81, 2, 2, 2),
	(83, 3, 2, 2),
	(87, 35, 3, 1),
	(89, 36, 3, 1),
	(90, 4, 3, 0),
	(91, 9, 3, 0),
	(92, 38, 3, 0),
	(93, 6, 3, 0),
	(94, 5, 3, 0),
	(95, 2, 3, 0),
	(97, 1, 6, 2),
	(98, 35, 6, 1),
	(99, 36, 6, 1),
	(101, 37, 6, 2),
	(103, 3, 6, 2),
	(105, 34, 5, 2),
	(107, 35, 5, 2),
	(109, 36, 5, 2),
	(111, 37, 5, 2),
	(113, 3, 5, 2),
	(114, 35, 7, 1),
	(115, 36, 7, 1),
	(117, 37, 7, 2),
	(119, 3, 7, 2),
	(120, 35, 8, 1),
	(122, 36, 8, 2),
	(124, 37, 8, 2),
	(126, 3, 8, 2),
	(128, 35, 4, 1),
	(130, 34, 4, 0),
	(131, 36, 4, 1),
	(133, 37, 4, 2),
	(135, 3, 4, 2),
	(137, 39, 2, 2),
	(139, 40, 2, 2),
	(144, 38, 5, 0),
	(145, 9, 5, 0),
	(148, 6, 5, 0),
	(151, 5, 5, 0),
	(154, 39, 5, 0),
	(157, 40, 5, 0),
	(160, 2, 5, 0),
	(163, 34, 3, 0),
	(171, 9, 6, 0),
	(174, 38, 6, 0),
	(177, 6, 6, 0),
	(180, 5, 6, 0),
	(183, 39, 6, 0),
	(186, 40, 6, 0),
	(189, 2, 6, 0),
	(195, 4, 7, 0),
	(198, 9, 7, 0),
	(201, 38, 7, 0),
	(204, 6, 7, 0),
	(207, 5, 7, 0),
	(210, 39, 7, 0),
	(213, 40, 7, 0),
	(216, 2, 7, 0),
	(228, 34, 6, 0),
	(230, 41, 2, 2),
	(231, 41, 6, 1),
	(233, 41, 3, 1),
	(235, 41, 5, 2),
	(236, 41, 7, 1),
	(237, 41, 8, 1),
	(238, 41, 4, 1),
	(241, 2, 8, 0),
	(244, 4, 8, 0);
/*!40000 ALTER TABLE `roles_menus` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.secciones
CREATE TABLE IF NOT EXISTS `secciones` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.secciones: ~1 rows (aproximadamente)
DELETE FROM `secciones`;
/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
INSERT INTO `secciones` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'seccion1', 'pbocchio', '2021-06-19 00:13:27');
/*!40000 ALTER TABLE `secciones` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.sectores
CREATE TABLE IF NOT EXISTS `sectores` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.sectores: ~1 rows (aproximadamente)
DELETE FROM `sectores`;
/*!40000 ALTER TABLE `sectores` DISABLE KEYS */;
INSERT INTO `sectores` (`codigo`, `descripcion`, `usuario_m`, `fecha_m`) VALUES
	(1, 'sector 1', 'pbocchio', '2021-06-19 00:13:22');
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

-- Volcando datos para la tabla giuliani.unidades: ~1 rows (aproximadamente)
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla giuliani.usuarios: ~1 rows (aproximadamente)
DELETE FROM `usuarios`;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`codigo`, `usuario`, `password`, `usuario_m`, `fecha_m`, `cargo`, `nombre`, `id_cliente`, `last_cliente`, `name`, `surname`, `mail`, `phone`, `reintentos`, `bloqueado`, `sistemas`, `id_rol`) VALUES
	(1, 'pbocchio', '827ccb0eea8a706c4c34a16891f84e7b', 'pbocchio', '2019-12-30', 'Administrador', 'Pablo Bocchio', NULL, 1, 'Pablo', 'Bocchio', 'pablobocchio@gmail.com', '3406411105', 0, 0, 1, 1),
	(2, 'giuliani', 'e36be0d03158c871e082da61a808bfaf', '1', '2021-08-09', NULL, 'giuliani giuliani', NULL, NULL, 'giuliani', 'giuliani', '-', NULL, NULL, NULL, NULL, 1),
	(3, 'gerencial', '9e55e9bc9b034364f1848bee6eb5402a', '2', '2021-08-09', NULL, 'gerencial gerencial', NULL, NULL, 'gerencial', 'gerencial', '-', NULL, NULL, NULL, NULL, 2),
	(4, 'calidad', 'd8911124a43dbc68e306f7d1420fdb54', '2', '2021-08-09', NULL, 'calidad calidad', NULL, NULL, 'calidad', 'calidad', '-', NULL, NULL, NULL, NULL, 3),
	(5, 'produccion', '6b9dac4cec5a347829b2a2241c458139', '2', '2021-08-09', NULL, 'produccion produccion', NULL, NULL, 'produccion', 'produccion', '-', NULL, NULL, NULL, NULL, 4),
	(6, 'ingenieria', '01bf897db0326f3ad5bdeff53a54f2e6', '2', '2021-08-09', NULL, 'ingenieria ingenieria', NULL, NULL, 'ingenieria', 'ingenieria', '-', NULL, NULL, NULL, NULL, 5),
	(7, 'compras', 'a82a2df07e8039ee4d5a1f46b3f03416', '2', '2021-08-09', NULL, 'compras compras', NULL, NULL, 'compras', 'compras', '-', NULL, NULL, NULL, NULL, 6),
	(8, 'logistica', 'e9a5390f6e432dc5149bb582def8a540', '2', '2021-08-09', NULL, 'logistica logistica', NULL, NULL, 'logistica', 'logistica', '-', NULL, NULL, NULL, NULL, 7),
	(9, 'planificacion', '87a069f623c33dc4e4e104fa4a3e0cfe', '2', '2021-08-09', NULL, 'planificacion planificacion', NULL, NULL, 'planificacion', 'planificacion', '-', NULL, NULL, NULL, NULL, 8);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.usuarios_destinos
CREATE TABLE IF NOT EXISTS `usuarios_destinos` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `destino_id` int(11) DEFAULT NULL,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.usuarios_destinos: ~0 rows (aproximadamente)
DELETE FROM `usuarios_destinos`;
/*!40000 ALTER TABLE `usuarios_destinos` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios_destinos` ENABLE KEYS */;

-- Volcando estructura para tabla giuliani.utils
CREATE TABLE IF NOT EXISTS `utils` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `valor` text,
  `usuario_m` text,
  `fecha_m` datetime DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla giuliani.utils: ~1 rows (aproximadamente)
DELETE FROM `utils`;
/*!40000 ALTER TABLE `utils` DISABLE KEYS */;
INSERT INTO `utils` (`codigo`, `descripcion`, `valor`, `usuario_m`, `fecha_m`) VALUES
	(1, 'texto_inicio', 'Un error detectado y resuelto en la oficina técnica cuesta 10 veces menos que<br /> resolverlo en el taller y 100 veces menos que resolverlo en obra.', 'pbocchio', '2021-06-15 23:44:57');
/*!40000 ALTER TABLE `utils` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
