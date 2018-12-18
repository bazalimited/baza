<?php
$lang['config_info']='Guarda Información de Configuración';

$lang['config_address']='Dirección de la Compañía';
$lang['config_phone']='Teléfono de la Compañía';
$lang['config_website']='Sitio Web';
$lang['config_fax']='Fax';
$lang['config_default_tax_rate']='% de Impuestos Predeterminada';


$lang['config_company_required']='Nombre de la Compañía es requerido';

$lang['config_phone_required']='Teléfono de la Compañía es requerido';
$lang['config_default_tax_rate_required']='La Tasa de Impuestos Predeterminada es requerida';
$lang['config_default_tax_rate_number']='La Taza de Impuestos Predeterminada debe ser un número';
$lang['config_company_website_url']='Sitio Web no es un URL estándard (http://...)';
$lang['config_saved_successfully']='Configuración guardada correctamente';
$lang['config_saved_unsuccessfully']='Error al guardar la configuración. Los cambios de configuración no se permiten en el modo de demostración o los impuestos no se guardaron correctamente';
$lang['config_return_policy_required']='Política de Reintegro es requerida';
$lang['config_print_after_sale']='¿Imprimir recibo después de una venta?';


$lang['config_currency_symbol'] = 'Símbolo de moneda';
$lang['config_backup_database'] = 'Copia de seguridad de base de datos';
$lang['config_restore_database'] = 'Restaurar base de datos';

$lang['config_number_of_items_per_page'] = 'Número de artículos por página';
$lang['config_date_format'] = 'Formato de fecha';
$lang['config_time_format'] = 'Formato de hora';
$lang['config_company_logo'] = 'Logotipo de la empresa';
$lang['config_delete_logo'] = '¿Eliminar Logo?';

$lang['config_optimize_database'] = 'Optimizar base de datos';
$lang['config_database_optimize_successfully'] = 'Base de datos optimizada con éxito';
$lang['config_payment_types'] = 'Método de pago';
$lang['select_sql_file'] = 'selecciona sql';
$lang['restore_heading'] = 'Esto te permite restaurar la base de datos';

$lang['type_file'] = 'selecciona. sql desde tu computadora';

$lang['restore'] = 'restaurar';

$lang['required_sql_file'] = 'No existe el fichero SQL se selecciona';

$lang['restore_db_success'] = 'Base de datos se restaura con éxito';

$lang['db_first_alert'] = '¿Estás seguro que deseas restaurar la base de datos?';
$lang['db_second_alert'] = 'Los datos actuales se perderán, ¿continuar?';
$lang['password_error'] = 'Clave incorrecta';
$lang['password_required'] = 'Campo de clave no puede estar en blanco';
$lang['restore_database_title'] = 'Restaurar base de datos';
$lang['config_use_scale_barcode'] = 'El uso de códigos de barras de escala';

$lang['config_environment'] = 'medio ambiente';


$lang['config_sandbox'] = 'arenero';
$lang['config_production'] = 'producción';
$lang['disable_confirmation_sale']='¿Desactivar la confirmación de la venta completada?';



$lang['config_default_payment_type'] = 'Método de pago estándar';
$lang['config_speed_up_note'] = 'Solo recomendaría si usted tiene más de 10.000 artículos o clientes';
$lang['config_hide_signature'] = '¿Ocultar firma?';
$lang['config_automatically_email_receipt']='Envío automático correo electrónico al cliente';
$lang['config_barcode_price_include_tax']='Agregar IVA en la etiqueta impresa del código de barras';
$lang['config_round_cash_on_sales'] = 'Redondea a neares5 en receiptt .0 (Solo para Canada)';

