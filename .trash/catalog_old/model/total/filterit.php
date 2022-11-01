<?php

class ModelTotalFilterit extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        if (!isset($this->session->data['payment_method']['code'])) {
            return;
        } 

        $status = false;
        $title = '';
        $value = '';

        $settings = $this->config->get('filterit_payment');

        foreach (array('installed', 'created') as $section) {
            $modules = isset($settings[$section]) ? $settings[$section] : array();

            foreach ($modules as $module_code => $module_info) {
                if ($module_code == $this->session->data['payment_method']['code']) {
                    if ($section == 'created') {
                        $status = true;
                    } else {
                        $status = !empty($module_info['status']['subtotal']);
                    }

                    if (!empty($module_info['subtotal_percent'])) {
                        $value = $module_info['subtotal_percent'];
                    }

                    if (!empty($module_info['subtotal_value'])) {
                        $value = $module_info['subtotal_value'];
                    }

                    if (!empty($module_info['subtotal_text'])) {
                        $title = $module_info['subtotal_text'];
                    }
                }
            }
        }

        if (!$status || !$title || !$value) {
            return;
        }

        if (strpos($value, '%')) {
            $value = (float)$value * $total / 100;
        } else {
            $value = (float)$value;
        }

        $total_data[] = array(
            'code'       => 'filterit',
            'title'      => $title,
            'value'      => $value,
            'sort_order' => $this->config->get('filterit_sort_order')
        );

        $total += $value;
    }
}