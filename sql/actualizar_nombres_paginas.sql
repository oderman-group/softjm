-- ================================================================
-- SCRIPT PARA ACTUALIZAR NOMBRES DESCRIPTIVOS DE PÁGINAS
-- Base de datos: orioncrmcom_dev_crm_admin_local
-- Autor: Sistema CRM
-- Fecha: 2025-10-22
-- Descripción: Actualiza los nombres de las páginas del sistema
--              para que sean más descriptivos y entendibles por el usuario
-- ================================================================

USE orioncrmcom_dev_crm_admin_local;

-- ================================================================
-- MÓDULO: GESTIÓN DE USUARIOS Y ROLES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Usuarios',
    pag_descripcion = 'Visualiza y gestiona todos los usuarios registrados en el sistema. Permite buscar, filtrar y acceder a las opciones de edición.'
WHERE pag_ruta LIKE '%usuarios.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nuevo Usuario',
    pag_descripcion = 'Registra un nuevo usuario en el sistema con sus datos personales, credenciales de acceso y asignación de rol.'
WHERE pag_ruta LIKE '%usuarios-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Usuario',
    pag_descripcion = 'Modifica la información de un usuario existente, incluyendo datos personales, rol asignado y estado de la cuenta.'
WHERE pag_ruta LIKE '%usuarios-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Usuario',
    pag_descripcion = 'Elimina permanentemente un usuario del sistema. Esta acción requiere confirmación y no puede deshacerse.'
WHERE pag_ruta LIKE '%bd_delete/usuarios-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Metas de Usuarios',
    pag_descripcion = 'Define y supervisa las metas de ventas o productividad asignadas a cada usuario del sistema.'
WHERE pag_ruta LIKE '%usuarios-metas.php';

UPDATE paginas SET 
    pag_nombre = 'Listado de Roles de Usuario',
    pag_descripcion = 'Administra los roles del sistema que determinan los permisos y accesos de los usuarios.'
WHERE pag_ruta LIKE '%roles.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nuevo Rol',
    pag_descripcion = 'Crea un nuevo rol personalizado con permisos específicos para diferentes áreas del sistema.'
WHERE pag_ruta LIKE '%roles-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Permisos de Rol',
    pag_descripcion = 'Modifica los permisos y accesos asignados a un rol existente. Gestiona qué páginas y funcionalidades puede usar cada rol.'
WHERE pag_ruta LIKE '%roles-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Rol',
    pag_descripcion = 'Elimina un rol del sistema. Los usuarios con este rol deberán ser reasignados a otro rol antes de eliminarlo.'
WHERE pag_ruta LIKE '%bd_delete/roles-eliminar.php';

-- ================================================================
-- MÓDULO: GESTIÓN DE CLIENTES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Clientes',
    pag_descripcion = 'Visualiza la base de datos completa de clientes. Permite buscar, filtrar por estado, tipo o ciudad, y acceder rápidamente a sus datos.'
WHERE pag_ruta LIKE '%clientes.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nuevo Cliente',
    pag_descripcion = 'Registra un nuevo cliente en la base de datos con toda su información comercial, contacto, ubicación y datos fiscales.'
WHERE pag_ruta LIKE '%clientes-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Cliente',
    pag_descripcion = 'Actualiza la información de un cliente existente, incluyendo datos de contacto, comerciales y documentos asociados.'
WHERE pag_ruta LIKE '%clientes-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Cliente',
    pag_descripcion = 'Muestra toda la información del cliente, historial de compras, cotizaciones, seguimientos y documentos relacionados.'
WHERE pag_ruta LIKE '%clientes-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Cliente',
    pag_descripcion = 'Elimina un cliente de la base de datos. Se recomienda verificar que no tenga documentos activos antes de eliminar.'
WHERE pag_ruta LIKE '%bd_delete/clientes-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Seguimiento de Clientes',
    pag_descripcion = 'Visualiza el historial completo de seguimientos, llamadas, reuniones y actividades realizadas con los clientes.'
WHERE pag_ruta LIKE '%clientes-seguimiento.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Seguimiento a Cliente',
    pag_descripcion = 'Registra una nueva actividad de seguimiento: llamada, reunión, visita, correo u otra interacción con el cliente.'
WHERE pag_ruta LIKE '%clientes-seguimiento-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Seguimiento de Cliente',
    pag_descripcion = 'Modifica la información de un seguimiento previamente registrado, actualiza notas, fechas y resultados.'
WHERE pag_ruta LIKE '%clientes-seguimiento-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Tickets de Clientes',
    pag_descripcion = 'Gestiona los tickets de soporte, quejas o solicitudes especiales de los clientes, con seguimiento de estados y resolución.'
