<?php

function get_items_barcode_data($item_ids) {
    $CI = & get_instance();

    $hide_prices = $CI->config->item('hide_price_on_barcodes');

    $result = array();

    $item_ids = explode('~', $item_ids);
    foreach ($item_ids as $item_id) {
        $barcode_number = number_pad($item_id, 10);
        $item_info = $CI->Item->get_info($item_id);

        if ($id_to_show_on_barcode = $CI->config->item('id_to_show_on_barcode')) {
            if ($id_to_show_on_barcode == 'id') {
                $barcode_number = number_pad($item_id, 10);
            } elseif ($id_to_show_on_barcode == 'number') {
                $barcode_number = $item_info->item_number;
            } elseif ($id_to_show_on_barcode == 'product_id') {
                $barcode_number = $item_info->product_id;
            }
        }

        $item_location_info = $CI->Item_location->get_info($item_id);

        $today = strtotime(date('Y-m-d'));
        $is_item_location_promo = ($item_location_info->start_date !== NULL && $item_location_info->end_date !== NULL) && (strtotime($item_location_info->start_date) <= $today && strtotime($item_location_info->end_date) >= $today);
        $is_item_promo = ($item_info->start_date !== NULL && $item_info->end_date !== NULL) && (strtotime($item_info->start_date) <= $today && strtotime($item_info->end_date) >= $today);

        $regular_item_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;


        if ($is_item_location_promo) {
            $item_price = $item_location_info->promo_price;
        } elseif ($is_item_promo) {
            $item_price = $item_info->promo_price;
        } else {
            $item_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
        }
        $result[] = array('name' => $item_info->name , 'id' => $barcode_number);
        
    }
    return $result;
}

function get_price_for_item_excluding_taxes($item_id_or_line, $item_price_including_tax, $sale_id = FALSE, $receiving_id = FALSE) {
    $return = FALSE;
    $CI = & get_instance();

    if ($sale_id !== FALSE) {
        $tax_info = $CI->Sale->get_sale_items_taxes($sale_id, $item_id_or_line);
    } elseif ($receiving_id !== FALSE) {
        $tax_info = $CI->Receiving->get_receiving_items_taxes($receiving_id, $item_id_or_line);
    } else {
        $tax_info = $CI->Item_taxes_finder->get_info($item_id_or_line);
    }

    if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1) {
        $return = $item_price_including_tax / (1 + ($tax_info[0]['percent'] / 100) + ($tax_info[1]['percent'] / 100) + (($tax_info[0]['percent'] / 100) * (($tax_info[1]['percent'] / 100))));
    } else { //0 or more taxes NOT cumulative
        $total_tax_percent = 0;

        foreach ($tax_info as $tax) {
            $total_tax_percent+=$tax['percent'];
        }

        $return = $item_price_including_tax / (1 + ($total_tax_percent / 100));
    }

    if ($return !== FALSE) {
        return to_currency_no_money($return, 10);
    }

    return FALSE;
}

function get_price_for_item_including_taxes($item_id_or_line, $item_price_excluding_tax, $sale_id = FALSE, $receiving_id = FALSE) {
    $return = FALSE;
    $CI = & get_instance();
    if ($sale_id !== FALSE) {
        $tax_info = $CI->Sale->get_sale_items_taxes($sale_id, $item_id_or_line);
    } elseif ($receiving_id !== FALSE) {
        $tax_info = $CI->Receiving->get_receiving_items_taxes($receiving_id, $item_id_or_line);
    } else {
        $tax_info = $CI->Item_taxes_finder->get_info($item_id_or_line);
    }

    if (count($tax_info) == 2 && $tax_info[1]['cumulative'] == 1) {
        $first_tax = ($item_price_excluding_tax * ($tax_info[0]['percent'] / 100));
        $second_tax = ($item_price_excluding_tax + $first_tax) * ($tax_info[1]['percent'] / 100);
        $return = $item_price_excluding_tax + $first_tax + $second_tax;
    } else { //0 or more taxes NOT cumulative
        $total_tax_percent = 0;

        foreach ($tax_info as $tax) {
            $total_tax_percent+=$tax['percent'];
        }

        $return = $item_price_excluding_tax * (1 + ($total_tax_percent / 100));
    }


    if ($return !== FALSE) {
        return to_currency_no_money($return, 10);
    }

    return FALSE;
}

