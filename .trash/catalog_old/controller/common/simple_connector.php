<?php
class ControllerCommonSimpleConnector extends Controller {
    public function index() {
        $custom = isset($this->request->get['custom']) ? true : false;
        $method = isset($this->request->get['method']) ? trim($this->request->get['method']) : '';
        $filter = isset($this->request->get['filter']) ? trim($this->request->get['filter']) : '';

        if (!$method) {
            exit;
        }

        if (!$custom) {
            $this->load->model('tool/simpleapimain');

            if (method_exists($this->model_tool_simpleapimain, $method) || property_exists($this->model_tool_simpleapimain, $method) || (method_exists($this->model_tool_simpleapimain, 'isExistForSimple') && $this->model_tool_simpleapimain->isExistForSimple($method))) {
                $this->response->setOutput(json_encode($this->model_tool_simpleapimain->{$method}($filter)));
            }
        } else {
            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method) || property_exists($this->model_tool_simpleapicustom, $method) || (method_exists($this->model_tool_simpleapicustom, 'isExistForSimple') && $this->model_tool_simpleapicustom->isExistForSimple($method))) {
                $this->response->setOutput(json_encode($this->model_tool_simpleapicustom->{$method}($filter)));
            }
        }
    }

    public function validate() {
        $custom = isset($this->request->get['custom']) ? true : false;
        $method = isset($this->request->get['method']) ? trim($this->request->get['method']) : '';
        $filter = isset($this->request->get['filter']) ? trim($this->request->get['filter']) : '';
        $value = isset($this->request->get['value']) ? trim($this->request->get['value']) : '';

        if (!$method) {
            exit;
        }

        if (!$custom) {
            $this->load->model('tool/simpleapimain');

            if (method_exists($this->model_tool_simpleapimain, $method) || property_exists($this->model_tool_simpleapimain, $method) || (method_exists($this->model_tool_simpleapimain, 'isExistForSimple') && $this->model_tool_simpleapimain->isExistForSimple($method))) {
                $this->response->setOutput($this->model_tool_simpleapimain->{$method}($value, $filter) ? 'valid' : 'invalid');
            }
        } else {
            $this->load->model('tool/simpleapicustom');

            if (method_exists($this->model_tool_simpleapicustom, $method) || property_exists($this->model_tool_simpleapicustom, $method) || (method_exists($this->model_tool_simpleapicustom, 'isExistForSimple') && $this->model_tool_simpleapicustom->isExistForSimple($method))) {
                $this->response->setOutput($this->model_tool_simpleapicustom->{$method}($value, $filter) ? 'valid' : 'invalid');
            }
        }
    }

    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output);
    }

    public function geo() {
        $this->load->model('tool/simplegeo');

        $term = $this->request->get['term'];

        if (utf8_strlen($term) < 2) {
            exit;
        }

        $this->response->setOutput(json_encode($this->model_tool_simplegeo->getGeoList($term)));
    }

    public function upload() {
        $this->language->load('checkout/simplecheckout');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!empty($this->request->files['file']['name'])) {
                $filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

                $filename = str_replace(' ', '_', $filename);

                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
                $allowed = array();

                $config_extensions = $this->config->get('config_file_extension_allowed');

                if (empty($config_extensions)) {
                    $config_extensions = $this->config->get('config_file_ext_allowed');
                }

                if (empty($config_extensions)) {
                    $config_extensions = $this->config->get('config_upload_allowed');
                    $filetypes = explode(",", $config_extensions);
                } else {
                    $filetypes = explode("\n", $config_extensions);
                }

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = array();

                $config_filetypes = $this->config->get('config_file_mime_allowed');

                if (!empty($config_filetypes)) {
                    $filetypes = explode("\n", $config_filetypes);

                    foreach ($filetypes as $filetype) {
                        $allowed[] = trim($filetype);
                    }

                    if (!in_array($this->request->files['file']['type'], $allowed)) {
                        $json['error'] = $this->language->get('error_filetype');
                    }
                }

                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }

            if (!isset($json['error'])) {
                if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
                    $file = basename($filename) . '.' . md5(mt_rand());

                    $json['filename'] = $filename;

                    $opencartVersion = explode('.', VERSION);
                    $opencartVersion = floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));

                    if ($opencartVersion < 200) {
                        $encryption = new Encryption($this->config->get('config_encryption'));
                        $json['file'] = $encryption->encrypt($file);
                    } else {
                        $this->load->model('tool/upload');
                        $json['file'] = $this->model_tool_upload->addUpload($filename, $file);
                    }

                    move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function captcha() {
        $this->session->data['captcha'] = substr(sha1(mt_rand()), 17, 6);

        $image = imagecreatetruecolor(150, 35);

        $width = imagesx($image);
        $height = imagesy($image);

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        $red = imagecolorallocatealpha($image, 255, 0, 0, 75);
        $green = imagecolorallocatealpha($image, 0, 255, 0, 75);
        $blue = imagecolorallocatealpha($image, 0, 0, 255, 75);

        imagefilledrectangle($image, 0, 0, $width, $height, $white);

        imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red);
        imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green);
        imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue);

        imagefilledrectangle($image, 0, 0, $width, 0, $black);
        imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $black);
        imagefilledrectangle($image, 0, 0, 0, $height - 1, $black);
        imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $black);

        imagestring($image, 10, intval(($width - (strlen($this->session->data['captcha']) * 9)) / 2), intval(($height - 15) / 2), $this->session->data['captcha'], $black);

        header('Content-type: image/jpeg');

        imagejpeg($image);

        imagedestroy($image);
    }

    public function human() {
        if (isset($this->session->data['get_used'])) {
            $this->session->data['human'] = true;
        }

        echo 'success'; 
    }

    public function header() {
        $opencartVersion = explode('.', VERSION);
        $opencartVersion = floatval($opencartVersion[0].$opencartVersion[1].$opencartVersion[2].'.'.(isset($opencartVersion[3]) ? $opencartVersion[3] : 0));

        if ($opencartVersion < 200) {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/maintenance.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/common/maintenance.tpl';
            } else {
                $this->template = 'default/template/common/maintenance.tpl';
            }

            $this->data['message'] = '';

            $this->children = array(
                'common/header',
                'common/footer'
            );

            $this->response->setOutput($this->render());
        } else {
            $this->response->setOutput($this->load->controller('common/header'));
        }
    }
}
?>