WHERE pag_ruta LIKE '%clientes-tikets.php';

UPDATE paginas SET 
    pag_nombre = 'Contactos del Cliente',
    pag_descripcion = 'Administra las personas de contacto dentro de una empresa cliente: nombres, cargos, teléfonos y correos electrónicos.'
WHERE pag_ruta LIKE '%clientes-contactos.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Contacto a Cliente',
    pag_descripcion = 'Registra una nueva persona de contacto para un cliente, con su información completa y cargo en la empresa.'
WHERE pag_ruta LIKE '%clientes-contactos-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Contacto de Cliente',
    pag_descripcion = 'Actualiza la información de una persona de contacto existente: teléfono, correo, cargo o estado.'
WHERE pag_ruta LIKE '%clientes-contactos-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Empresas Cliente (ORION)',
    pag_descripcion = 'Gestiona las empresas cliente que utilizan el sistema ORION. Módulo especial para administración de clientes corporativos.'
WHERE pag_ruta LIKE '%clientes-orion.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Empresa Cliente ORION',
    pag_descripcion = 'Registra una nueva empresa cliente en el sistema ORION con su configuración personalizada y módulos asignados.'
WHERE pag_ruta LIKE '%clientes-orion-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Empresa Cliente ORION',
    pag_descripcion = 'Modifica la configuración de una empresa cliente ORION: módulos activos, usuarios permitidos y parámetros de sistema.'
WHERE pag_ruta LIKE '%clientes-orion-editar.php';

-- ================================================================
-- MÓDULO: GESTIÓN DE COTIZACIONES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Cotizaciones',
    pag_descripcion = 'Visualiza todas las cotizaciones creadas en el sistema. Permite filtrar por estado, cliente, fecha o vendedor asignado.'
WHERE pag_ruta LIKE '%cotizaciones.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nueva Cotización',
    pag_descripcion = 'Crea una nueva cotización para un cliente, agregando productos/servicios, cantidades, precios y condiciones comerciales.'
WHERE pag_ruta LIKE '%cotizaciones-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Cotización',
    pag_descripcion = 'Modifica una cotización existente: productos, cantidades, precios, descuentos, términos y condiciones de pago.'
WHERE pag_ruta LIKE '%cotizaciones-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Cotización',
    pag_descripcion = 'Muestra la información completa de una cotización: productos cotizados, totales, historial y estado actual.'
WHERE pag_ruta LIKE '%cotizaciones-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Cotización',
    pag_descripcion = 'Elimina una cotización del sistema. Se recomienda anular en lugar de eliminar para mantener historial.'
WHERE pag_ruta LIKE '%bd_delete/cotizaciones-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Cotizaciones Relacionadas',
    pag_descripcion = 'Muestra el historial de cotizaciones relacionadas o versiones de una cotización base para un mismo cliente.'
WHERE pag_ruta LIKE '%cotizaciones-relacionadas.php';

UPDATE paginas SET 
    pag_nombre = 'Generar Pedido desde Cotización',
    pag_descripcion = 'Convierte una cotización aprobada en un pedido de venta, arrastrando automáticamente los productos y valores.'
WHERE pag_ruta LIKE '%bd_create/cotizaciones-generar-pedido.php';

UPDATE paginas SET 
    pag_nombre = 'Duplicar Cotización',
    pag_descripcion = 'Crea una copia de una cotización existente para realizar modificaciones sin alterar el original.'
WHERE pag_ruta LIKE '%cotizaciones-duplicar.php';

UPDATE paginas SET 
    pag_nombre = 'Enviar Cotización por Correo',
    pag_descripcion = 'Envía la cotización por correo electrónico al cliente en formato PDF con mensaje personalizable.'
WHERE pag_ruta LIKE '%enviar_correos/cotizaciones-enviar-correo.php';

UPDATE paginas SET 
    pag_nombre = 'Imprimir Cotización (PDF)',
    pag_descripcion = 'Genera e imprime la cotización en formato PDF con el diseño estándar de la empresa.'
WHERE pag_ruta LIKE '%reportes/formato-cotizacion-pdf.php';

UPDATE paginas SET 
    pag_nombre = 'Formato Cotización 2 (PDF)',
    pag_descripcion = 'Genera la cotización en formato PDF con diseño alternativo 2, útil para diferentes tipos de clientes.'
WHERE pag_ruta LIKE '%reportes/formato-cotizacion-2_pdf.php';

UPDATE paginas SET 
    pag_nombre = 'Formato Cotización 3 (PDF)',
    pag_descripcion = 'Genera la cotización en formato PDF con diseño alternativo 3, personalizado para clientes corporativos.'
