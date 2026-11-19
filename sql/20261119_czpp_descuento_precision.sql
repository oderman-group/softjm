-- BD: MAIN
-- Sin dependencia de otro script del mismo día.
-- Más decimales en el porcentaje para conservar el valor de descuento digitado al céntimo.

ALTER TABLE cotizacion_productos
    MODIFY czpp_descuento DECIMAL(12,8) NULL DEFAULT 0.00;
