<?php
$lang['config_info']='Armazenar Informações de Configuração';

$lang['config_address']='Endereço da Empresa';
$lang['config_phone']='Telefone da Empresa';
$lang['config_prefix']='Prefixo de Venda ID';
$lang['config_website']='Website';
$lang['config_fax']='Fax';
$lang['config_default_tax_rate']='Padrão Taxa de Imposto %';


$lang['config_company_required']='Nome da empresa é um campo obrigatório';

$lang['config_phone_required']='Telefone da empresa é um campo obrigatório';
$lang['config_sale_prefix_required']='Prefixo Venda ID é um campo obrigatório';
$lang['config_default_tax_rate_required']='A alíquota padrão é um campo obrigatório';
$lang['config_default_tax_rate_number']='A alíquota padrão deve ser um número';
$lang['config_company_website_url']='Site da empresa não é uma URL válida (http://...)';
$lang['config_saved_successfully']='Configuração salvo com sucesso';
$lang['config_saved_unsuccessfully']='Falha ao salvar configuração. As alterações de configuração não são permitidos no modo de demonstração ou de impostos não foram salvos corretamente';
$lang['config_return_policy_required']='A política de retorno é um campo obrigatório';
$lang['config_print_after_sale']='Imprimir recibo após a venda';
$lang['config_automatically_email_receipt']='Automaticamente Email recebimento';
$lang['config_barcode_price_include_tax']='Incluir imposto sobre código de barras?';
$lang['disable_confirmation_sale']='Desativar confirmação para venda completa';


$lang['config_currency_symbol'] = 'Símbolo da moeda';
$lang['config_backup_database'] = 'Banco de dados de backup';
$lang['config_restore_database'] = 'Restaurar Database';

$lang['config_number_of_items_per_page'] = 'Número de Itens Por Página';
$lang['config_date_format'] = 'Formato de Data';
$lang['config_time_format'] = 'Formato de Hora';
$lang['config_company_logo'] = 'Logomarca';
$lang['config_delete_logo'] = 'Deletar Logomarca';

$lang['config_optimize_database'] = 'Otimizar Database';
$lang['config_database_optimize_successfully'] = 'Banco de dados otimizado com sucesso';
$lang['config_payment_types'] = 'Tipos de Pagamentos';
$lang['select_sql_file'] = 'selecionar arquivo .sql';

$lang['restore_heading'] = 'Isto permite a você restaurar o banco de dados';

$lang['type_file'] = 'selecionar arquivo .sql do seu computador';

$lang['restore'] = 'restaurar';

$lang['required_sql_file'] = 'Nenhum arquivo sql foi selecionado';

$lang['restore_db_success'] = 'Banco de dados foi restaurado com sucesso';

$lang['db_first_alert'] = 'Você tem certeza de restaurar o banco de dados?';
$lang['db_second_alert'] = 'Os presentes dados serão perdidos, continuar?';
$lang['password_error'] = 'Senha incorreta';
$lang['password_required'] = 'Campo de senha não pode estar em branco';
$lang['restore_database_title'] = 'Restaurar Banco de Dados';



$lang['config_environment'] = 'Ambiente';


$lang['config_sandbox'] = 'Caixa de areia';
$lang['config_production'] = 'Produção';