$lang['config_prefix'] = 'Prefijo ID Venta';
$lang['config_sale_prefix_required'] = 'Prefijo Venta ID es un campo obligatorio';
$lang['config_customers_store_accounts'] = 'Permitir crédito en la tienda';
$lang['config_change_sale_date_when_suspending'] = 'Cambiar la fecha de venta al suspender la venta';
$lang['config_change_sale_date_when_completing_suspended_sale'] = 'Cambiar la fecha de venta al completar la venta suspendida';
$lang['config_price_tiers'] = 'Tiers de precios';
$lang['config_add_tier'] = 'Añadir tier';
$lang['config_show_receipt_after_suspending_sale'] = 'Mostrar recibo después de la suspensión de la venta';
$lang['config_backup_overview'] = 'Información general de copia de seguridad';
$lang['config_backup_overview_desc'] = 'Realizar copias de seguridad de sus datos es muy importante, pero puede ser un problema con la gran cantidad de datos. Si usted tiene un montón de imágenes, objetos y las ventas que esto puede aumentar el tamaño de su base de datos.';
$lang['config_backup_options'] = 'Ofrecemos muchas opciones de copia de seguridad para ayudarle a decidir cómo proceder';
$lang['config_backup_simple_option'] = 'Al hacer clic en &quot;base de datos de copia de seguridad&quot;. Este intentará descargar toda su base de datos a un archivo. Si recibe una pantalla en blanco o no puede descargar el archivo, pruebe una de las otras opciones.';
$lang['config_backup_phpmyadmin_1'] = 'PhpMyAdmin es una herramienta popular para la gestión de bases de datos. Si está utilizando la versión de descarga con el instalador, se puede acceder por ir a';
$lang['config_backup_phpmyadmin_2'] = 'Su nombre de usuario es root y la contraseña es lo que se utiliza durante la instalación inicial de PHP POS PDV. Una vez conectado, seleccione la base de datos desde el panel de la izquierda. A continuación, seleccione la exportación y luego enviar el formulario.';
$lang['config_backup_control_panel'] = 'Si ha instalado en su propio servidor que tiene un panel de control como Cpanel, busque el módulo de copia de seguridad que a menudo permitirá descargar copias de seguridad de su base de datos.';
$lang['config_backup_mysqldump'] = 'Si usted tiene acceso a la shell y mysqldump en su servidor, usted puede tratar de ejecutarlo haciendo clic en el enlace de abajo. De lo contrario, tendrá que probar otras opciones.';
$lang['config_mysqldump_failed'] = 'backup mysqldump ha fallado. Esto podría ser debido a una restricción servidor o el comando podría no estar disponible. Por favor intente otro método de copia de seguridad';



$lang['config_looking_for_location_settings'] = '¿Está buscando otras opciones de configuración? Ir a';
$lang['config_module'] = 'Módulo';
$lang['config_automatically_calculate_average_cost_price_from_receivings'] = 'Calcular el costo promedio de la compra';
$lang['config_averaging_method'] = 'Método de promedio';
$lang['config_historical_average'] = 'Promedio histórico';
$lang['config_moving_average'] = 'Media Móvil';

$lang['config_hide_dashboard_statistics'] = 'Ocultar del panel las estadísticas';
$lang['config_hide_store_account_payments_in_reports'] = 'Ocultar cuentas por pagar en en los informes de la tienda';
$lang['config_id_to_show_on_sale_interface'] = 'Columna a mostrar en la interfaz de Ventas';
$lang['config_auto_focus_on_item_after_sale_and_receiving'] = 'Posicionar el cursor en el campo del artículo en la interfaz de ventas y entradas';
$lang['config_automatically_show_comments_on_receipt'] = 'Mostrar Automáticamente Observaciones sobre recibo';
$lang['config_hide_customer_recent_sales'] = 'Ocultar ventas recientes para cliente';
$lang['config_spreadsheet_format'] = 'Formato de hoja de cálculo';
$lang['config_csv'] = 'CSV';
$lang['config_xlsx'] = 'XLSX';
$lang['config_disable_giftcard_detection'] = 'Desactivar detección de Terjetas de regalo';
$lang['config_disable_subtraction_of_giftcard_amount_from_sales'] = 'Deshabilitar substracción de tarjetas de regalo al utilizarla durante la venta';
$lang['config_always_show_item_grid'] = 'Mostrar siempre elemento de cuadrícula';
$lang['config_legacy_detailed_report_export'] = 'Legado Informe Detallado Excel Exportación';
$lang['config_print_after_receiving'] = 'Imprimir recibo después de recibir';
$lang['config_company_info'] = 'Información de la Compañía';
$lang['config_tax_currency_info'] = 'Impuestos y moneda';
$lang['config_sales_receipt_info'] = 'Ventas y Recibo';
$lang['config_suspended_sales_layaways_info'] = 'Suspendido Ventas / Layaways';
$lang['config_application_settings_info'] = 'Configuración de la aplicación';
$lang['config_hide_barcode_on_sales_and_recv_receipt'] = 'Ocultar código de barras en los recibos';
$lang['config_round_tier_prices_to_2_decimals'] = 'Tier precios Ronda a 2 decimales';
$lang['config_group_all_taxes_on_receipt'] = 'Grupo de todos los impuestos sobre la recepción';
$lang['config_receipt_text_size'] = 'Recibo el tamaño del texto';
$lang['config_small'] = 'Pequeño';
$lang['config_medium'] = 'Medio';
$lang['config_large'] = 'Grande';
$lang['config_extra_large'] = 'Extra grande';
$lang['config_select_sales_person_during_sale'] = 'Seleccionar persona de las ventas durante la venta';
$lang['config_default_sales_person'] = 'Persona de ventas por defecto';
$lang['config_require_customer_for_sale'] = 'Requerir al cliente para la venta';

