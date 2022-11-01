<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutText extends SimpleController {
    private $_templateData = array();

    public function index($block) {
        $this->loadLibrary('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $text = '';
        $texts = $this->simplecheckout->getSettingValue($block . 's');
        $old_text_id = $this->simplecheckout->getSettingValue($block . 'Id');

        if (empty($texts) && !empty($old_text_id)) {
            $text = $old_text_id;
        } elseif (!empty($texts)) {
            $text = $this->simplecheckout->getItemForShippingAndPayment($texts);
        }       

        $type = $this->simplecheckout->getSettingValue('type', $block);

        $this->_templateData['text_title'] = '';
        $this->_templateData['text_content'] = '';
        
        if (!$type || $type == 'information') {
            $this->load->model('catalog/information');

            $information = $this->model_catalog_information->getInformation($text);

            if ($information) {
                $this->_templateData['text_title'] = $information['title'];
                $this->_templateData['text_content'] = html_entity_decode($information['description'], ENT_QUOTES, 'UTF-8');
            }
        } else {
            $language_code = $this->simplecheckout->getCurrentLanguageCode();

            $this->_templateData['text_title'] = !empty($text['title']) && !empty($text['title'][$language_code]) ? $text['title'][$language_code] : '';
            $this->_templateData['text_content'] = !empty($text['content']) && !empty($text['content'][$language_code]) ? $text['content'][$language_code] : '';
        }            

        $this->_templateData['block'] = $block;
        $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader', $block);

        if (empty($this->_templateData['text_title']) && empty($this->_templateData['text_content'])) {
            $this->setOutputContent('');
        } else {
            $this->setOutputContent($this->renderPage('checkout/simplecheckout_text', $this->_templateData));
        }
    }
}


?>