$lang['config_default_payment_type'] = 'Tipo de Pagamento Padrão';
$lang['config_speed_up_note'] = 'Só recomendo se você tem mais de 10.000 itens ou clientes';
$lang['config_hide_signature'] = 'Ocultar assinatura';
$lang['config_round_cash_on_sales'] = 'Arredondar para mais próximo .05 no recebimento';
$lang['config_customers_store_accounts'] = 'Conta de Clientes da Loja';
$lang['config_change_sale_date_when_suspending'] = 'Alterar data de venda ao suspender venda';
$lang['config_change_sale_date_when_completing_suspended_sale'] = 'Alterar data de venda ao completar a venda suspensa';
$lang['config_price_tiers'] = 'Faixas de preço';
$lang['config_add_tier'] = 'Adicionar Faixa de Preço';
$lang['config_show_receipt_after_suspending_sale'] = 'Mostrar recibo após suspender venda';
$lang['config_backup_overview'] = 'Visão geral do backup';
$lang['config_backup_overview_desc'] = 'Fazer o backup de seus dados é muito importante, mas pode ser problemático com grande quantidade de dados. Se você tem um monte de imagens, itens e vendas este pode aumentar o tamanho de seu banco de dados.';
$lang['config_backup_options'] = 'Nós oferecemos muitas opções de backup para ajudá-lo a decidir como proceder';
$lang['config_backup_simple_option'] = 'Ao clicar em "banco de dados de backup". Este tentará baixar todo o seu banco de dados para um arquivo. Se você receber uma tela em branco ou pode baixar o arquivo, tente uma das outras opções.';
$lang['config_backup_phpmyadmin_1'] = 'O phpMyAdmin é uma ferramenta popular para o gerenciamento de seus bancos de dados. Se você estiver usando a versão de download com o instalador, ele pode ser acessado indo até';
$lang['config_backup_phpmyadmin_2'] = 'Seu nome de usuário é root ea senha é o que você usou durante a instalação inicial do PHP POS. Uma vez conectado, selecione seu banco de dados a partir do painel do lado esquerdo. Em seguida, selecione a exportação e, em seguida, enviar o formulário.';
$lang['config_backup_control_panel'] = 'Se você tiver instalado no seu próprio servidor que tem um painel de controle, tais como cpanel, procure o módulo de backup que muitas vezes permitem que você baixar backups de seu banco de dados.';
$lang['config_backup_mysqldump'] = 'Se você tiver acesso ao shell e mysqldump em seu servidor, você pode tentar executá-lo clicando no link abaixo. Caso contrário, você terá de tentar outras opções.';
$lang['config_mysqldump_failed'] = 'de backup mysqldump falhou. Isto poderia ser devido a uma restrição do servidor ou o comando pode não estar disponível. Por favor, tente outro método de backup';



$lang['config_looking_for_location_settings'] = 'Procurando outras opções de configuração? Vá para';
$lang['config_module'] = 'Modulo';
$lang['config_automatically_calculate_average_cost_price_from_receivings'] = 'Calcular Custo Médio Preço de Recebimento';
$lang['config_averaging_method'] = 'Método da média';
$lang['config_historical_average'] = 'Média histórica';
$lang['config_moving_average'] = 'Média móvel';

$lang['config_hide_dashboard_statistics'] = 'Ocultar Painel Estatísticas';
$lang['config_hide_store_account_payments_in_reports'] = 'Esconder Loja Pagamentos Conta no Relatórios';
$lang['config_id_to_show_on_sale_interface'] = 'Item ID para mostrar na interface de vendas';
$lang['config_auto_focus_on_item_after_sale_and_receiving'] = 'Auto Focus No Campo Item Ao utilizar Vendas /Interfaces Recebimentos';
$lang['config_automatically_show_comments_on_receipt'] = 'Automaticamente Exibir Comentários no Recibo';
$lang['config_hide_customer_recent_sales'] = 'Esconder Vendas recentes para cliente';
$lang['config_spreadsheet_format'] = 'Formato da Planilha';
$lang['config_csv'] = 'CSV';
$lang['config_xlsx'] = 'XLSX';
$lang['config_disable_giftcard_detection'] = 'Desativar Detecção Cartão Presente';
$lang['config_disable_subtraction_of_giftcard_amount_from_sales'] = 'Desativar subtração Cartão Presente ao usar o mesmo durante a venda';
$lang['config_always_show_item_grid'] = 'Sempre Mostrar Grade de Item';
$lang['config_legacy_detailed_report_export'] = 'Legado Relatório Excel Export';
$lang['config_print_after_receiving'] = 'Imprimir recibo depois de receber';
$lang['config_company_info'] = 'Informações sobre a empresa';
$lang['config_tax_currency_info'] = 'Impostos e Taxas';
$lang['config_sales_receipt_info'] = 'Vendas e Recepção';
$lang['config_suspended_sales_layaways_info'] = 'Vendas suspendido / compras a prazo';
$lang['config_application_settings_info'] = 'Configurações do aplicativo';
$lang['config_hide_barcode_on_sales_and_recv_receipt'] = 'Ocultar código de barras em recibos';
$lang['config_round_tier_prices_to_2_decimals'] = 'Camada de Preços e volta para 2 casas decimais';
$lang['config_group_all_taxes_on_receipt'] = 'Grupo de todos os impostos sobre o recebimento';
$lang['config_receipt_text_size'] = 'Recebimento tamanho do texto';
$lang['config_small'] = 'Pequeno';
$lang['config_medium'] = 'Médio';
$lang['config_large'] = 'Grande';
$lang['config_extra_large'] = 'Extra grande';
$lang['config_select_sales_person_during_sale'] = 'Escolha um vendedor durante a venda';
$lang['config_default_sales_person'] = 'Vendas Padrão pessoa';
$lang['config_require_customer_for_sale'] = 'Exigir cliente para venda';

