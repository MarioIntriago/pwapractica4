CREATE DATABASE IF NOT EXISTS `01_calif` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `01_calif`;

DROP TABLE IF EXISTS `notas`;
DROP TABLE IF EXISTS `asignaturas_estudiante`;
DROP TABLE IF EXISTS `asignaturas`;
DROP TABLE IF EXISTS `lugares`;
DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `rol` int(1) DEFAULT NULL COMMENT '1 Docente, 2 Estudiante',
  `contrasena` varchar(100) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `lugares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `asignaturas_estudiante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lugar_id` int(11) DEFAULT NULL,
  `asignatura_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Estudiante',
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ae_lugar` (`lugar_id`),
  KEY `idx_ae_asignatura` (`asignatura_id`),
  KEY `idx_ae_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asignatura_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Estudiante',
  `parcial` int(1) DEFAULT NULL COMMENT '1 1er, 2 2do, 3 Mejoramiento',
  `teoria` float(6,2) DEFAULT NULL,
  `practica` float(6,2) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notas_asignatura` (`asignatura_id`),
  KEY `idx_notas_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios`
(`id`, `nombre`, `email`, `rol`, `contrasena`, `obs`, `usuario_id_creacion`, `fecha_creacion`, `hora_creacion`)
VALUES
(1, 'Docente Principal', 'docente@gmail.com', 1, '1234', 'Usuario docente de prueba', 1, NOW(), CURTIME()),
(2, 'Intriago Zambrano Mario José', 'mario@gmail.com', 2, '1234', 'Estudiante de prueba', 1, NOW(), CURTIME()),
(3, 'Cedeño Vera Ana María', 'ana@gmail.com', 2, '1234', 'Estudiante de prueba', 1, NOW(), CURTIME());

INSERT INTO `lugares`
(`id`, `nombre`, `obs`, `usuario_id_creacion`, `fecha_creacion`, `hora_creacion`)
VALUES
(1, 'Universidad Bolivariana del Ecuador', 'Lugar educativo principal', 1, NOW(), CURTIME()),
(2, 'Instituto Tecnológico Central', 'Lugar educativo secundario', 1, NOW(), CURTIME());

INSERT INTO `asignaturas`
(`id`, `nombre`, `obs`, `usuario_id_creacion`, `fecha_creacion`, `hora_creacion`)
VALUES
(1, 'Programación Web', 'HTML, CSS, JavaScript, PHP y MySQL', 1, NOW(), CURTIME()),
(2, 'Base de Datos', 'Diseño y consultas MySQL', 1, NOW(), CURTIME());

INSERT INTO `asignaturas_estudiante`
(`id`, `lugar_id`, `asignatura_id`, `usuario_id`, `usuario_id_creacion`, `fecha_creacion`, `hora_creacion`)
VALUES
(1, 1, 1, 2, 1, NOW(), CURTIME()),
(2, 1, 2, 2, 1, NOW(), CURTIME()),
(3, 2, 1, 3, 1, NOW(), CURTIME());

INSERT INTO `notas`
(`id`, `asignatura_id`, `usuario_id`, `parcial`, `teoria`, `practica`, `obs`, `usuario_id_creacion`, `fecha_creacion`, `hora_creacion`)
VALUES
(1, 1, 2, 1, 8.50, 9.00, 'Buen desempeño', 1, NOW(), CURTIME()),
(2, 2, 2, 1, 8.00, 8.70, 'Debe reforzar consultas SQL', 1, NOW(), CURTIME()),
(3, 1, 3, 1, 9.20, 9.50, 'Excelente participación', 1, NOW(), CURTIME());
