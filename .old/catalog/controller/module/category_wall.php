<?php
class ControllerModuleCategoryWall extends Controller {

    public function index() {
        $this->load->language('module/category_wall');

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        if (isset($parts[0])) {
            $data['category_id'] = $parts[0];
        } else {
            $data['category_id'] = 0;
        }

        if (isset($parts[1])) {
            $data['child_id'] = $parts[1];
        } else {
            $data['child_id'] = 0;
        }

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $data['categories'] = array();

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);

        $this->load->model('tool/image');

        foreach ($categories as $category) {
            if ($category['top']) {
            $img_w = $this->config->get('config_image_category_width');
            $img_h = $this->config->get('config_image_category_height');

            if ($category['image']) {
                $image = $this->model_tool_image->resize($category['image'], $img_w, $img_h);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $img_w, $img_h);
            }

            $data['categories'][] = array(
                'description' => $category['description'],
                'name' => $category['name'],
                'image' => $image,
                'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_wall.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/category_wall.tpl', $data);
        } else {
            return $this->load->view('magazin/template/module/category_wall.tpl', $data);
        }
    }
}