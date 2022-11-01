<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple.php');

class SimpleCheckout extends Simple {
    protected static $_instance;

    private $_errors = array();
    private $_blocked = false;
    private $_redirectUrl = '';
    private $_reservedBlockNames = array(
        '{three_column}',
        '{/three_column}',
        '{left_column}',
        '{/left_column}',
        '{right_column}',
        '{/right_column}',
        '{step}',
        '{/step}',
        '{customer}',
        '{payment_address}',
        '{shipping_address}',
        '{cart}',
        '{shipping}',
        '{payment}',
        '{agreement}',
        '{help}',
        '{summary}',
        '{comment}',
        '{payment_form}'
    );

    protected function __construct($registry, $settingsId = 0) {
        $this->setPage('checkout');
        parent::__construct($registry, $settingsId);
    }

    public static function getInstance($registry, $settingsId = 0) {
        if (self::$_instance === null) {
            self::$_instance = new self($registry, $settingsId);
        }

        return self::$_instance;
    }

    public function setRedirectUrl($url) {
        $this->_redirectUrl = $url;
    }

    public function getRedirectUrl() {
        return $this->_redirectUrl;
    }

    public function isGuestCheckoutDisabled() {
        return $this->getSettingValue('guestCheckoutDisabled');
    }

    public function clearPreventDeleteFlag() {
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && !isset($this->session->data['order_id']) && isset($this->session->data['prevent_delete'])) {
            unset($this->session->data['prevent_delete']);
        }
    }

    public function setPreventDeleteFlag() {
        $order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
        $this->session->data['prevent_delete'][$order_id] = true;
    }

    public function isCustomerCombinedWithShippingAddress() {
        return $this->getSettingValue('combined', 'shippingAddress') && !$this->getSettingValue('combined', 'paymentAddress');
    }

    public function isCustomerCombinedWithPaymentAddress() {
        return !$this->getSettingValue('combined', 'shippingAddress') && $this->getSettingValue('combined', 'paymentAddress');
    }

    public function clearOrder() {
        if (isset($this->session->data['order_id']) && !isset($this->session->data['prevent_delete'][$this->session->data['order_id']]) && !isset($this->session->data['prevent_delete'][0])) {
            $order_id = $this->session->data['order_id'];
            $version = $this->getOpencartVersion();

            $order_pending = $this->cache->get('order_pending');

            if (!isset($order_pending)) {
                $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order_pending'");
                $order_pending = $query->rows ? true : false;
                $this->cache->set('order_pending', $order_pending);
            }

            $this->db->query("SET SQL_BIG_SELECTS=1");
            $this->db->query("DELETE
                                    `" . DB_PREFIX . "order`,
                                    " . DB_PREFIX . "order_product,
                                    " . DB_PREFIX . "order_history,
                                    " . DB_PREFIX . "order_option,
                                    " . ($version < 300 ? (DB_PREFIX . "affiliate_transaction,") : "") . "
                                    " . ($version < 200 ? (DB_PREFIX . "order_download,") : "") . "
                                    " . DB_PREFIX . "order_total"
                                    . ($version >= 152 ? "," . DB_PREFIX . "order_voucher" : "") .
                                    ($version >= 152 && $version < 203 ? "," . DB_PREFIX . "order_fraud" : "") .
                            " FROM
                                `" . DB_PREFIX . "order`
                            LEFT JOIN
                                " . DB_PREFIX . "order_product
                            ON
                                " . DB_PREFIX . "order_product.order_id = `" . DB_PREFIX . "order`.order_id
                            LEFT JOIN
                                " . DB_PREFIX . "order_history
                            ON
                                " . DB_PREFIX . "order_history.order_id = `" . DB_PREFIX . "order`.order_id"
                            . ($version < 300 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "affiliate_transaction
                            ON
                                " . DB_PREFIX . "affiliate_transaction.order_id = `" . DB_PREFIX . "order`.order_id" : "")."
                            LEFT JOIN
                                " . DB_PREFIX . "order_option
                            ON
                                " . DB_PREFIX . "order_option.order_id = `" . DB_PREFIX . "order`.order_id"
                            . ($version < 200 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "order_download
                            ON
                                " . DB_PREFIX . "order_download.order_id = `" . DB_PREFIX . "order`.order_id" : "")."
                            LEFT JOIN
                                " . DB_PREFIX . "order_total
                            ON
                                " . DB_PREFIX . "order_total.order_id = `" . DB_PREFIX . "order`.order_id "
                            . ($version >= 152 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "order_voucher
                            ON
                                " . DB_PREFIX . "order_voucher.order_id = `" . DB_PREFIX . "order`.order_id" : "")
                            . ($version >= 152 && $version < 203 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "order_fraud
                            ON
                                " . DB_PREFIX . "order_fraud.order_id = `" . DB_PREFIX . "order`.order_id" : "")
                            . ($order_pending ? " LEFT JOIN
                                " . DB_PREFIX . "order_pending
                            ON
                                " . DB_PREFIX . "order_pending.order_id = `" . DB_PREFIX . "order`.order_id" : "") .
                            " WHERE
                                `" . DB_PREFIX . "order`.order_id = '" . (int)$order_id . "'
                            AND
                                `" . DB_PREFIX . "order`.order_status_id = 0");

            if ($this->db->countAffected() > 0) {
                $this->db->query("SET insert_id = " . (int)$order_id);
            }

            unset($this->session->data['order_id']);
        }
    }

    public function getPaymentDisplayType() {
        $type = $this->getSettingValue('displayType', 'payment');
        return in_array($type, array(1,2)) ? $type : 1;
    }

    public function getShippingDisplayType() {
        $type = $this->getSettingValue('displayType', 'shipping');
        return in_array($type, array(1,2)) ? $type : 1;
    }

    public function isPaymentBeforeShipping() {
        return $this->getSettingValue('paymentBeforeShipping');
    }

    public function getModules() {
        $replaces = array();
        foreach ($this->_reservedBlockNames as $name) {
            $replaces[$name] = '';
        }

        $template = trim(str_replace($this->_reservedBlockNames, $replaces, $this->getTemplate()), '{}');

        return explode('}{', $template);
    }

    public function isOrderBlocked() {
        return $this->_blocked;
    }

    public function getErrors() {
        return $this->_errors;
    }

    private function getSteps() {
        $search = array('{three_column}',
                        '{/three_column}',
                        '{left_column}',
                        '{/left_column}',
                        '{right_column}',
                        '{/right_column}',
                        '{step}',
                        '{/step}');

        $replace = array('{three_column}' => '',
                        '{/three_column}' => '',
                        '{left_column}' => '',
                        '{/left_column}' => '',
                        '{right_column}' => '',
                        '{/right_column}' => '',
                        '{step}' => '',
                        '{/step}' => '');

        $steps = $this->getSettingValue('steps');

        if (!empty($steps) && is_array($steps)) {
            $result = array();
            foreach ($steps as $key => $info) {
                $countOfBlocks = 0;
                $countOfHiddenBlocks = 0;

                if (!empty($info['template'])) {
                    $template = str_replace($search, $replace, $info['template']);

                    $tmp = explode('{', $template);

                    foreach ($tmp as $block) {
                        if (!$block) {
                            continue;
                        }

                        $countOfBlocks++;

                        $block = trim($block, '{}');

                        if ($this->isBlockHidden($block)) {
                            $countOfHiddenBlocks++;
                        }
                    }
                }

                if ($countOfBlocks > $countOfHiddenBlocks) {
                    $result[$key] = $info;
                }
            }

            $steps = $result;
        }

        return $steps;
    }

    public function getTemplate($full = false) {
        $steps = $full ? $this->getSettingValue('steps') : $this->getSteps();
        $template = '';

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                if (!empty($info['template'])) {
                    $template .= '{step}'.$info['template'].'{/step}';
                }
            }
        }

        return $template;
    }

    public function hasBlock($name) {
        return strpos($this->getTemplate(true), '{'.$name.'}') !== false ? true : false;
    }

    public function isBlockHidden($block) {
        if ($this->hasBlock($block)) {
            if (($block == 'shipping_address' || $block == 'shipping') && !$this->cart->hasShipping()) {
                return true;
            }
            
            $hidden = $this->customer->isLogged() ? $this->getSettingValue('hideForLogged', $block) : $this->getSettingValue('hideForGuest', $block);

            if (!$hidden) {
                if ($block == 'shipping_address') {
                    $hideForMethods = $this->getSettingValue('hideForMethods', $block);

                    if (!empty($hideForMethods) && is_array($hideForMethods)) {
                        $shippingMethod = isset($this->session->data['shipping_method']) ? $this->session->data['shipping_method'] : array();

                        if (!empty($shippingMethod) && !empty($shippingMethod['code'])) {
                            foreach ($hideForMethods as $hideShippingCode => $value) {
                                if ($value) {
                                    if (preg_match($this->convertMaskToRegexp($hideShippingCode), $shippingMethod['code'])) {
                                        $hidden = true;
                                    }
                                }
                            }
                        }
                    }

                    $hideForMethods = $this->getSettingValue('hideForPaymentMethods', $block);

                    if (!empty($hideForMethods) && is_array($hideForMethods)) {
                        $paymentMethod = isset($this->session->data['payment_method']) ? $this->session->data['payment_method'] : array();

                        if (!empty($paymentMethod) && !empty($paymentMethod['code']) && !empty($hideForMethods[$paymentMethod['code']])) {
                            $hidden = true;
                        }
                    }
                } else if ($block == 'payment_address') {
                    $hideForMethods = $this->getSettingValue('hideForMethods', $block);

                    if (!empty($hideForMethods) && is_array($hideForMethods)) {
                        $paymentMethod = isset($this->session->data['payment_method']) ? $this->session->data['payment_method'] : array();

                        if (!empty($paymentMethod) && !empty($paymentMethod['code']) && !empty($hideForMethods[$paymentMethod['code']])) {
                            $hidden = true;
                        }
                    }

                    $hideForMethods = $this->getSettingValue('hideForShippingMethods', $block);

                    if (!empty($hideForMethods) && is_array($hideForMethods)) {
                        $shippingMethod = isset($this->session->data['shipping_method']) ? $this->session->data['shipping_method'] : array();

                        if (!empty($shippingMethod) && !empty($shippingMethod['code'])) {
                            foreach ($hideForMethods as $hideShippingCode => $value) {
                                if ($value) {
                                    if (preg_match($this->convertMaskToRegexp($hideShippingCode), $shippingMethod['code'])) {
                                        $hidden = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $hidden = true;
        }

        return $hidden ? true : false;
    }

    public function addError($block) {
        $this->_errors[] = $block;
    }

    public function hasError($block) {
        return in_array($block, $this->_errors) ? true : false;
    }

    public function blockOrder() {
        $this->_blocked = true;
    }

    public function getBlockStepNumber($block) {
        $stepsCount = $this->getStepsCount();

        if ($stepsCount == 1) {
            return 1;
        }

        if (!$block || $block == 'common') {
            return $stepsCount-1;
        }

        $steps = $this->getSteps();

        $step = 1;

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                if (!empty($info['template']) && strpos($info['template'], '{'.$block.'}') !== false) {
                    return $step;
                }
                $step++;
            }
        }

        return $step;
    }

    public function displayError($block = '') {
        if (isset($this->request->post['create_order'])) {
            return true;
        }

        if (isset($this->request->post['next_step']) && $this->request->post['next_step'] > $this->getBlockStepNumber($block)) {
            return true;
        }

        return false;
    }

    public function canCreateOrder() {
        $result = false;
        $asap = $this->customer->isLogged() ? $this->getSettingValue('asapForLogged') : $this->getSettingValue('asapForGuests');

        if ($this->getStepsCount() == 1) {
            if ($asap) {
                $result = true;
            } else if (isset($this->request->post['create_order'])) {
                $result = true;
            }
        } else {
            if ($asap) {
                if (isset($this->request->post['next_step']) && $this->request->post['next_step'] == $this->getStepsCount()) {
                    $result = true;
                }
            } else if (isset($this->request->post['create_order'])) {
                $result = true;
            }
        }

        return $result;
    }

    public function getStepsCount() {
        $steps = $this->getSteps();

        if (!empty($steps) && is_array($steps)) {
            return count($steps);
        }

        return 1;
    }

    public function getStepsNames() {
        $steps = $this->getSteps();

        $result = array();
        $lc = $this->getCurrentLanguageCode();

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                $label = $key;
                if (!empty($info['label'][$lc])) {
                    $label = $info['label'][$lc];
                }
                $result[] = $label;
            }
        }

        return $result;
    }

    public function getStepsButtons() {
        $steps = $this->getSteps();

        $result = array();
        $lc = $this->getCurrentLanguageCode();

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                if (!empty($info['buttonNext'][$lc])) {
                    $result[$key+1] = $info['buttonNext'][$lc];
                }
                
            }
        }

        return $result;
    }

    public function getComment() {
        $blocks = array('customer', 'payment_address', 'payment', 'shipping_address', 'shipping');
        $objects = array('customer', 'address', 'order');
        $langCode = $this->getCurrentLanguageCode();
        $result = array();

        if (!empty($this->session->data['simple']['comment'])) {
            $result[] = $this->session->data['simple']['comment'];
        }

        foreach ($blocks as $block) {
            foreach ($objects as $obj) {
                $blockUsedFields = $this->getUsedFields($block, $obj);

                foreach ($blockUsedFields as $field) {
                    $fieldSettings = $this->getFieldSettings($field);

                    if (!$fieldSettings['custom']) {
                        continue;
                    }

                    if (!empty($fieldSettings['saveToComment'])) {
                        $value = isset($this->session->data['simple'][$block][$fieldSettings['id']]) ? $this->session->data['simple'][$block][$fieldSettings['id']] : '';

                        if (in_array($fieldSettings['type'], array('radio','select','checkbox')) && !empty($this->_values[$block][$fieldSettings['id']]) && is_array($this->_values[$block][$fieldSettings['id']])) {
                            if (is_array($value)) {
                                $tmp = array();

                                foreach ($this->_values[$block][$fieldSettings['id']] as $info) {
                                    if (array_key_exists($info['id'], $value) && !empty($value[$info['id']])) {
                                        $tmp[] = $info['text'];
                                    }
                                }

                                $value = implode(', ', $tmp);
                            } else {
                                foreach ($this->_values[$block][$fieldSettings['id']] as $info) {
                                    if ($value == $info['id']) {
                                        $value = $info['text'];
                                        break;
                                    }
                                }
                            }
                        }

                        if (!empty($value)) {
                            $label = trim(strip_tags($fieldSettings['label'][$langCode]));
                            if (!empty($label)) {
                                $value = $fieldSettings['label'][$langCode].': '.$value;
                            }

                            $result[] = $value;
                        }
                    }
                }
            }
        }

        return implode(', ', $result);
    }

    public function getShippingStubs() {
        $methods = $this->getSettingValue('methods', 'shipping');
        $displayTitles = $this->getSettingValue('displayTitles', 'shipping');

        $lc = $this->getCurrentLanguageCode();
        $result = array();

        if (empty($methods)) {
            return array();
        }

        foreach($methods as $method) {
            if (empty($method['code'])) {
                continue;
            }
            
            $use = false;
            $result[$method['code']] = array(
                'code'       => $method['code'],
                'title'      => $displayTitles ? (!empty($method['title'][$lc]) ? $method['title'][$lc] : $method['code']) : '',
                'dummy'      => true,
                'sort_order' => isset($method['sortOrder']) ? $method['sortOrder'] : $this->config->get($method['code'].'_sort_order'),
                'quote'      => array()
            );

            if (!empty($method['methods']) && is_array($method['methods'])) {
                foreach ($method['methods'] as $submethod) {
                    if (strpos($submethod['code'], '*')) {
                        continue;
                    }

                    if (!empty($submethod['display'])) {
                        $use = true;
                        $tmp = explode('.', $submethod['code']);
                        $result[$method['code']]['quote'][$tmp[1]] = array(
                            'code'        => $submethod['code'],
                            'dummy'       => true,
                            'title'       => !empty($submethod['title'][$lc]) ? $submethod['title'][$lc] : $submethod['code'],
                            'description' => !empty($submethod['description'][$lc]) ? $submethod['description'][$lc] : '',
                            'sort_order'  => isset($submethod['sortOrder']) ? $submethod['sortOrder'] : -1,
                            'text'        => ''
                        );
                    }
                }
            }

            if (!$use) {
                unset($result[$method['code']]);
            }
        }

        return $result;
    }

    public function displayShippingMethodForEmptyAddress($code) {
        $methods = $this->getSettingValue('methods', 'shipping');

        if (!empty($methods) && is_array($methods) && !empty($methods[$code]['wait'])) {
            return false;
        }

        return true;
    }

    public function prepareShippingMethods($quote) {
        $methods       = $this->getSettingValue('methods', 'shipping');
        $checkedMethod = isset($quote['code']) && !empty($methods[$quote['code']]) ? $methods[$quote['code']] : array();
        $lc            = $this->getCurrentLanguageCode();
        $result        = $quote;
        $displayTitles = $this->getSettingValue('displayTitles', 'shipping');

        $groupId = $this->session->data['simple']['customer']['customer_group_id'];

        $all = '';
        if (!empty($methods) && isset($quote['code'])) {
            $tmp = explode('.', $quote['code']);
            $all = $tmp[0].'.*';

            $checkedMethodForAll = !empty($methods[$quote['code']]['methods'][$all]) ? $methods[$quote['code']]['methods'][$all] : array();

            if (!empty($checkedMethodForAll)) {
                if (empty($checkedMethodForAll['forAllGroups']) && !empty($checkedMethodForAll['forGroups']) && empty($checkedMethodForAll['forGroups'][$groupId])) {
                    return array();
                }

                if (!empty($checkedMethodForAll['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
                    return array();
                }

                if (!empty($checkedMethodForAll['hideForStatuses']['logged']) && $this->customer->isLogged()) {
                    return array();
                }

                if ($this->isPaymentBeforeShipping() && empty($checkedMethodForAll['forAllMethods']) && !empty($checkedMethodForAll['forMethods'])) {
                    $paymentMethod = isset($this->session->data['payment_method']) ? $this->session->data['payment_method'] : array();;
                    if (!empty($paymentMethod['code']) && empty($checkedMethodForAll['forMethods'][$paymentMethod['code']])) {
                        return array();
                    }
                }
            }
        }

        if (empty($checkedMethod)) {
            if (!$displayTitles) {
                unset($result['title']);
            }

            return $result;
        }

        if (!empty($checkedMethod['useTitle']) && !empty($checkedMethod['title'][$lc])) {
            $result['title'] = $checkedMethod['title'][$lc];
        }

        if (!is_array($quote['quote'])) {
            return $quote;
        }

        foreach ($quote['quote'] as $code => $info) {
            $checkedSubmethod = !empty($checkedMethod['methods'][$info['code']]) ? $checkedMethod['methods'][$info['code']] : array();

            if (empty($checkedSubmethod)) {
                continue;
            }

            $checkedSubmethodMask = array();

            foreach ($checkedMethod['methods'] as $mask => $maskSettings) {
                if (strpos($mask, '*') && preg_match($this->convertMaskToRegexp($mask), $info['code']) && !empty($maskSettings)) {
                    $checkedSubmethodMask = $maskSettings;
                }
            }

            if (!empty($checkedSubmethod['useTitle']) && !empty($checkedSubmethod['title'][$lc])) {
                $result['quote'][$code]['title'] = $checkedSubmethod['title'][$lc];
            }

            if (!empty($checkedSubmethod['useDescription']) && !empty($checkedSubmethod['description'][$lc])) {
                $result['quote'][$code]['description'] = $checkedSubmethod['description'][$lc];
            }

            if (empty($checkedSubmethod['forAllGroups']) && !empty($checkedSubmethod['forGroups']) && empty($checkedSubmethod['forGroups'][$groupId])) {
                unset($result['quote'][$code]);
            }

            if (empty($checkedSubmethodMask['forAllGroups']) && !empty($checkedSubmethodMask['forGroups']) && empty($checkedSubmethodMask['forGroups'][$groupId])) {
                unset($result['quote'][$code]);
            }

            if (!empty($checkedSubmethod['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
                unset($result['quote'][$code]);
            }

            if (!empty($checkedSubmethodMask['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
                unset($result['quote'][$code]);
            }

            if (!empty($checkedSubmethod['hideForStatuses']['logged']) && $this->customer->isLogged()) {
                unset($result['quote'][$code]);
            }

            if (!empty($checkedSubmethodMask['hideForStatuses']['logged']) && $this->customer->isLogged()) {
                unset($result['quote'][$code]);
            }

            if ($this->isPaymentBeforeShipping() && empty($checkedSubmethod['forAllMethods']) && !empty($checkedSubmethod['forMethods'])) {
                $paymentMethod = isset($this->session->data['payment_method']) ? $this->session->data['payment_method'] : array();;
                if (!empty($paymentMethod['code']) && empty($checkedSubmethod['forMethods'][$paymentMethod['code']])) {
                    unset($result['quote'][$code]);
                }
            }

            if ($this->isPaymentBeforeShipping() && empty($checkedSubmethodMask['forAllMethods']) && !empty($checkedSubmethodMask['forMethods'])) {
                $paymentMethod = isset($this->session->data['payment_method']) ? $this->session->data['payment_method'] : array();;
                if (!empty($paymentMethod['code']) && empty($checkedSubmethodMask['forMethods'][$paymentMethod['code']])) {
                    unset($result['quote'][$code]);
                }
            }
        }

        if (empty($result['quote'])) {
            return array();
        }

        if (!$displayTitles) {
            unset($result['title']);
        }

        return $result;
    }

    public function getPaymentStubs() {
        $methods = $this->getSettingValue('methods', 'payment');
        $lc = $this->getCurrentLanguageCode();
        $result = array();

        if (empty($methods)) {
            return array();
        }

        foreach($methods as $method) {
            if (!empty($method['display'])) {
                $result[$method['code']] = array(
                    'code'        => $method['code'],
                    'title'       => !empty($method['title'][$lc]) ? $method['title'][$lc] : $method['code'],
                    'description' => !empty($method['description'][$lc]) ? $method['description'][$lc] : '',
                    'dummy'       => true,
                    'sort_order'  => isset($method['sortOrder']) ? $method['sortOrder'] : $this->config->get($method['code'].'_sort_order')
                );
            }
        }

        return $result;
    }

    public function displayPaymentMethodForEmptyAddress($code) {
        $methods = $this->getSettingValue('methods', 'payment');

        if (!empty($methods) && is_array($methods) && !empty($methods[$code]['wait'])) {
            return false;
        }

        return true;
    }

    public function preparePaymentMethod($method) {
        $methods       = $this->getSettingValue('methods', 'payment');
        $checkedMethod = !empty($methods[$method['code']]) ? $methods[$method['code']] : array();
        $lc            = $this->getCurrentLanguageCode();
        $result        = $method;

        if (empty($checkedMethod)) {
            return $result;
        }

        if (!empty($checkedMethod['useTitle']) && !empty($checkedMethod['title'][$lc])) {
            $result['title'] = $checkedMethod['title'][$lc];
        }

        $groupId = $this->session->data['simple']['customer']['customer_group_id'];

        if (!empty($checkedMethod['useDescription']) && !empty($checkedMethod['description'][$lc])) {
            $result['description'] = $checkedMethod['description'][$lc];
        }

        if (empty($checkedMethod['forAllGroups']) && !empty($checkedMethod['forGroups']) && empty($checkedMethod['forGroups'][$groupId])) {
            return array();
        }

        if (!empty($checkedMethod['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
            return array();
        }

        if (!empty($checkedMethod['hideForStatuses']['logged']) && $this->customer->isLogged()) {
            return array();
        }

        if (!$this->isPaymentBeforeShipping() && $this->cart->hasShipping() && empty($checkedMethod['forAllMethods']) && !empty($checkedMethod['forMethods'])) {
            $shippingMethod = isset($this->session->data['shipping_method']) ? $this->session->data['shipping_method'] : array();;

            $isLinksUsed = false;
            $isLinkFounded = false;

            foreach ($checkedMethod['forMethods'] as $mask => $value) {
                if (!empty($value)) {
                    $isLinksUsed = true;

                    if (!empty($shippingMethod['code'])) {
                        if (preg_match($this->convertMaskToRegexp($mask), $shippingMethod['code'])) {
                            $isLinkFounded = true;
                        }
                    }
                }
            }

            if ($isLinksUsed && !$isLinkFounded) {
                return array();
            }
        }

        return $result;
    }

    public function displayWeight() {
        return $this->getSettingValue('displayWeight');
    }

    public function displayAddressSame() {
        return $this->cart->hasShipping() && !$this->isBlockHidden('shipping_address') && !$this->isBlockHidden('payment_address') && $this->getSettingValue('displayAddressSame', 'payment_address');
    }

    public function isAddressSame() {
        if ($this->displayAddressSame()) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                return !empty($this->request->post['address_same']) ? true : false;
            } else {
                return $this->getSettingValue('addressSameInit', 'payment_address');
            }
        } else {
            if (!$this->isBlockHidden('shipping_address') && !$this->isBlockHidden('payment_address')) {
                return false;
            } else {
                return true;
            }
        }

        return true;
    }

    public function exportShippingMethods($quote) {
        if (empty($quote['code']) || empty($quote['quote'])) {
            return;
        }

        $exported = $this->cache->get('simple_shipping_methods');

        if (empty($exported)) {
            $exported = array();
        }

        if (empty($exported[$quote['code']])) {
            $exported[$quote['code']] = $quote;
        } else {
            foreach ($quote['quote'] as $code => $info) {
                if (empty($exported[$quote['code']]['quote'][$code])) {
                    $exported[$quote['code']]['quote'][$code] = $info;
                }
            }
        }

        $this->cache->set('simple_shipping_methods', $exported);
    }

    public function exportPaymentMethod($method) {
        if (empty($method['code'])) {
            return;
        }

        $exported = $this->cache->get('simple_payment_methods');

        if (empty($exported)) {
            $exported = array();
        }

        if (empty($exported[$method['code']])) {
            $exported[$method['code']] = $method;
        }

        $this->cache->set('simple_payment_methods', $exported);
    }

    private function getCustomFieldsOfObject($object) {
        $result = array();

        $fields = $this->getFields($object);

        if (empty($fields)) {
            return array();
        }

        foreach ($fields as $fieldInfo) {
            if (!empty($fieldInfo['custom'])) {
                $result[] = $fieldInfo['id'];
            }
        }

        return $result;
    }

    private function loadFromSession() {
        $orderCustomFields = $this->getCustomFieldsOfObject('order');

        $customerFields = array('firstname', 'lastname', 'email', 'telephone', 'fax', 'customer_group_id', 'register');
        $customerCustomFields = $this->getCustomFieldsOfObject('customer');

        $customerFields = array_merge($customerFields, $customerCustomFields);
        $customerFields = array_merge($customerFields, $orderCustomFields);

        $addressFields = array('firstname', 'lastname', 'company_id', 'tax_id', 'address_1', 'address_2', 'postcode', 'city', 'country_id', 'zone_id');
        $addressCustomFields = $this->getCustomFieldsOfObject('address');

        $addressFields = array_merge($addressFields, $addressCustomFields);
        $addressFields = array_merge($addressFields, $orderCustomFields);

        $specialFields = array('postcode', 'country_id', 'zone_id');

        $result = array();

        foreach ($customerFields as $field) {
            if (!empty($this->session->data['guest'][$field])) {
                $result[$field] = $this->session->data['guest'][$field];
            }
        }

        if (count($result) > 0) {
            $this->session->data['simple']['customer'] = $result;
        }

        $result = array();

        foreach ($addressFields as $field) {
            if (!empty($this->session->data['guest']['payment'][$field])) {
                $result[$field] = $this->session->data['guest']['payment'][$field];
            }

            if (!empty($this->session->data['payment_address'][$field])) {
                $result[$field] = $this->session->data['payment_address'][$field];
            }
        }

        foreach ($specialFields as $field) {
            if (!empty($this->session->data['payment_'.$field])) {
                $result[$field] = $this->session->data['payment_'.$field];
            }
        }

        if (count($result) > 0) {
            $this->session->data['simple']['payment_address'] = $result;
        }

        $result = array();

        foreach ($addressFields as $field) {
            if (!empty($this->session->data['guest']['shipping'][$field])) {
                $result[$field] = $this->session->data['guest']['shipping'][$field];
            }

            if (!empty($this->session->data['shipping_address'][$field])) {
                $result[$field] = $this->session->data['shipping_address'][$field];
            }
        }

        foreach ($specialFields as $field) {
            if (!empty($this->session->data['shipping_'.$field])) {
                $result[$field] = $this->session->data['shipping_'.$field];
            }
        }

        if (count($result) > 0) {
            $this->session->data['simple']['shipping_address'] = $result;
        }
    }

    public function initBlocks($ignorePostManual = false) {
        $sessionExpired = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && empty($this->session->data['simple'])) {
            $sessionExpired = true;
        }

        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            if (!$this->customer->isLogged()) {
                if ($this->getSettingValue('useCookies')) {
                    $this->loadFromCookies();
                }

                foreach (array('payment_address', 'shipping_address') as $block) {
                    $this->loadSimpleSessionViaGeoIp($block);
                }

                $this->loadFromSession();
            }
        } else {
            $this->setIgnorePostFlag($ignorePostManual);
        }

        foreach (array('customer', 'payment_address', 'shipping_address', 'shipping', 'payment') as $block) {
            $this->init($block, $sessionExpired);
        }

        if (!empty($this->session->data['simple'])) {
            $this->session->data['simple']['comment'] = '';
        }

        $this->setCustomerId();
        $this->copyNameFields();
        $this->copyFields();

        if ($this->getSettingValue('useCookies') && !$ignorePostManual) {
            $this->saveToCookies();
        }

        if (!empty($this->session->data['simple']['customer']['customer_group_id']) && $this->config->get('config_customer_group_id') != $this->session->data['simple']['customer']['customer_group_id']) {
            $this->config->set('config_customer_group_id', $this->session->data['simple']['customer']['customer_group_id']);

            if ($this->getOpencartVersion() < 220) {
                $this->cart = new Cart($this->_registry);
            } else {
                $this->cart = new Cart\Cart($this->_registry);
            }
        }
    }

    public function validateBlocks() {
        foreach (array('customer', 'payment_address', 'shipping_address', 'shipping', 'payment') as $block) {
            if (!$this->isBlockHidden($block) && !$this->validateFields($block)) {
                $this->addError($block);
            }
        }
    }

    public function clearUnusedFields($block = '') {
        foreach (array('customer', 'payment_address', 'shipping_address', 'shipping', 'payment') as $block) {
            parent::clearUnusedFields($block);
        }
    }

    private function setIgnorePostFlag($ignorePostManual = false) {
        foreach (array('payment_address', 'shipping_address') as $block) {
            if (isset($this->request->post[$block]['address_id']) && isset($this->request->post[$block]['current_address_id'])) {
                if ($this->request->post[$block]['address_id'] != $this->request->post[$block]['current_address_id']) {
                    $this->request->post[$block]['ignore_post'] = true;

                    $this->reset($block);
                }
            }
        }

        if (isset($this->request->post['address_same'])) {
            $this->request->post['shipping_address']['ignore_post'] = true;

            $this->reset('shipping_address');
        }

        if ($ignorePostManual) {
            $this->request->post['ignore_post'] = true;

            $this->reset('customer');
            $this->reset('payment_address');
            $this->reset('shipping_address');
        }
    }

    public function replaceAddressIdInPostRequest($block, $addressId) {
        $this->request->post[$block]['address_id'] = $addressId;
        $this->request->post[$block]['current_address_id'] = $addressId;
    }

    public function loadFromCookies() {
        foreach (array('customer', 'payment_address', 'shipping_address') as $block) {
            if (isset($this->request->cookie['simple_'.$block])) {
                $info = explode(';', $this->request->cookie['simple_'.$block]);

                if (is_array($info)) {
                    $result = array();

                    foreach ($info as $item) {
                        $parts = explode('=', $item);

                        if (count($parts) != 2) {
                            continue;
                        }

                        list($key, $value) = $parts;

                        $value = @base64_decode($value);

                        if (empty($this->session->data['simple'][$block][$key]) && !empty($value)) {
                            $result[$key] = $value;
                        }
                    }

                    if (count($result) > 0) {
                        $this->session->data['simple'][$block] = $result;
                    }
                }
            }
        }
    }

    private function saveToCookies() {
        $simple = $this->session->data['simple'];

        foreach (array('register', 'password', 'confirm_password', 'customer_group_id', 'newsletter') as $id) {
            if (isset($simple['customer'][$id])) {
                unset($simple['customer'][$id]);
            }
        }

        foreach (array('customer', 'payment_address', 'shipping_address') as $block) {
            if (!empty($simple[$block])) {
                $data = array();

                foreach ($simple[$block] as $id => $value) {
                    if (!empty($value) && !is_array($value)) {
                        $data[] = $id.'='.base64_encode($value);
                    }
                }

                setcookie('simple_'.$block, implode(';', $data), time() + 60 * 60 * 24 * 30, '/');
            }
        }
    }

    public function isShippingAddressEmpty() {
        $empty = false;

        $fields = $this->getFields('address');

        foreach ($fields as $fieldSettings) {
            if ($this->isFieldUsed($fieldSettings['id'], 'shipping_address') && in_array($fieldSettings['id'], $this->_observedFields['shipping_address']) && (!isset($this->session->data['simple']['shipping_address'][$fieldSettings['id']]) || $this->session->data['simple']['shipping_address'][$fieldSettings['id']] == '')) {
                $empty = true;
            }
        }

        return $empty;
    }

    public function isPaymentAddressEmpty() {
        $empty = false;

        $fields = $this->getFields('address');

        foreach ($fields as $fieldSettings) {
            if ($this->isFieldUsed($fieldSettings['id'], 'payment_address') && in_array($fieldSettings['id'], $this->_observedFields['payment_address']) && (!isset($this->session->data['simple']['payment_address'][$fieldSettings['id']]) || $this->session->data['simple']['payment_address'][$fieldSettings['id']] == '')) {
                $empty = true;
            }
        }

        return $empty;
    }

    private function setCustomerId() {
        if (empty($this->session->data['simple']['customer'])) {
            return;
        }

        if ($this->customer->isLogged()) {
            $this->session->data['simple']['customer']['customer_id'] = $this->customer->getId();
        } else {
            $this->session->data['simple']['customer']['customer_id'] = 0;
        }
    }

    private function copyFields() {
        $fields = $this->getFields('customer');

        if (empty($fields)) {
            return;
        }

        foreach ($fields as $fieldSettings) {
            if ($fieldSettings['custom']) {
                if (isset($this->session->data['simple']['payment'][$fieldSettings['id']])) {
                    $this->session->data['simple']['customer'][$fieldSettings['id']] = $this->session->data['simple']['payment'][$fieldSettings['id']];
                }

                if (isset($this->session->data['simple']['shipping'][$fieldSettings['id']])) {
                    $this->session->data['simple']['customer'][$fieldSettings['id']] = $this->session->data['simple']['shipping'][$fieldSettings['id']];
                }
            }
        }

        $fields = $this->getFields('address');

        foreach ($fields as $fieldSettings) {
            if ($fieldSettings['custom']) {
                if (isset($this->session->data['simple']['payment'][$fieldSettings['id']])) {
                    $this->session->data['simple']['payment_address'][$fieldSettings['id']] = $this->session->data['simple']['payment'][$fieldSettings['id']];
                }

                if (isset($this->session->data['simple']['shipping'][$fieldSettings['id']])) {
                    $this->session->data['simple']['shipping_address'][$fieldSettings['id']] = $this->session->data['simple']['shipping'][$fieldSettings['id']];
                }
            }
        }

        if ((!$this->isBlockHidden('payment_address') && !$this->isBlockHidden('shipping_address') && $this->isAddressSame()) || (!$this->isBlockHidden('payment_address') && $this->isBlockHidden('shipping_address'))) {
            foreach ($this->session->data['simple']['payment_address'] as $key => $value) {
                $this->session->data['simple']['shipping_address'][$key] = $value;
            }
        }

        if (($this->isBlockHidden('payment_address') && !$this->isBlockHidden('shipping_address')) || ($this->isBlockHidden('payment_address') && $this->isBlockHidden('shipping_address'))) {
            foreach ($this->session->data['simple']['shipping_address'] as $key => $value) {
                $this->session->data['simple']['payment_address'][$key] = $value;
            }
        }
    }

    private function copyNameFields() {
        foreach (array('firstname', 'lastname') as $field) {
            if (!$this->customer->isLogged() && !$this->isFieldUsed($field, 'customer')) {
                if ($this->isFieldUsed($field, 'payment_address') && !$this->isBlockHidden('payment_address')) {
                    $this->session->data['simple']['customer'][$field] = $this->session->data['simple']['payment_address'][$field];
                } elseif ($this->isFieldUsed($field, 'shipping_address') && !$this->isBlockHidden('shipping_address')) {
                    $this->session->data['simple']['customer'][$field] = $this->session->data['simple']['shipping_address'][$field];
                }
            }

            if (!$this->isFieldUsed($field, 'payment_address') && $this->isFieldUsed($field, 'customer')) {
                $this->session->data['simple']['payment_address'][$field] = $this->session->data['simple']['customer'][$field];
            }

            if (!$this->isFieldUsed($field, 'shipping_address') && $this->isFieldUsed($field, 'customer')) {
                $this->session->data['simple']['shipping_address'][$field] = $this->session->data['simple']['customer'][$field];
            }
        }
    }

    public function formatCurrency($value) {
        if ($this->getOpencartVersion() < 220) {
            return $this->currency->format($value);
        } else {
            return $this->currency->format($value, $this->session->data['currency']);
        }
    }
}