WHERE pag_ruta LIKE '%reportes/formato-cotizacion-3_pdf.php';

-- ================================================================
-- MÓDULO: GESTIÓN DE PRODUCTOS Y SERVICIOS
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Productos',
    pag_descripcion = 'Visualiza el catálogo completo de productos y servicios. Permite buscar, filtrar por categoría, marca o estado.'
WHERE pag_ruta LIKE '%productos.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nuevo Producto',
    pag_descripcion = 'Registra un nuevo producto o servicio en el catálogo con su descripción, precios, códigos y especificaciones técnicas.'
WHERE pag_ruta LIKE '%productos-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Producto',
    pag_descripcion = 'Modifica la información de un producto existente: precios, descripciones, imágenes, stock y características.'
WHERE pag_ruta LIKE '%productos-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Producto',
    pag_descripcion = 'Muestra toda la información del producto, historial de ventas, stock actual y movimientos de inventario.'
WHERE pag_ruta LIKE '%productos-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Producto',
    pag_descripcion = 'Elimina un producto del catálogo. Se recomienda inactivar en lugar de eliminar para mantener historial.'
WHERE pag_ruta LIKE '%bd_delete/productos-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Categorías de Productos',
    pag_descripcion = 'Administra las categorías y subcategorías del catálogo para organizar los productos de manera jerárquica.'
WHERE pag_ruta LIKE '%productos-categorias.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Categoría de Producto',
    pag_descripcion = 'Crea una nueva categoría o familia de productos para organizar mejor el catálogo comercial.'
WHERE pag_ruta LIKE '%productos-categorias-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Categoría de Producto',
    pag_descripcion = 'Modifica el nombre, descripción o categoría padre de una categoría existente.'
WHERE pag_ruta LIKE '%productos-categorias-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Importar Productos (Excel)',
    pag_descripcion = 'Importa productos masivamente desde un archivo Excel siguiendo la plantilla del sistema.'
WHERE pag_ruta LIKE '%productos-importar.php';

UPDATE paginas SET 
    pag_nombre = 'Exportar Productos (Excel)',
    pag_descripcion = 'Descarga el catálogo completo de productos en formato Excel para análisis o respaldo.'
WHERE pag_ruta LIKE '%productos-exportar.php';

-- ================================================================
-- MÓDULO: GESTIÓN DE REMISIONES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Remisiones',
    pag_descripcion = 'Visualiza todas las remisiones emitidas. Permite buscar por número, cliente, fecha o estado de entrega.'
WHERE pag_ruta LIKE '%remisiones.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nueva Remisión',
    pag_descripcion = 'Crea una remisión de productos para despacho al cliente, documentando las cantidades y referencias entregadas.'
WHERE pag_ruta LIKE '%remisiones-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Remisión',
    pag_descripcion = 'Modifica una remisión existente antes de ser procesada: productos, cantidades o información del despacho.'
WHERE pag_ruta LIKE '%remisiones-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Remisión',
    pag_descripcion = 'Muestra la información completa de una remisión: productos despachados, destinatario y estado de entrega.'
WHERE pag_ruta LIKE '%remisiones-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Remisión',
    pag_descripcion = 'Elimina una remisión del sistema si no ha sido procesada o facturada.'
WHERE pag_ruta LIKE '%bd_delete/remisiones-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Remisiones BDG',
    pag_descripcion = 'Gestión especial de remisiones para el sistema BDG con sus requerimientos particulares de entrega y documentación.'
WHERE pag_ruta LIKE '%remisionbdg.php';

UPDATE paginas SET 
    pag_nombre = 'Generar Factura desde Remisión',
    pag_descripcion = 'Convierte una remisión en factura de venta, facturando automáticamente los productos despachados.'
WHERE pag_ruta LIKE '%bd_create/remisionbdg-generar-factura.php';

UPDATE paginas SET 
    pag_nombre = 'Imprimir Remisión (PDF)',
    pag_descripcion = 'Genera e imprime la remisión en formato PDF para adjuntar al despacho de mercancía.'
WHERE pag_ruta LIKE '%reportes/formato-remision-pdf.php';

-- ================================================================
-- MÓDULO: GESTIÓN DE FACTURAS
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Facturas',
    pag_descripcion = 'Visualiza todas las facturas de venta emitidas. Permite filtrar por estado de pago, cliente, fecha o vendedor.'
WHERE pag_ruta LIKE '%facturas.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nueva Factura',
    pag_descripcion = 'Crea una nueva factura de venta con los productos, servicios vendidos, impuestos y condiciones de pago.'
WHERE pag_ruta LIKE '%facturas-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Factura',
    pag_descripcion = 'Modifica una factura existente antes de ser cerrada contablemente: productos, valores o información del cliente.'
