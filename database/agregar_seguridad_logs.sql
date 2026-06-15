-- ============================================================
--  Vortex Epix - Módulo Seguridad + Logs (Danilo)
--  Ejecutar UNA VEZ en la base `supermercado`.
-- ============================================================

-- 1) Tabla de bitácora (registro de actividad)
CREATE TABLE IF NOT EXISTS `bitacora` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `id_empleado` INT NULL,
  `usuario` VARCHAR(50) NULL,
  `accion` VARCHAR(150) NULL,
  `modulo` VARCHAR(50) NULL,
  `metodo` VARCHAR(10) NULL,
  `ruta` VARCHAR(200) NULL,
  `ip` VARCHAR(45) NULL,
  `fecha` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) Empleados: columnas para la gestión de usuarios
ALTER TABLE `empleados`
  ADD COLUMN `estado` VARCHAR(20) NOT NULL DEFAULT 'Activo' AFTER `cargo`,
  ADD COLUMN `departamento` VARCHAR(100) NULL AFTER `estado`,
  ADD COLUMN `salario` DECIMAL(10,2) NULL AFTER `departamento`,
  ADD COLUMN `fecha_contratacion` DATE NULL AFTER `salario`;