$lang['config_hide_store_account_payments_from_report_totals'] = 'Ocultar pagos de la cuenta de la tienda totales del informe';
$lang['config_disable_sale_notifications'] = 'Desactivar las notificaciones de venta';
$lang['config_id_to_show_on_barcode'] = 'ID para mostrar en el código de barras';
$lang['config_currency_denoms'] = 'Denominaciones de moneda';
$lang['config_currency_value'] = 'Valor moneda';
$lang['config_add_currency_denom'] = 'Añadir denominación de la moneda';
$lang['config_enable_timeclock'] = 'Activar reloj horario';
$lang['config_change_sale_date_for_new_sale'] = 'Cambiar fecha Venta En Nueva venta';
$lang['config_dont_average_use_current_recv_price'] = 'No media, use precio recibido actual';
$lang['config_number_of_recent_sales'] = 'Número de ventas recientes por el cliente para mostrar';
$lang['config_hide_suspended_recv_in_reports'] = 'Ocultar Receivings suspendidos en los informes';
$lang['config_calculate_profit_for_giftcard_when'] = 'Calcular Tarjeta Regalo Beneficio Cuando';
$lang['config_selling_giftcard'] = 'La venta de tarjetas de regalo';
$lang['config_redeeming_giftcard'] = 'Tarjeta de regalo Canjeando';
$lang['config_remove_customer_contact_info_from_receipt'] = 'Eliminar información de contacto del cliente desde la recepción';
$lang['config_speed_up_search_queries'] = 'Acelerar las consultas de búsqueda?';




$lang['config_redirect_to_sale_or_recv_screen_after_printing_receipt'] = 'Redirigir a la venta o la recepción de pantalla después de imprimir el recibo';
$lang['config_enable_sounds'] = 'Activar sonidos para mensajes de estado';
$lang['config_charge_tax_on_recv'] = 'Cargue impuesto sobre receivings';
$lang['config_report_sort_order'] = 'Informe Orden de Clasificación';
$lang['config_asc'] = 'Lo antiguo primero';
$lang['config_desc'] = 'Lo nuevo primero';
$lang['config_do_not_group_same_items'] = 'No agrupar los elementos que son los mismos';
$lang['config_show_item_id_on_receipt'] = 'Mostrar ID de elemento en el recibo';
$lang['config_show_language_switcher'] = 'Mostrar Language Switcher';
$lang['config_do_not_allow_out_of_stock_items_to_be_sold'] = 'No permita que fuera de stock productos para ser vendidos';
$lang['config_number_of_items_in_grid'] = 'Número de artículos por la página en la rejilla';
$lang['config_edit_item_price_if_zero_after_adding'] = 'Editar precio del artículo si 0 después de añadir a la venta';
$lang['config_override_receipt_title'] = 'Ignorar título recibo';
$lang['config_automatically_print_duplicate_receipt_for_cc_transactions'] = 'Imprima automáticamente recibo duplicado para las transacciones con tarjeta de crédito';