WHERE pag_ruta LIKE '%facturas-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Factura',
    pag_descripcion = 'Muestra toda la información de la factura: productos facturados, pagos realizados y saldo pendiente.'
WHERE pag_ruta LIKE '%facturas-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Factura',
    pag_descripcion = 'Anula o elimina una factura del sistema. Se requiere autorización especial para facturas con movimientos.'
WHERE pag_ruta LIKE '%bd_delete/facturas-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Imprimir Factura (PDF)',
    pag_descripcion = 'Genera la factura en formato PDF con todos los requisitos legales para impresión o envío electrónico.'
WHERE pag_ruta LIKE '%reportes/formato-factura-pdf.php';

UPDATE paginas SET 
    pag_nombre = 'Enviar Factura por Correo',
    pag_descripcion = 'Envía la factura electrónica por correo al cliente con el PDF adjunto y mensaje personalizado.'
WHERE pag_ruta LIKE '%enviar_correos/facturas-enviar-correo.php';

-- ================================================================
-- MÓDULO: GESTIÓN DE PEDIDOS
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Pedidos',
    pag_descripcion = 'Visualiza todos los pedidos de venta registrados. Permite filtrar por estado, cliente, fecha de entrega o prioridad.'
WHERE pag_ruta LIKE '%pedidos.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nuevo Pedido',
    pag_descripcion = 'Registra un nuevo pedido de venta con productos, cantidades, fechas de entrega y condiciones comerciales.'
WHERE pag_ruta LIKE '%pedidos-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Pedido',
    pag_descripcion = 'Modifica un pedido existente: productos, cantidades, fechas de entrega o condiciones especiales del cliente.'
WHERE pag_ruta LIKE '%pedidos-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Pedido',
    pag_descripcion = 'Muestra la información completa del pedido: productos solicitados, estado de producción y fecha de entrega.'
WHERE pag_ruta LIKE '%pedidos-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Pedido',
    pag_descripcion = 'Elimina o anula un pedido del sistema. Verificar que no tenga remisiones o facturas asociadas.'
WHERE pag_ruta LIKE '%bd_delete/pedidos-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Imprimir Pedido (PDF)',
    pag_descripcion = 'Genera e imprime el pedido en formato PDF para enviarlo a producción o al área de despacho.'
WHERE pag_ruta LIKE '%reportes/formato-pedido-pdf.php';

-- ================================================================
-- MÓDULO: REPORTES Y ESTADÍSTICAS
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Dashboard Principal',
    pag_descripcion = 'Panel principal con indicadores clave de negocio, gráficos de ventas, alertas y accesos rápidos a las funciones más usadas.'
WHERE pag_ruta LIKE '%index.php';

UPDATE paginas SET 
    pag_nombre = 'Indicadores KPI',
    pag_descripcion = 'Visualiza los indicadores clave de rendimiento del negocio: ventas, conversión, cartera, rotación y cumplimiento de metas.'
WHERE pag_ruta LIKE '%kpi.php';

UPDATE paginas SET 
    pag_nombre = 'Reporte de Ventas',
    pag_descripcion = 'Genera reportes detallados de ventas por período, producto, cliente, vendedor o región con gráficos comparativos.'
WHERE pag_ruta LIKE '%reportes/reporte-ventas.php';

UPDATE paginas SET 
    pag_nombre = 'Reporte de Clientes',
    pag_descripcion = 'Analiza el comportamiento de clientes: compras, frecuencia, ticket promedio y clasificación por rentabilidad.'
WHERE pag_ruta LIKE '%reportes/reporte-clientes.php';

UPDATE paginas SET 
    pag_nombre = 'Reporte de Productos',
    pag_descripcion = 'Muestra estadísticas de productos: más vendidos, rotación de inventario, márgenes y productos con baja salida.'
WHERE pag_ruta LIKE '%reportes/reporte-productos.php';

UPDATE paginas SET 
    pag_nombre = 'Estadísticas Generales',
    pag_descripcion = 'Dashboard con estadísticas generales del sistema: ventas, compras, cartera, inventario y comparativos históricos.'
WHERE pag_ruta LIKE '%estadisticas.php';

UPDATE paginas SET 
    pag_nombre = 'Gráficos y Análisis',
    pag_descripcion = 'Herramienta de análisis con gráficos interactivos personalizables para tendencias y proyecciones de negocio.'
WHERE pag_ruta LIKE '%graficos.php';

UPDATE paginas SET 
    pag_nombre = 'Listado de Prospección',
    pag_descripcion = 'Administra el pipeline de ventas con clientes potenciales, estados de negociación y probabilidades de cierre.'
