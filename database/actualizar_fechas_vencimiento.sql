-- ============================================================
--  Vortex Epix - Corrección de fechas de vencimiento (Steve)
--  Problema: la mayoría de los productos estaban caducados.
--  Este script les asigna fechas de vencimiento FUTURAS y variadas
--  (entre ~2 meses y ~2 años desde hoy), distintas para cada producto.
--  Ejecutar UNA VEZ en la base `supermercado`.
-- ============================================================

UPDATE `productos`
SET `fecha_vencimiento` = DATE_ADD(CURDATE(), INTERVAL (60 + (id_producto * 17) % 640) DAY);

-- Verificación (opcional): correr esto para confirmar que ya no hay caducados
-- SELECT id_producto, nombre, fecha_vencimiento
-- FROM productos
-- ORDER BY fecha_vencimiento;
