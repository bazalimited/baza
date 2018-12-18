<?php
$lang['config_info']='Informations sur la configuration du magasin'; 

$lang['config_address']='Adresse de la compagnie';
$lang['config_phone']='Téléphone de la compagnie'; 
$lang['config_website']='Site web';
$lang['config_fax']='Télécopieur';
$lang['config_default_tax_rate']='Pourcentage de taxe par défaut';


$lang['config_company_required']='Le champ Nom de compagnie est requis'; 

$lang['config_phone_required']='Le champ Téléphone est requis'; 
$lang['config_default_tax_rate_required']='Le champ Pourcentage taxe par défaut est requis'; 
$lang['config_default_tax_rate_number']='Le pourcentage de taxe par défaut doit être un chiffre ou un nombre'; 
$lang['config_company_website_url']='Le site web de la compagnie n\'est pas un URL valide (http://...)'; 
$lang['config_saved_successfully']='La configuration à été enregistrée avec succès';
$lang['config_saved_unsuccessfully']='La configuration n\'a pas été enregistrée';
$lang['config_return_policy_required']='Le champ Politique de retour est requis'; 
$lang['config_print_after_sale']='Imprimer le reçu après la vente'; 


$lang['config_currency_symbol'] = 'Symbole monétaire';
$lang['config_backup_database'] = 'Sauvegarder la base de données';
$lang['config_restore_database'] = 'Restaurer la base de données';

$lang['config_number_of_items_per_page'] = 'Nombre d\'articles par page';
$lang['config_date_format'] = 'Format de date';
$lang['config_time_format'] = 'Format de l\'heure';
$lang['config_company_logo'] = 'Logo de la compagnie';
$lang['config_delete_logo'] = 'Supprimer le logo';

$lang['config_optimize_database'] = 'Optimiser la base de données';
$lang['config_database_optimize_successfully'] = 'Base de données optimisée avec succès'; 
$lang['config_payment_types'] = 'Types de paiements';
$lang['select_sql_file'] = 'sélectionnez un fichier .sql';

$lang['restore_heading'] = 'Cela vous permet de restaurer votre base de données';

$lang['type_file'] = 'sélectionner un fichier .sql de votre ordinateur';

$lang['restore'] = 'restaurer';

$lang['required_sql_file'] = 'Aucun fichier .sql n\'a été sélectionné'; 

$lang['restore_db_success'] = 'Base de données restaurée avec succès'; 

$lang['db_first_alert'] = 'Êtes-vous sûr de vouloir restaurer la base de données?'; 
$lang['db_second_alert'] = 'Les données actuelles seront perdues, voulez-vous continuer?'; 
$lang['password_error'] = 'Mot de passe incorrect';
$lang['password_required'] = 'Le champ Mot de passe est requis';
$lang['restore_database_title'] = 'Restaurer la base de données';
$lang['config_use_scale_barcode'] = "Utilisez le code barre de la balance";

$lang['config_environment'] = 'Environnement';


$lang['config_sandbox'] = 'Bac à sable'; 
$lang['config_production'] = 'Production';
$lang['disable_confirmation_sale']='Désactiver la confirmation pour conclure la vente';