WHERE pag_ruta LIKE '%listado-prospeccion.php';

-- ================================================================
-- MÓDULO: GESTIÓN DEL SISTEMA
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Configuración General',
    pag_descripcion = 'Administra la configuración global del sistema: datos de empresa, parámetros de operación y preferencias generales.'
WHERE pag_ruta LIKE '%configuracion.php';

UPDATE paginas SET 
    pag_nombre = 'Gestión de Módulos',
    pag_descripcion = 'Administra los módulos del sistema, activa o desactiva funcionalidades y gestiona las secciones principales.'
WHERE pag_ruta LIKE '%modulos.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Módulo',
    pag_descripcion = 'Crea un nuevo módulo o sección principal en el sistema para organizar las funcionalidades por áreas.'
WHERE pag_ruta LIKE '%modulos-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Módulo',
    pag_descripcion = 'Modifica la configuración de un módulo: nombre, icono, descripción o módulo padre en la jerarquía.'
WHERE pag_ruta LIKE '%modulos-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Módulo',
    pag_descripcion = 'Elimina un módulo del sistema. Debe estar vacío de páginas antes de poder eliminarlo.'
WHERE pag_ruta LIKE '%bd_delete/modulos-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Gestión de Páginas',
    pag_descripcion = 'Administra todas las páginas del sistema: rutas, permisos, módulos asignados y visibilidad en menús.'
WHERE pag_ruta LIKE '%paginas.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Página',
    pag_descripcion = 'Registra una nueva página en el sistema asignándola a un módulo y configurando sus permisos de acceso.'
WHERE pag_ruta LIKE '%paginas-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Página',
    pag_descripcion = 'Modifica la configuración de una página: nombre, ruta, módulo, tipo CRUD o visibilidad en menú.'
WHERE pag_ruta LIKE '%paginas-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Página',
    pag_descripcion = 'Elimina una página del registro del sistema. Verificar que no esté en uso por ningún rol.'
WHERE pag_ruta LIKE '%bd_delete/paginas-eliminar.php';

UPDATE paginas SET 
    pag_nombre = 'Panel de Menú',
    pag_descripcion = 'Personaliza el menú de navegación: orden de módulos, visibilidad y organización jerárquica.'
WHERE pag_ruta LIKE '%panel-menu.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar Posición del Menú',
    pag_descripcion = 'Guarda los cambios de posición de los elementos del menú mediante arrastre y ordenamiento.'
WHERE pag_ruta LIKE '%bd_update/actualizar-posicion-menu.php';

UPDATE paginas SET 
    pag_nombre = 'Empresas del Sistema',
    pag_descripcion = 'Gestiona las empresas registradas en el sistema si es multi-empresa. Administra su configuración individual.'
WHERE pag_ruta LIKE '%empresas.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Empresa',
    pag_descripcion = 'Registra una nueva empresa en el sistema con su información comercial, fiscal y configuración específica.'
WHERE pag_ruta LIKE '%empresas-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Empresa',
    pag_descripcion = 'Modifica la información de una empresa: datos comerciales, fiscales, logo y parámetros de operación.'
WHERE pag_ruta LIKE '%empresas-editar.php';

-- ================================================================
-- MÓDULO: NOTIFICACIONES Y COMUNICACIONES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Lista de Notificaciones',
    pag_descripcion = 'Visualiza todas las notificaciones del sistema: alertas, recordatorios, vencimientos y avisos importantes.'
WHERE pag_ruta LIKE '%notificaciones-lista.php';

UPDATE paginas SET 
    pag_nombre = 'Calendario de Eventos',
    pag_descripcion = 'Calendario interactivo con seguimientos, reuniones, vencimientos y tareas programadas del equipo.'
WHERE pag_ruta LIKE '%calendario.php';

UPDATE paginas SET 
    pag_nombre = 'Modal de Calendario',
    pag_descripcion = 'Ventana emergente para crear o editar eventos en el calendario: reuniones, llamadas o recordatorios.'
WHERE pag_ruta LIKE '%calendario-modal.php';

UPDATE paginas SET 
    pag_nombre = 'Enviar Correos Masivos',
    pag_descripcion = 'Herramienta para enviar correos electrónicos masivos a clientes con plantillas personalizables y seguimiento.'
WHERE pag_ruta LIKE '%enviar-correos.php';

UPDATE paginas SET 
    pag_nombre = 'Plantillas de Correo',
    pag_descripcion = 'Administra las plantillas prediseñadas de correo electrónico para diferentes tipos de comunicación con clientes.'
WHERE pag_ruta LIKE '%plantillas-correo.php';