$lang['config_hide_store_account_payments_from_report_totals'] = 'Esconder os pagamentos da conta da loja de totais do relatório';
$lang['config_disable_sale_notifications'] = 'Desativar notificações venda';
$lang['config_id_to_show_on_barcode'] = 'ID para mostrar no código de barras';
$lang['config_currency_denoms'] = 'Denominações monetárias';
$lang['config_currency_value'] = 'Valor Moeda';
$lang['config_add_currency_denom'] = 'Adicionar denominação da moeda';
$lang['config_enable_timeclock'] = 'Habilite Time Clock';
$lang['config_change_sale_date_for_new_sale'] = 'Mudança Venda Data Para Venda Nova';
$lang['config_dont_average_use_current_recv_price'] = 'Não média, utilize atual preço recebido';
$lang['config_number_of_recent_sales'] = 'Número de vendas recentes por cliente para mostrar';
$lang['config_hide_suspended_recv_in_reports'] = 'Esconder Recebimento suspensas em relatórios';
$lang['config_calculate_profit_for_giftcard_when'] = 'Calcule Gift Card Lucro Quando';
$lang['config_selling_giftcard'] = 'Vendendo Gift Card';
$lang['config_redeeming_giftcard'] = 'Gift Card Redeeming';
$lang['config_remove_customer_contact_info_from_receipt'] = 'Remover informações de contato do cliente a partir do recebimento';
$lang['config_speed_up_search_queries'] = 'Acelerar as consultas de pesquisa?';




$lang['config_redirect_to_sale_or_recv_screen_after_printing_receipt'] = 'Redirecionar para venda ou recebimento de tela após a impressão do recibo';
$lang['config_enable_sounds'] = 'Ativar sons para mensagens de status';
$lang['config_charge_tax_on_recv'] = 'Cobrar imposto sobre recebimentos';
$lang['config_report_sort_order'] = 'Relatório de Ordem';
$lang['config_asc'] = 'Mais antigo primeiro';
$lang['config_desc'] = 'Mais recentes primeiro';
$lang['config_do_not_group_same_items'] = 'Não itens de grupo que são os mesmos';
$lang['config_show_item_id_on_receipt'] = 'Mostrar id item no recibo';
$lang['config_show_language_switcher'] = 'Mostrar Língua Switcher';
$lang['config_do_not_allow_out_of_stock_items_to_be_sold'] = 'Não permita que fora de stock artigos para serem vendidos';
$lang['config_number_of_items_in_grid'] = 'Número de itens por página na rede';
$lang['config_edit_item_price_if_zero_after_adding'] = 'Editar preço do item se 0 depois de adicionar o direito de venda';
$lang['config_override_receipt_title'] = 'Título recebimento Override';
$lang['config_automatically_print_duplicate_receipt_for_cc_transactions'] = 'Imprimir automaticamente o recebimento duplicado para transações com cartão de crédito';