$lang['config_default_payment_type'] = 'Type de paiement par défaut';
$lang['config_speed_up_note'] = 'Recommandé uniquement si vous avez plus de 10000 articles ou clients';
$lang['config_hide_signature'] = 'Masquer la signature';
$lang['config_automatically_email_receipt']='Envoyer automatiquement le reçu par courriel';
$lang['config_barcode_price_include_tax']='Inclure les taxes sur les codes à barres?';
$lang['config_round_cash_on_sales'] = 'Arrondir à 0,05 lors de paiements comptant';
$lang['config_prefix'] = 'Préfixe d\'identification de la vente';
$lang['config_sale_prefix_required'] = 'Le champ Préfixe d\'identification de la vente est requis';
$lang['config_customers_store_accounts'] = 'Comptes clients ';
$lang['config_change_sale_date_when_suspending'] = 'Changer la date de la vente lors de la suspension';
$lang['config_change_sale_date_when_completing_suspended_sale'] = 'Changer la date de vente au moment de compléter la vente suspendue';
$lang['config_price_tiers'] = 'Niveaux de prix';
$lang['config_add_tier'] = 'Ajouter un niveau';
$lang['config_show_receipt_after_suspending_sale'] = 'Montrer le reçu après avoir suspendu la vente';
$lang['config_backup_overview'] = 'Aperçu de la sauvegarde';
$lang['config_backup_overview_desc'] = 'La sauvegarde de vos données est très importante, mais peut être difficile avec une grande quantité de données. Si vous avez beaucoup d\'images, d\'objets ou de ventes, cela peut augmenter la taille de votre base de données.';
$lang['config_backup_options'] = 'Nous offrons de nombreuses options de sauvegarde pour vous aider à décider comment procéder';
$lang['config_backup_simple_option'] = 'En cliquant sur Sauvgarder la base de données, le système va essayer de télécharger l\'ensemble de votre base dans un fichier. Si vous obtenez un écran blanc ou ne pouvez pas télécharger le fichier, essayez l\'une des autres options.';
$lang['config_backup_phpmyadmin_1'] = 'PHPMyAdmin est un outil populaire pour la gestion de vos bases des données. Si vous utilisez la version incluse avec l\'installateur, il est possible d\' y accéder en allant à';
$lang['config_backup_phpmyadmin_2'] = 'Le nom d\'utilisateur est root et le mot de passe est celui que vous avez utilisé lors de l\'installation initiale de PHP POS. Une fois connecté, sélectionnez votre base de données à partir du panneau sur la gauche. Ensuite, sélectionnez "export" et soumettre le formulaire.';
$lang['config_backup_control_panel'] = 'Si PHP POS à été installé sur un serveur disposant d\'un panneau de contrôle tel que cPanel, recherchez le module de sauvegarde qui vous permettra de télécharger des sauvegardes de votre base de données.';
$lang['config_backup_mysqldump'] = 'Si vous avez accès à l\'invite de commandes et mysqldump sur votre serveur, vous pouvez essayer de l\'exécuter en cliquant sur le lien ci-dessous. Sinon, vous devrez essayer d\'autres options.';
$lang['config_mysqldump_failed'] = 'La sauvegarde mysqldump a échoué. Cela pourrait être dû à une restriction du serveur ou la commande peut ne pas être disponible. S\'il vous plaît essayer une autre méthode de sauvegarde';



$lang['config_looking_for_location_settings'] = 'Vous cherchez d\'autres options de configuration? Allez à';
$lang['config_module'] = 'Module';
$lang['config_automatically_calculate_average_cost_price_from_receivings'] = 'Calculer automatiquement le prix moyen de la reception';
$lang['config_averaging_method'] = 'Méthode moyenne';
$lang['config_historical_average'] = 'Historique de la moyenne';
$lang['config_moving_average'] = 'Moyenne des deplacements';

$lang['config_hide_dashboard_statistics'] = 'Cachez les statistiques du tableau de bord';
$lang['config_hide_store_account_payments_in_reports'] = 'Masquer les paiements du compte magasin dans les rapports';
$lang['config_id_to_show_on_sale_interface'] = 'Afficher l\'ID d\'article sur l\'interface de vente';
$lang['config_auto_focus_on_item_after_sale_and_receiving'] = 'Focus automatique sur l\'article lorsque vous utilisez l\'interface ventes ou réception';
$lang['config_automatically_show_comments_on_receipt'] = 'Afficher automatiquement les commentaires sur le reçu';
$lang['config_hide_customer_recent_sales'] = 'Masquer les ventes récentes des clients';
$lang['config_spreadsheet_format'] = 'Format du tableau';
$lang['config_csv'] = 'CSV';
$lang['config_xlsx'] = 'XLSX';
$lang['config_disable_giftcard_detection'] = 'Désactiver la détection des carte cadeau';
$lang['config_disable_subtraction_of_giftcard_amount_from_sales'] = 'Désactiver la soustraction du montant de la carte cadeau lors de la vente';
$lang['config_always_show_item_grid'] = 'Toujours afficher la grille des articles';
$lang['config_legacy_detailed_report_export'] = 'Export un rapport détaillé Excel';
$lang['config_print_after_receiving'] = 'Imprimer un reçu après une réception';
$lang['config_company_info'] = 'Informations sur la société';
$lang['config_tax_currency_info'] = 'Taxes et devises';
$lang['config_sales_receipt_info'] = 'Ventes et réceptions';
$lang['config_suspended_sales_layaways_info'] = 'Ventes/mises de côté suspendues';
$lang['config_application_settings_info'] = 'Paramètres de l\'application';
$lang['config_hide_barcode_on_sales_and_recv_receipt'] = 'Cachez le code à barres sur les reçus';
$lang['config_round_tier_prices_to_2_decimals'] = 'Arrondir les niveaux de prix à 2 décimales';
$lang['config_group_all_taxes_on_receipt'] = 'Regrouper toutes les taxes sur le reçu';
$lang['config_receipt_text_size'] = 'Taille du texte sur le reçu';
$lang['config_small'] = 'Petit';
$lang['config_medium'] = 'Moyen';
$lang['config_large'] = 'Grand';
$lang['config_extra_large'] = 'Très grand';
$lang['config_select_sales_person_during_sale'] = 'Sélectionnez un vendeur lors de la vente';
$lang['config_default_sales_person'] = 'Vendeur par défaut';
$lang['config_require_customer_for_sale'] = 'Exiger la saisie d\'un client lors de la vente';