-- ================================================================
-- MÓDULO: MI PERFIL Y CONFIGURACIONES PERSONALES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Mi Perfil',
    pag_descripcion = 'Visualiza tu información personal, datos de contacto, rol asignado y actividad reciente en el sistema.'
WHERE pag_ruta LIKE '%perfil.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Mi Perfil',
    pag_descripcion = 'Modifica tu información personal: nombre, teléfono, correo, foto de perfil y preferencias de usuario.'
WHERE pag_ruta LIKE '%perfil-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Cambiar Mi Contraseña',
    pag_descripcion = 'Actualiza tu contraseña de acceso al sistema por seguridad. Requiere la contraseña actual para confirmar.'
WHERE pag_ruta LIKE '%cambiar-clave.php';

UPDATE paginas SET 
    pag_nombre = 'Mis Notificaciones',
    pag_descripcion = 'Centro de notificaciones personales: alertas, menciones, tareas asignadas y actualizaciones relevantes.'
WHERE pag_ruta LIKE '%mis-notificaciones.php';

UPDATE paginas SET 
    pag_nombre = 'Mis Tareas',
    pag_descripcion = 'Administra tus tareas pendientes, seguimientos asignados y actividades programadas con fechas límite.'
WHERE pag_ruta LIKE '%mis-tareas.php';

-- ================================================================
-- MÓDULO: OPERACIONES Y GESTIONES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Buscar Clientes (Fetch)',
    pag_descripcion = 'Herramienta de búsqueda rápida de clientes con autocompletado para agilizar la captura de información.'
WHERE pag_ruta LIKE '%fetch-buscar-clientes.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar Gestión',
    pag_descripcion = 'Procesa y guarda las gestiones comerciales realizadas con los clientes, actualizando su estado en el pipeline.'
WHERE pag_ruta LIKE '%guardar_gestion.php';

UPDATE paginas SET 
    pag_nombre = 'Editor de Texto',
    pag_descripcion = 'Editor de texto enriquecido para crear contenido con formato, ideal para correos, descripciones o documentos.'
WHERE pag_ruta LIKE '%texto-editor.php';

UPDATE paginas SET 
    pag_nombre = 'Historial de Acciones',
    pag_descripcion = 'Registro de auditoría con todas las acciones realizadas en el sistema: quién hizo qué y cuándo.'
WHERE pag_ruta LIKE '%historial-acciones.php';

UPDATE paginas SET 
    pag_nombre = 'Logs del Sistema',
    pag_descripcion = 'Registro técnico de eventos, errores y actividad del sistema para soporte técnico y depuración.'
WHERE pag_ruta LIKE '%logs.php';

-- ================================================================
-- MÓDULO: INVENTARIO Y ALMACÉN
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Inventario de Productos',
    pag_descripcion = 'Visualiza el stock actual de todos los productos, ubicaciones, cantidades disponibles y valores de inventario.'
WHERE pag_ruta LIKE '%inventario.php';

UPDATE paginas SET 
    pag_nombre = 'Movimientos de Inventario',
    pag_descripcion = 'Registra y consulta todos los movimientos de inventario: entradas, salidas, traslados y ajustes realizados.'
WHERE pag_ruta LIKE '%inventario-movimientos.php';

UPDATE paginas SET 
    pag_nombre = 'Ajuste de Inventario',
    pag_descripcion = 'Realiza ajustes al inventario por diferencias físicas, pérdidas, daños o correcciones de conteos.'
WHERE pag_ruta LIKE '%inventario-ajuste.php';

UPDATE paginas SET 
    pag_nombre = 'Reporte de Inventario',
    pag_descripcion = 'Genera reportes de inventario: valorización, rotación, productos bajo mínimo y análisis de stock.'
WHERE pag_ruta LIKE '%reportes/reporte-inventario.php';

-- ================================================================
-- MÓDULO: PROVEEDORES
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Proveedores',
    pag_descripcion = 'Visualiza la base de datos de proveedores. Permite buscar, filtrar y acceder a su información comercial.'
WHERE pag_ruta LIKE '%proveedores.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Proveedor',
    pag_descripcion = 'Registra un nuevo proveedor con su información comercial, contacto, productos que suministra y condiciones de pago.'
WHERE pag_ruta LIKE '%proveedores-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Proveedor',
    pag_descripcion = 'Actualiza la información de un proveedor: datos de contacto, condiciones comerciales o estado.'
WHERE pag_ruta LIKE '%proveedores-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Proveedor',
    pag_descripcion = 'Muestra toda la información del proveedor, historial de compras, pagos realizados y productos suministrados.'
