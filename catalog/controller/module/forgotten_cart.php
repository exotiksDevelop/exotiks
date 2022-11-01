<?php
include_once(DIR_SYSTEM . 'zrcode/library/forgotten_cart.php');

class ControllerModuleForgottenCart extends Controller {
    private $core = null;

    public function index() {
        $this->load->model('module/forgotten_cart');
        $db_data = $this->model_module_forgotten_cart->getSetting();
        $this->core = ZR_code\ForgottenCart::setData($db_data, $this->request->get, $this->request->post, 'forgotten_cart_', 'opencart');

        if ($this->core->getStatus()) {
            $this->document->addScript('catalog/view/javascript/forgotten_cart/script.js');
        }

        $this->model_module_forgotten_cart->updateSettings($this->core->getSettings());
    }
    
    public function ajax() {
        if ($this->config->get('forgotten_cart_status')) {
            $this->load->model('module/forgotten_cart');
            $db_data = $this->model_module_forgotten_cart->getSetting();
            $this->core = ZR_code\ForgottenCart::setData($db_data, $this->request->get, $this->request->post, 'forgotten_cart_');

            if ($this->core->getStatus() && $this->customer->isLogged()) {
                $this->model_module_forgotten_cart->updateCustomer($this->customer->getId(), $this->config->get('config_language_id'), $this->session->data['currency'], $this->cart->getProducts(), $_SERVER['HTTP_REFERER']);
            }

            if ($this->core->getStatus() && $this->core->isJavaScriptSend()) {
                $this->send_mail();
            }

            $this->model_module_forgotten_cart->updateSettings($this->core->getSettings());
        }
    }
    
    public function cron() {
        if ($this->config->get('forgotten_cart_status')) {
            $this->load->model('module/forgotten_cart');
            $db_data = $this->model_module_forgotten_cart->getSetting();
            $this->core = ZR_code\ForgottenCart::setData($db_data, $this->request->get, $this->request->post, 'forgotten_cart_');

            if ($this->core->getStatus() && $this->core->isCronSend()) {
                $this->send_mail();
            }

            $this->model_module_forgotten_cart->updateSettings($this->core->getSettings());
        }
    }
    
    public function send() {
        $this->load->model('module/forgotten_cart');
        $db_data = $this->model_module_forgotten_cart->getSetting();
        $this->core = ZR_code\ForgottenCart::setData($db_data, $this->request->get, $this->request->post, 'forgotten_cart_');

        $customer = $this->core->getSendCustomer();

        if ($customer) {
            $this->send_mail($customer['forgotten_cart_customer_id'], $customer['forgotten_cart_mail_type']);
        }

        $this->model_module_forgotten_cart->updateSettings($this->core->getSettings());
    }
    