$lang['config_hide_store_account_payments_from_report_totals'] = 'Masquer les paiements du compte magasin des totaux des rapports';
$lang['config_disable_sale_notifications'] = 'Désactiver la notification de vente';
$lang['config_id_to_show_on_barcode'] = 'Afficher ID sur le code à barres';
$lang['config_currency_denoms'] = 'Coupures';
$lang['config_currency_value'] = 'Valeur de la devise';
$lang['config_add_currency_denom'] = 'Ajouter les coupures';
$lang['config_enable_timeclock'] = 'Activer l\'hodoraeur';
$lang['config_change_sale_date_for_new_sale'] = 'Modification de la date de vente pour les nouvelles ventes';
$lang['config_dont_average_use_current_recv_price'] = 'Ne pas faire une moyenne, utiliser le prix actuel';
$lang['config_number_of_recent_sales'] = 'Montrer les ventes récentes pour le client';
$lang['config_hide_suspended_recv_in_reports'] = 'Cacher les réceptions suspendues dans les rapports';
$lang['config_calculate_profit_for_giftcard_when'] = 'Calculer le bénéfice d\'une carte-cadeau lors';
$lang['config_selling_giftcard'] = 'Vente de cartes-cadeaux';
$lang['config_redeeming_giftcard'] = 'Encaisser des cartes-cadeaux';
$lang['config_remove_customer_contact_info_from_receipt'] = 'Retirer informations de contact client de la réception';
$lang['config_speed_up_search_queries'] = 'Accélérer les requêtes de recherche?';




$lang['config_redirect_to_sale_or_recv_screen_after_printing_receipt'] = 'Rediriger à la vente ou à l\'écran de réception après l\'impression réception';
$lang['config_enable_sounds'] = 'Activer les sons pour les messages d\'état';
$lang['config_charge_tax_on_recv'] = 'Facturer la taxe sur les réceptions';
$lang['config_report_sort_order'] = 'Ordre de tri du rapport';
$lang['config_asc'] = 'Plus ancien en premier';
$lang['config_desc'] = 'Plus récent en premier';
$lang['config_do_not_group_same_items'] = 'Ne pas regrouper les éléments qui sont les mêmes';
$lang['config_show_item_id_on_receipt'] = 'Voir l\'ID de l\'article à la réception';
$lang['config_show_language_switcher'] = 'Afficher le commutateur de langue';
$lang['config_do_not_allow_out_of_stock_items_to_be_sold'] = 'Ne autoriser la vente d\'items en rupture de stock';
$lang['config_number_of_items_in_grid'] = 'Nombre d\'articles par page dans la grille';
$lang['config_edit_item_price_if_zero_after_adding'] = 'Modifier prix de l\'objet si 0 après l\'ajout à la vente';
$lang['config_override_receipt_title'] = 'Remplacer le titre de la réception';
$lang['config_automatically_print_duplicate_receipt_for_cc_transactions'] = 'Imprimer automatiquement le reçu en double pour les transactions par carte de crédit';