WHERE pag_ruta LIKE '%proveedores-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Proveedor',
    pag_descripcion = 'Elimina un proveedor de la base de datos. Verificar que no tenga compras pendientes o cuentas por pagar.'
WHERE pag_ruta LIKE '%bd_delete/proveedores-eliminar.php';

-- ================================================================
-- MÓDULO: COMPRAS
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Listado de Compras',
    pag_descripcion = 'Visualiza todas las órdenes de compra y compras realizadas. Permite filtrar por proveedor, fecha o estado.'
WHERE pag_ruta LIKE '%compras.php';

UPDATE paginas SET 
    pag_nombre = 'Agregar Nueva Compra',
    pag_descripcion = 'Registra una nueva orden de compra a proveedor con productos, cantidades, precios y condiciones de entrega.'
WHERE pag_ruta LIKE '%compras-agregar.php';

UPDATE paginas SET 
    pag_nombre = 'Editar Compra',
    pag_descripcion = 'Modifica una orden de compra existente: productos, cantidades, precios o fechas de entrega.'
WHERE pag_ruta LIKE '%compras-editar.php';

UPDATE paginas SET 
    pag_nombre = 'Ver Detalle de Compra',
    pag_descripcion = 'Muestra toda la información de la compra: productos solicitados, estado de recepción y pagos realizados.'
WHERE pag_ruta LIKE '%compras-ver.php';

UPDATE paginas SET 
    pag_nombre = 'Eliminar Compra',
    pag_descripcion = 'Elimina o anula una orden de compra. Verificar que no tenga recepción de mercancía o pagos asociados.'
WHERE pag_ruta LIKE '%bd_delete/compras-eliminar.php';

-- ================================================================
-- MÓDULO: CUENTAS POR COBRAR
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Cuentas por Cobrar',
    pag_descripcion = 'Gestiona la cartera de clientes: facturas pendientes, vencidas, pagos parciales y gestión de cobro.'
WHERE pag_ruta LIKE '%cuentas-cobrar.php';

UPDATE paginas SET 
    pag_nombre = 'Registrar Pago',
    pag_descripcion = 'Registra los pagos recibidos de clientes contra facturas pendientes, con métodos de pago y comprobantes.'
WHERE pag_ruta LIKE '%cuentas-cobrar-pago.php';

UPDATE paginas SET 
    pag_nombre = 'Reporte de Cartera',
    pag_descripcion = 'Genera reportes de cartera: edades de saldo, clientes morosos, proyecciones de flujo y análisis de cobro.'
WHERE pag_ruta LIKE '%reportes/reporte-cartera.php';

-- ================================================================
-- MÓDULO: CUENTAS POR PAGAR
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Cuentas por Pagar',
    pag_descripcion = 'Administra las obligaciones con proveedores: facturas por pagar, vencimientos y programación de pagos.'
WHERE pag_ruta LIKE '%cuentas-pagar.php';

UPDATE paginas SET 
    pag_nombre = 'Registrar Pago a Proveedor',
    pag_descripcion = 'Registra pagos realizados a proveedores contra facturas pendientes, con métodos de pago y referencias.'
WHERE pag_ruta LIKE '%cuentas-pagar-pago.php';

UPDATE paginas SET 
    pag_nombre = 'Reporte de Cuentas por Pagar',
    pag_descripcion = 'Genera reportes de obligaciones: vencimientos próximos, proveedores, proyección de desembolsos y análisis de pagos.'
WHERE pag_ruta LIKE '%reportes/reporte-cuentas-pagar.php';

-- ================================================================
-- ACTUALIZAR PÁGINAS AJAX (Para referencia interna)
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'AJAX: Cargar Usuarios',
    pag_descripcion = 'Servicio interno AJAX que carga dinámicamente la información de usuarios para tablas y selectores.'
WHERE pag_ruta LIKE '%ajax/ajax-usuarios.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Cargar Clientes',
    pag_descripcion = 'Servicio interno AJAX para búsqueda y carga dinámica de información de clientes en formularios.'
WHERE pag_ruta LIKE '%ajax/ajax-clientes.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Cargar Productos',
    pag_descripcion = 'Servicio interno AJAX que proporciona información de productos para cotizaciones, pedidos y facturas.'
WHERE pag_ruta LIKE '%ajax/ajax-productos.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Cargar KPIs',
    pag_descripcion = 'Servicio interno AJAX que calcula y retorna los indicadores clave de rendimiento del negocio.'
WHERE pag_ruta LIKE '%ajax/ajax-kpis.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Notificaciones',
    pag_descripcion = 'Servicio interno AJAX que carga las notificaciones en tiempo real para el usuario activo.'
