-- ============================================================
--  Vortex Epix - Módulo Dashboard + Configuración (Bryan Steve)
--  Crea la tabla de configuración del sistema. Ejecutar UNA VEZ.
-- ============================================================
CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre_empresa` VARCHAR(150) NOT NULL DEFAULT 'Vortex Epix',
  `direccion` VARCHAR(200) NULL,
  `telefono` VARCHAR(50) NULL,
  `correo` VARCHAR(100) NULL,
  `nit` VARCHAR(50) NULL,
  `iva_porcentaje` DECIMAL(5,2) NOT NULL DEFAULT 13.00,
  `moneda` VARCHAR(10) NOT NULL DEFAULT '$'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Fila inicial con los datos de la empresa
INSERT INTO `configuracion` (id, nombre_empresa, direccion, telefono, correo, nit, iva_porcentaje, moneda)
VALUES (1, 'Vortex Epix', 'Acajutla, Sonsonate, El Salvador', '7000-0000', 'contacto@vortexepix.com', '0000-000000-000-0', 13.00, '$')
ON DUPLICATE KEY UPDATE id = id;