$lang['config_default_type_for_grid'] = 'Type par défaut pour la grille';
$lang['config_billing_is_managed_through_paypal'] = 'La facturation est gérée par <a target="_blank" href="http://paypal.com">Paypal</a>. Vous pouvez annuler votre abonnement en cliquant <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=BNTRX72M8UZ2E">ici</a>. <a href="http://baza.rw/update_billing.php" target="_blank">Vous pouvez mettre à jour la facturation ici</a>';
$lang['config_cannot_change_language'] = 'La langue ne peut être modifié sur la démonstration. Pour essayer une autre langue créer un nouvel employé et lui attribuer la langue de votre choix';
$lang['disable_quick_complete_sale'] = 'Désactiver la vente rapide complète';
$lang['config_fast_user_switching'] = 'Activer la permutation rapide d\'utilisateur';
$lang['config_require_employee_login_before_each_sale'] = 'Exiger la connexion de l\'employé avant chaque vente';
$lang['config_keep_same_location_after_switching_employee'] = 'Gardez le même emplacement après avoir changé d\'employé';
$lang['config_number_of_decimals'] = 'Nombre de décimales';
$lang['config_let_system_decide'] = 'Laissez décider le système (recommandé)';
$lang['config_thousands_separator'] = 'Séparateur de milliers';
$lang['config_legacy_search_method'] = 'Moteur de recherche par défaut';
$lang['config_hide_store_account_balance_on_receipt'] = 'Cacher la balance du magasin à la réception';
$lang['config_decimal_point'] = 'Virgule';

$lang['config_hide_out_of_stock_grid'] = 'Cacher des articles en stock dans la grille';
$lang['config_highlight_low_inventory_items_in_items_module'] = 'Mettez en surbrillance faibles objets de l\'inventaire dans le module des articles';
$lang['config_sort'] = 'Genre';
$lang['config_enable_customer_loyalty_system'] = 'Activer le système de fidélisation de la clientèle';
$lang['config_spend_to_point_ratio'] = 'Passez montant à pointer rapport';
$lang['config_point_value'] = 'Valeur ponctuelle';
$lang['config_hide_points_on_receipt'] = 'Cacher Points à la réception';
$lang['config_show_clock_on_header'] = 'Afficher l\'horloge en tête';
$lang['config_show_clock_on_header_help_text'] = 'Ceci est visible seulement sur les écrans larges';
$lang['config_loyalty_explained_spend_amount'] = 'Entrez le montant à dépenser';
$lang['config_loyalty_explained_points_to_earn'] = 'Entrez les points à gagner';
$lang['config_simple'] = 'Simple';
$lang['config_advanced'] = 'Advanded';
$lang['config_loyalty_option'] = 'Option du programme de fidélisation';
$lang['config_number_of_sales_for_discount'] = 'Nombre de ventes pour une réduction';
$lang['config_discount_percent_earned'] = 'Pour cent de rabais obtenu en atteignant des ventes';
$lang['hide_sales_to_discount_on_receipt'] = 'Cacher les ventes à rabais à la réception';
$lang['config_hide_price_on_barcodes'] = 'Masquer prix sur les codes-barres';
$lang['config_always_use_average_cost_method'] = 'Toujours utiliser méthode du coût moyen global lors de la vente d\'un élément';
$lang['config_test_mode'] = 'Mode d\'essai';
$lang['config_test_mode_help'] = 'Requests pas enregistré';
$lang['config_require_customer_for_suspended_sale'] = 'Exiger client à vendre suspendu';
$lang['config_default_new_items_to_service'] = 'Par défaut Nouveaux Articles que les éléments de service';






$lang['config_prompt_for_ccv_swipe'] = 'Demander le CCV avant de faire glisser la carte de crédit';
$lang['config_disable_store_account_when_over_credit_limit'] = 'Compte sur le magasin Désactiver lorsque plus de limite de crédit';
$lang['config_mailing_labels_type'] = 'Mailing Labels Format';
$lang['config_phppos_session_expiration'] = 'Expiration de la session';
$lang['config_hours'] = 'Des heures';
$lang['config_never'] = 'Jamais';
$lang['config_on_browser_close'] = 'Sur Browser Fermer';
$lang['config_do_not_allow_below_cost'] = 'Ne laissez pas les articles destinés à être vendus en dessous du prix de revient';
$lang['config_store_account_statement_message'] = 'Magasin relevé de compte du message';
?>