    protected function send_mail($customer_id = 0, $mail_type = 0) {
        $this->load->model('tool/image');
        
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
        
        $customer = $this->model_module_forgotten_cart->getCustomer($customer_id, $this->core->getCustomerSettings());
        if ($customer) {
            $manager_notifi = 0;
            $related_limit = $this->core->relatedLimit();
            $customer_info = $this->core->getCustomerInfo($customer);
            $message_type = "";
            $total = $customer_info['total'];
            
            if (!$mail_type) {
                $mail_type = (int)$customer_info['mail_status'] + 1;
            }

            if ($mail_type == 2) {
               $message_type = "_repeated";
            }

            if ($this->core->isManagerNotifi($customer_id, $customer_info)) {
                $mail_type = (int)$customer_info['mail_status'];
                $message_type = "_manager";
                $manager_notifi = 1;
            }

            $message_data = array(
                'message_type'            => $message_type,
                'language_id'             => $customer['language_id'],
                'free_shipping'           => false,
                'discount_code'           => '',
                'discount'                => '',
                'products'                => array(),
                'related_products'        => array(),
                'manager_notifi'          => $manager_notifi,
                'customer_firstname'      => $customer['firstname'],
                'customer_lastname'       => $customer['lastname'],
                'customer_email'          => $customer['email'],
                'customer_telephone'      => $customer['telephone'],
                'customer_manager_notifi' => $customer_info['manager_notifi'],
                'site_name'               => $this->config->get('config_name'),
                'site_email'              => $this->config->get('config_email'),
                'site_telephone'          => $this->config->get('config_telephone'),
                'site_logo'               => $server . 'image/' . $this->config->get('config_logo'),
                'cart_link'               => $this->url->link('checkout/cart'),
            );

            $discount = $this->core->getDiscount($total, $mail_type);

            if ($discount) {
                $old_coupon = $this->model_module_forgotten_cart->get_old_coupon($customer['customer_id']);
                $coupon_products = array();
                $coupon_discount = "";

                if (isset($old_coupon['code'])) {
                    $coupon_code = $old_coupon['code'];
                } else {
                    $coupon_code = $this->generate_coupon(9);
                }

                foreach ($customer_info['products'] as $product) {
                    $coupon_products[] = $product['product_id'];
                }

                if ($discount['type'] == "P" && (float)$discount['discount'] > 0) {
                    $coupon_discount = (float)$discount['discount']."%";
                } elseif($discount['type'] == "F" && (float)$discount['discount'] > 0) {
                    $coupon_discount = $this->currency->format($discount['discount'], $customer['currency_code']);
                }

                $discount['code'] = $coupon_code;
                $discount['coupon_product'] = $coupon_products;
                
                if (isset($old_coupon['coupon_id'])) {
                    $this->model_module_forgotten_cart->updateCoupon($customer['customer_id'], $old_coupon['coupon_id'], $discount);
                } else {
                    $this->model_module_forgotten_cart->addCoupon($customer['customer_id'], $discount);
                }

                $message_data['free_shipping'] = $discount['shipping'];
                $message_data['discount_code'] = $coupon_code;
                $message_data['discount'] = $coupon_discount;
            }
            
            foreach ($customer_info['products'] as $product) {
                $product_options = array();

                foreach ($product['options'] as $option) {
                    $product_options[] = "- <small>" . $option . "</small>";
                }

                $options = implode('<br>', $product_options);
                
                if ($product['image']) {
                    $image = $this->model_tool_image->resize($product['image'], 100, 100);
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 100, 100);
                }

                $message_data['products'][] = array(
                    'product_id' => $product['product_id'],
                    'name'       => $product['name'],
                    'model'      => $product['model'],
                    'options'    => $options,
                    'image'      => $image,
                    'quantity'   => $product['quantity'],
                    'price'      => $this->currency->format($product['price'], $customer['currency_code']),
                    'sum'        => $this->currency->format($product['total'], $customer['currency_code']),
                    'link'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }
            
            if ($related_limit) {
                $related_data = $this->core->getRelatedData();
                $related_products = array();

                shuffle($customer_info['products']);
                
                foreach ($customer_info['products'] as $product) {
                    $products_related = $this->model_module_forgotten_cart->getRelatedProducts($product['product_id'], $product['options'], $product['price'], $customer['language_id'], $related_data['attributes'], $related_data['attribute_condition'], $related_data['options'], $related_data['option_condition'], $related_data['price_step'], $related_data['limit']);
                    
                    $related_products = array_merge($related_products, $products_related);
                    
                    if (count($related_products) >= $related_limit) {
                        break;
                    } else {
                        $related_limit -= count($related_products);
                    }
                }
                
                if ($related_products) {
                    $i = 0;

                    shuffle($related_products);
                    
                    foreach ($related_products as $product) {
                        if ($i >= $related_data['limit']) {
                            break;
                        }

                        if ($product['image']) {
                            $image = $this->model_tool_image->resize($product['image'], 100, 100);
                        } else {
                            $image = $this->model_tool_image->resize('no_image.png', 100, 100);
                        }
                        
                        if ((float)$product['special']) {
                            $price = $this->currency->format($product['special'], $customer['currency_code']);
                        } else {
                            $price = $this->currency->format($product['price'], $customer['currency_code']);
                        }

                        $message_data['related_products'][] = array(
                            'product_id' => $product['product_id'],
                            'name'       => $product['name'],
                            'model'      => $product['model'],
                            'image'      => $image,
                            'price'      => $price,
                            'link'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                        );

                        $i++;
                    }
                }
            }

            $message = $this->core->getMessage($message_data);

            if ($message['email']) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
                    $html = $this->load->view($this->config->get('config_template') . '/template/mail/forgotten_cart.tpl', $message);
                } else {
                    $html = $this->load->view('default/template/mail/forgotten_cart.tpl', $message);
                }

                $this->model_module_forgotten_cart->sendMail($message['email'], $this->config->get('config_email'), $this->config->get('config_name'), $message['title'], $html);
            }
            
            $this->model_module_forgotten_cart->confirm_mail($customer['customer_id'], $mail_type, $message['manager_notifi']);
        } elseif($customer_id) {
            if ($this->core->isCustomerNotifi()) {
                $this->model_module_forgotten_cart->delete_cart($customer_id);
            }
        }
    }

    private function generate_coupon($length = 9) {
        $code = $this->core->generateCode($length);
        $check = $this->model_module_forgotten_cart->check_coupon($code);

        if (!$check) {
            $code = $this->generate_coupon(10);
        }
        
        return $code;
    }
}