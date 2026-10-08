ALTER TABLE productos
    ADD COLUMN prod_habilitado TINYINT(1) NOT NULL DEFAULT 1;
    
UPDATE productos SET prod_habilitado = 0 WHERE prod_integrado_ofima = 0;