WHERE pag_ruta LIKE '%ajax/ajax-notificaciones.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Páginas por Módulo',
    pag_descripcion = 'Servicio interno AJAX que carga las páginas de un módulo específico para gestión de roles.'
WHERE pag_ruta LIKE '%ajax/ajax-paginas-modulos.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Todos los Módulos y Páginas',
    pag_descripcion = 'Servicio interno AJAX que retorna todos los módulos con sus páginas para gestión avanzada de roles.'
WHERE pag_ruta LIKE '%ajax/ajax-todos-modulos-paginas.php';

UPDATE paginas SET 
    pag_nombre = 'AJAX: Cargar Módulos',
    pag_descripcion = 'Servicio interno AJAX que carga dinámicamente la información de módulos del sistema.'
WHERE pag_ruta LIKE '%ajax/ajax-modulos.php';

-- ================================================================
-- ACTUALIZAR PÁGINAS DE ACTUALIZACIÓN (BD_UPDATE)
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Cliente',
    pag_descripcion = 'Procesa y guarda las modificaciones realizadas a la información de un cliente existente.'
WHERE pag_ruta LIKE '%bd_update/clientes-actualizar.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Usuario',
    pag_descripcion = 'Procesa y guarda los cambios realizados en la información de un usuario del sistema.'
WHERE pag_ruta LIKE '%bd_update/usuarios-actualizar.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Producto',
    pag_descripcion = 'Procesa y guarda las modificaciones realizadas en la ficha de un producto del catálogo.'
WHERE pag_ruta LIKE '%bd_update/productos-actualizar.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Cotización',
    pag_descripcion = 'Procesa y guarda los cambios realizados en una cotización: productos, precios o condiciones.'
WHERE pag_ruta LIKE '%bd_update/cotizaciones-actualizar.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Roles y Permisos',
    pag_descripcion = 'Procesa y guarda los cambios en permisos de roles: páginas asignadas y accesos permitidos.'
WHERE pag_ruta LIKE '%bd_update/actualizar-roles.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Seguimiento de Cliente',
    pag_descripcion = 'Procesa y guarda las modificaciones realizadas en un seguimiento o gestión de cliente.'
WHERE pag_ruta LIKE '%bd_update/clientes-seguimiento-actualizar.php';

UPDATE paginas SET 
    pag_nombre = 'Actualizar: Módulos de Cliente',
    pag_descripcion = 'Procesa y guarda los módulos activos asignados a un cliente ORION del sistema.'
WHERE pag_ruta LIKE '%bd_update/actualizar-modulos-cliente.php';

-- ================================================================
-- ACTUALIZAR PÁGINAS DE CREACIÓN (BD_CREATE)
-- ================================================================

UPDATE paginas SET 
    pag_nombre = 'Guardar: Nuevo Cliente',
    pag_descripcion = 'Procesa y registra un nuevo cliente en la base de datos con toda su información comercial.'
WHERE pag_ruta LIKE '%bd_create/clientes-guardar.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar: Nuevo Usuario',
    pag_descripcion = 'Procesa y registra un nuevo usuario en el sistema con credenciales y permisos asignados.'
WHERE pag_ruta LIKE '%bd_create/usuarios-guardar.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar: Nuevo Producto',
    pag_descripcion = 'Procesa y registra un nuevo producto en el catálogo con precios y especificaciones.'
WHERE pag_ruta LIKE '%bd_create/productos-guardar.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar: Nueva Cotización',
    pag_descripcion = 'Procesa y crea una nueva cotización en el sistema con productos, precios y condiciones.'
WHERE pag_ruta LIKE '%bd_create/cotizaciones-guardar.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar: Nuevo Rol',
    pag_descripcion = 'Procesa y crea un nuevo rol de usuario con sus permisos de acceso específicos.'
WHERE pag_ruta LIKE '%bd_create/roles-guardar.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar: Seguimiento de Cliente',
    pag_descripcion = 'Procesa y registra un nuevo seguimiento o gestión comercial realizada con un cliente.'
WHERE pag_ruta LIKE '%bd_create/clientes-seguimiento-guardar.php';

UPDATE paginas SET 
    pag_nombre = 'Guardar: Nueva Remisión',
    pag_descripcion = 'Procesa y crea una nueva remisión de productos para despacho al cliente.'
WHERE pag_ruta LIKE '%bd_create/remisiones-guardar.php';

-- ================================================================
-- FIN DEL SCRIPT
-- ================================================================

-- Verificar resultados
SELECT COUNT(*) as 'Páginas Actualizadas' FROM paginas WHERE pag_nombre NOT LIKE '%-%';
SELECT pag_id, pag_nombre, pag_ruta FROM paginas ORDER BY pag_id LIMIT 20;

