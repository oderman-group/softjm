ALTER TABLE orioncrmcom_dev_jm_crm.proyectos MODIFY COLUMN proy_creada_fecha TIMESTAMP NOT NULL;

ALTER TABLE orioncrmcom_dev_crm_admin.modulos ADD mod_padre int(10) unsigned DEFAULT null NULL;