$lang['config_default_type_for_grid'] = 'Tipo padrão para Grelha';
$lang['config_billing_is_managed_through_paypal'] = 'Faturamento é gerido através de <a target="_blank" href="http://paypal.com">Paypal</a>. Você pode cancelar sua inscrição clicando <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=BNTRX72M8UZ2E">aqui</a>. <a href="http://baza.rw/update_billing.php" target="_blank">Você pode atualizar de faturamento aqui</a>';
$lang['config_cannot_change_language'] = 'A linguagem não pode ser alterado na demo. Para tentar outro idioma criar um novo funcionário e atribuir-lhes uma língua de sua escolha';
$lang['disable_quick_complete_sale'] = 'Desativar venda rápida completa';
$lang['config_fast_user_switching'] = 'Ativar troca rápida de usuário (senha não é necessária)';
$lang['config_require_employee_login_before_each_sale'] = 'Exigir o acesso de empregado antes de cada venda';
$lang['config_keep_same_location_after_switching_employee'] = 'Mantenha mesmo local após a mudança empregado';
$lang['config_number_of_decimals'] = 'Número de casas decimais';
$lang['config_let_system_decide'] = 'Vamos decidir sistema (Recomendado)';
$lang['config_thousands_separator'] = 'Milhares Separator';
$lang['config_legacy_search_method'] = 'Legado Método de Pesquisa';
$lang['config_hide_store_account_balance_on_receipt'] = 'Ocultar armazenamento de conta do saldo no momento da recepção';
$lang['config_decimal_point'] = 'Ponto decimal';
$lang['config_hide_out_of_stock_grid'] = 'Esconder fora de stock artigos em grade';
$lang['config_highlight_low_inventory_items_in_items_module'] = 'Destaque os itens de estoque baixos em itens módulo';
$lang['config_sort'] = 'Tipo';
$lang['config_enable_customer_loyalty_system'] = 'Habilitar sistema de Fidelização de Clientes';
$lang['config_spend_to_point_ratio'] = 'Passe quantidade para apontar rácio';
$lang['config_point_value'] = 'Valor de ponto';
$lang['config_hide_points_on_receipt'] = 'Esconder Pontos no recebimento';
$lang['config_show_clock_on_header'] = 'Mostrar hora no cabeçalho';
$lang['config_show_clock_on_header_help_text'] = 'Isto é visível apenas em telas de largura';
$lang['config_loyalty_explained_spend_amount'] = 'Digite o valor para gastar';
$lang['config_loyalty_explained_points_to_earn'] = 'Digite pontos a serem auferidos';
$lang['config_simple'] = 'Simples';
$lang['config_advanced'] = 'Advanded';
$lang['config_loyalty_option'] = 'Opção Programa de Fidelidade';
$lang['config_number_of_sales_for_discount'] = 'Número de vendas para desconto';
$lang['config_discount_percent_earned'] = 'Por cento de desconto ganhou ao atingir vendas';
$lang['hide_sales_to_discount_on_receipt'] = 'Esconder as vendas de desconto sobre o recebimento';
$lang['config_hide_price_on_barcodes'] = 'Ocultar preço em códigos de barras';
$lang['config_always_use_average_cost_method'] = 'Sempre Uso Global Custo médio preço de um item de Venda Preço de custo';
$lang['config_test_mode'] = 'Modo de teste';
$lang['config_test_mode_help'] = 'As vendas não salva';
$lang['config_require_customer_for_suspended_sale'] = 'Exigir do cliente para a venda suspensa';
$lang['config_default_new_items_to_service'] = 'Padrão novos itens como itens de serviço';






$lang['config_prompt_for_ccv_swipe'] = 'Solicitar CCV quando swiping cartão de crédito';
$lang['config_disable_store_account_when_over_credit_limit'] = 'Desativar conta da loja, quando mais de limite de crédito';
$lang['config_mailing_labels_type'] = 'Mailing Labels Format';
$lang['config_phppos_session_expiration'] = 'Expiração da sessão';
$lang['config_hours'] = 'Horas';
$lang['config_never'] = 'Nunca';
$lang['config_on_browser_close'] = 'No ecrã Fechar';
$lang['config_do_not_allow_below_cost'] = 'Não permitir que os itens a serem vendidos abaixo do preço de custo';
$lang['config_store_account_statement_message'] = 'Loja Mensagem Extrato de Conta';
?>