$lang['config_default_type_for_grid'] = 'Tipo predeterminado para la red';
$lang['config_billing_is_managed_through_paypal'] = 'La facturación se gestiona a través de <a target="_blank" href="http://paypal.com">Paypal</a>. Usted puede cancelar su suscripción haciendo clic <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=BNTRX72M8UZ2E">aquí</a>. <a href="http://baza.rw/update_billing.php" target="_blank">Puede actualizar la facturación aquí</a>';
$lang['config_cannot_change_language'] = 'El idioma no se puede cambiar en la versión demo. Para intentar otro idioma crea un nuevo empleado y asígnale el idioma de su elección';
$lang['disable_quick_complete_sale'] = 'Desactivar la Venta Rápida';
$lang['config_fast_user_switching'] = 'Activar cambio rápido de usuario (la contraseña no es obligatoria)';
$lang['config_require_employee_login_before_each_sale'] = 'Exigir a los empleados loguearse antes de cada venta';
$lang['config_keep_same_location_after_switching_employee'] = 'Mantenga mismo lugar después de la conmutación empleado';
$lang['config_number_of_decimals'] = 'Número de decimales';
$lang['config_let_system_decide'] = 'Deje que el sistema decida (Recomendado)';
$lang['config_thousands_separator'] = 'Separador de miles';
$lang['config_legacy_search_method'] = 'Legado Método de búsqueda';
$lang['config_hide_store_account_balance_on_receipt'] = 'Ocultar tienda de saldo de la cuenta en el recibo';
$lang['config_decimal_point'] = 'Punto decimal';
$lang['config_hide_out_of_stock_grid'] = 'Ocultar fuera de stock productos en rejilla';
$lang['config_highlight_low_inventory_items_in_items_module'] = 'Resaltar elementos bajos de inventario en el módulo de artículos';
$lang['config_sort'] = 'Especie';
$lang['config_enable_customer_loyalty_system'] = 'Activar el sistema de fidelización de clientes';
$lang['config_spend_to_point_ratio'] = 'Pasa cantidad señalar relación';
$lang['config_point_value'] = 'Valor de punto';
$lang['config_hide_points_on_receipt'] = 'Ocultar Puntos en el recibo';
$lang['config_show_clock_on_header'] = 'Mostrar el reloj en la Cabecera';
$lang['config_show_clock_on_header_help_text'] = 'Esto es visible sólo en las pantallas anchas';
$lang['config_loyalty_explained_spend_amount'] = 'Ingrese la cantidad a gastar';
$lang['config_loyalty_explained_points_to_earn'] = 'Introduzca los puntos que se obtuvo';
$lang['config_simple'] = 'Sencillo';
$lang['config_advanced'] = 'Advanded';
$lang['config_loyalty_option'] = 'Opción Programa de Lealtad';
$lang['config_number_of_sales_for_discount'] = 'Número de ventas para el descuento';
$lang['config_discount_percent_earned'] = 'Por ciento de descuento ganó al alcanzar ventas';
$lang['hide_sales_to_discount_on_receipt'] = 'Ocultar ventas para descontar en el recibo';
$lang['config_hide_price_on_barcodes'] = 'Ocultar precio en los códigos de barras';
$lang['config_always_use_average_cost_method'] = 'Siempre Coste Uso Global Media Precio para una venta del articulo del precio de costo';
$lang['config_test_mode'] = 'Modo de prueba';
$lang['config_test_mode_help'] = 'Ventas NO guardan';
$lang['config_require_customer_for_suspended_sale'] = 'Requerir al cliente en venta suspendida';
$lang['config_default_new_items_to_service'] = 'Por defecto Nuevos productos como artículos de servicio';






$lang['config_prompt_for_ccv_swipe'] = 'Preguntar por el CCV al pasar la tarjeta de crédito';
$lang['config_disable_store_account_when_over_credit_limit'] = 'Cuenta de la tienda Desactivar cuando más del límite de crédito';
$lang['config_mailing_labels_type'] = 'Etiquetas para Envíos Formato';
$lang['config_phppos_session_expiration'] = 'Sesión de caducidad';
$lang['config_hours'] = 'Horas';
$lang['config_never'] = 'Nunca';
$lang['config_on_browser_close'] = 'Cerrar Navegador';
$lang['config_do_not_allow_below_cost'] = 'NO permita que los artículos que se venden por debajo del precio de coste';
$lang['config_store_account_statement_message'] = 'Tienda Estado de Cuenta Mensaje';
?>