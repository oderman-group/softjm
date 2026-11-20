-- BDADMIN. Quita el ítem de menú Marcas (módulo 55 → usuarios/marcas.php).
-- Las marcas se administran dentro de Categorías. La página y sus permisos se conservan.

DELETE FROM modulos_empresa
WHERE mxe_id_modulo = 55;