function get_commission_for_item($item_id, $price, $cost, $quantity, $discount) {
    $CI = & get_instance();
    $CI->load->library('sale_lib');

    $employee_id = $CI->sale_lib->get_sold_by_employee_id();
    $sales_person_info = $CI->Employee->get_info($employee_id);
    $employee_id = $CI->Employee->get_logged_in_employee_info()->person_id;
    $logged_in_employee_info = $CI->Employee->get_info($employee_id);

    $item_info = $CI->Item->get_info($item_id);

    if ($item_info->commission_fixed !== NULL) {
        return $quantity * $item_info->commission_fixed;
    } elseif ($item_info->commission_percent !== NULL) {
        $commission_percent_type = $item_info->commission_percent_type == 'profit' ? 'profit' : 'selling_price';

        if ($commission_percent_type == 'selling_price') {
            return to_currency_no_money(($price * $quantity - $price * $quantity * $discount / 100) * ($item_info->commission_percent / 100));
        } else { //Profit
            return to_currency_no_money((($price * $quantity - $price * $quantity * $discount / 100) - ($cost * $quantity)) * ($item_info->commission_percent / 100));
        }
    } elseif ($CI->config->item('select_sales_person_during_sale')) {
        if ($sales_person_info->commission_percent > 0) {
            $commission_percent_type = $sales_person_info->commission_percent_type == 'profit' ? 'profit' : 'selling_price';

            if ($commission_percent_type == 'selling_price') {
                return to_currency_no_money(($price * $quantity - $price * $quantity * $discount / 100) * ((float) ($sales_person_info->commission_percent) / 100));
            } else {
                return to_currency_no_money((($price * $quantity - $price * $quantity * $discount / 100) - ($cost * $quantity)) * ($sales_person_info->commission_percent / 100));
            }
        }

        $commission_percent_type = $CI->config->item('commission_percent_type') == 'profit' ? 'profit' : 'selling_price';

        if ($commission_percent_type == 'profit') {
            return to_currency_no_money((($price * $quantity - $price * $quantity * $discount / 100) - ($cost * $quantity)) * ((float) ($CI->config->item('commission_default_rate')) / 100));
        } else {
            return to_currency_no_money(($price * $quantity - $price * $quantity * $discount / 100) * ((float) ($CI->config->item('commission_default_rate')) / 100));
        }
    } elseif ($logged_in_employee_info->commission_percent > 0) {
        $commission_percent_type = $logged_in_employee_info->commission_percent_type == 'profit' ? 'profit' : 'selling_price';

        if ($commission_percent_type == 'selling_price') {
            return to_currency_no_money(($price * $quantity - $price * $quantity * $discount / 100) * ((float) ($logged_in_employee_info->commission_percent) / 100));
        } else {
            return to_currency_no_money((($price * $quantity - $price * $quantity * $discount / 100) - ($cost * $quantity)) * ($logged_in_employee_info->commission_percent / 100));
        }
    } else {
        $commission_percent_type = $CI->config->item('commission_percent_type') == 'profit' ? 'profit' : 'selling_price';

        if ($commission_percent_type == 'profit') {
            return to_currency_no_money((($price * $quantity - $price * $quantity * $discount / 100) - ($cost * $quantity)) * ((float) ($CI->config->item('commission_default_rate')) / 100));
        } else {
            return to_currency_no_money(($price * $quantity - $price * $quantity * $discount / 100) * ((float) ($CI->config->item('commission_default_rate')) / 100));
        }
    }
}

function cache_item_and_item_kit_cart_info($cart) {
    $CI = & get_instance();
    $item_ids = array();
    $item_kit_ids = array();

    foreach ($cart as $cart_item) {
        if (isset($cart_item['item_id'])) {
            $item_ids[] = $cart_item['item_id'];
        } elseif (isset($cart_item['item_kit_id'])) {
            $item_kit_ids[] = $cart_item['item_kit_id'];
        }
    }

    $CI->Item->get_info($item_ids);
    $CI->Item_kit->get_info($item_kit_ids);

    $CI->Item_location->get_info($item_ids, false, true);
    $CI->Item_kit_location->get_info($item_kit_ids, false, true);
}

?>