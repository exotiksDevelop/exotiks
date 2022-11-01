<?php

class ControllerDwebExporterExporting extends Controller
{

    // <editor-fold desc="index" defaultstate="collapsed">

    public function index()
    {
        if (isset($_GET['id']) && $this->config->get('dwebexporter_status') == '1')
        {
            $exportId = $_GET['id'];
            $this->load->model('catalog/product');
            $this->load->model('dwebexporter/exporting');
            $exportQuery = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "dweb_exporting  "
                    . "WHERE export_id = '" . $this->db->escape($exportId) . "'");

            $exportingProducts = null;
            if ($exportQuery->row != null)
            {
                $exportingProducts = $this->model_dwebexporter_exporting->getExportingProducts($exportQuery->row);
            }

            if ($exportingProducts == null)
            {
                return $this->loadError();
            }

            $xmlstr = '<?xml version="1.0" encoding="utf-8" ?><root></root>';
            $xml = new SimpleXMLElement($xmlstr);

            $this->load->model('catalog/category');
            $this->load->model('tool/image');

            $parserXmlItems = array();
            if ($exportQuery->row['use_custom_parser'] == '1' && $exportQuery->row['custom_parser'] != null)
            {
                $customParser = html_entity_decode($exportQuery->row['custom_parser']);
                $parserXmlItems = simplexml_load_string($customParser);

                if (!$parserXmlItems)
                {
                    return $this->loadError();
                }
            }

            $products = $exportingProducts;
            $currency_code = $this->config->get('config_currency');
            $currency_value = 1;

            foreach ($products as $product)
            {
                $productImage = "";
                $productPrice = 0.00;
                $productLink = "";
                $productCategory = "";
                $categoryUrl = "";
                $categoryFull = "";

                $productImage = $this->model_tool_image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 500, 500);

                $categoryData = $this->GetCategoryData($product['product_id'], $exportQuery->row);
                $productCategory = $categoryData != null && isset($categoryData['title']) ? $categoryData['title'] : '';
                $categoryUrl = $categoryData != null && isset($categoryData['url']) ? $categoryData['url'] : '';
                $categoryFull = $categoryData != null && isset($categoryData['full_title']) ? $categoryData['full_title'] : '';
                $categoryPath = $categoryData != null && isset($categoryData['last_path']) ? $categoryData['last_path'] : '';

                $productLink = html_entity_decode($this->url->link('product/product', 'path=' . $categoryPath . '&product_id=' . $product['product_id']));

                if ((float) $product['special'])
                {
                    $productPrice = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false);
                }
                else
                {
                    $productPrice = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false);
                }

                if ($exportQuery->row['use_custom_parser'] == '1' && $exportQuery->row['custom_parser'] != null)
                {
                    $customItem = $xml->addChild('item');
                    $customItem = $this->GetCustomExportItem($parserXmlItems,$customItem, $product, $productLink, $productImage, $productCategory, $categoryUrl);
                }
                else
                {
                    $item = $xml->addChild('item');
                    $item->name = $product['name'];
                    //$item->description = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');
                    $item->image = $productImage;
                    $item->link = $productLink;
                    $item->price = $productPrice;
                    $item->category = html_entity_decode($productCategory);
                    $item->category_full = $categoryFull;
                    $item->category_link = $categoryUrl;
                    $item->manufacturer = $product['manufacturer'];
                    $item->model = $product['model'];
                    $item->in_stock = $product['quantity'];
                    $item->upc = $product['quantity'];
                    $item->weight = $product['weight'];
                }
            }
            $this->response->addHeader('Content-Type: application/xml');
            $this->response->setOutput($xml->asXML());
        }
        else
        {
            return $this->loadError();
        }
    }

// </editor-fold>

    /* ============================================================ */

    // <editor-fold desc="Get Custom Export Item" defaultstate="collapsed">

    private function GetCustomExportItem($parserXmlItems, $customItem, $product, $productLink, $productImage, $productCategory, $categoryUrl)
    {
        if ($parserXmlItems != null)
        {
            foreach ($parserXmlItems as $parserXmlItem)
            {
                $parserItem = new ParserItem($parserXmlItem);

                if ($parserItem->skip_mapping)
                {
                    $customItem->addChild($parserItem->name, $parserItem->custom_string);
                }
                else
                {
                    if ($parserItem->LoadItem($product, $productLink, $productImage, $productCategory, $categoryUrl))
                    {
                        $customItem->addChild($parserItem->name, htmlspecialchars($parserItem->value));
                    }
                }
            }
        }

        return $customItem;
    }

    // </editor-fold>

    /* ============================================================ */

    // <editor-fold desc="Get Category Data" defaultstate="collapsed">

    private function GetCategoryData($product_id, $exportData)
    {

        $result = array('title' => '', 'url' => '', 'full_title' => '', 'last_path' => '');

        $categories = $this->model_catalog_product->getCategories($product_id);
        $lastPath = '';

        $filterCategories = array();
        if ($exportData['all'] == 0 && $exportData['categories'])
        {
            $filterCategories = explode(',', $exportData['categories']);
        }

        foreach ($categories as $category)
        {
            if ($filterCategories != null && !empty($filterCategories))
            {
                if (!in_array($category['category_id'], $filterCategories))
                {
                    continue;
                }
            }

            $path = $this->getPath($category['category_id']);
            $result['url'] = html_entity_decode($this->url->link('product/category', 'path=' . $category['category_id']));
            if ($path)
            {
                foreach (explode('_', $path) as $path_id)
                {
                    $category_info = $this->model_catalog_category->getCategory($path_id);
                    $result['title'] = $category_info['name'];
                    if ($category_info)
                    {
                        $result['full_title'] .=!$result['full_title'] != '' ? $category_info['name'] : ' -> ' . $category_info['name'];
                    }
                }
                $lastPath = $path;
            }
            break;
        }

        $result['last_path'] = $lastPath;

        return $result;
    }

    // </editor-fold>

    /* ============================================================ */

    // <editor-fold desc="Get Path" defaultstate="collapsed">

    protected function getPath($parent_id, $current_path = '')
    {
        $category_info = $this->model_catalog_category->getCategory($parent_id);
        if ($category_info)
        {
            if (!$current_path)
            {
                $new_path = $category_info['category_id'];
            }
            else
            {
                $new_path = $category_info['category_id'] . '_' . $current_path;
            }

            $path = $this->getPath($category_info['parent_id'], $new_path);
            if ($path)
            {
                return $path;
            }
            else
            {
                return $new_path;
            }
        }
    }

// </editor-fold>

    /* ============================================================ */

    // <editor-fold desc="Load Error" defaultstate="collapsed">

    private function loadError()
    {
        $this->load->language('error/not_found');
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_error'),
            'href' => '#'
        );

        $this->document->setTitle($this->language->get('text_error'));

        $data['heading_title'] = $this->language->get('text_error');

        $data['text_error'] = $this->language->get('text_error');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        return $this->response->setOutput($this->load->view('error/not_found', $data));
    }

// </editor-fold>
}

class ParserItem
{

    var $id;
    var $enabled;
    var $name;
    var $source;
    var $prefix;
    var $suffix;
    var $mathexpression;
    var $mathexpression_val;
    var $skip_mapping = false;
    var $custom_string = "";
    var $value = "";

    function __construct($xmlObject)
    {
        $this->id = (string) $xmlObject->id;
        $this->enabled = $xmlObject->enabled == "true";
        $this->name = (string) $xmlObject->name;
        $this->source = (string) $xmlObject->source;
        $this->prefix = (string) $xmlObject->prefix;
        $this->suffix = (string) $xmlObject->suffix;
        $this->mathexpression = (string) $xmlObject->mathexpression;
        $this->mathexpression_val = (string) $xmlObject->mathexpression_val;
        $this->skip_mapping = $xmlObject->skip_mapping == "true";
        $this->custom_string = (string) $xmlObject->custom_string;
    }

    public function LoadItem($productArr, $productLink, $image, $category, $categoryLink)
    {
        if ($this->enabled)
        {
            if ($this->source == 'image')
            {
                $value = $image;
                $this->value = $this->prefix . $value . $this->suffix;
                return true;
            }

            if ($this->source == 'category')
            {
                $value = $category;
                $this->value = $this->prefix . $value . $this->suffix;
                return true;
            }

            if ($this->source == 'categorylink')
            {
                $value = $categoryLink;
                $this->value = $this->prefix . $value . $this->suffix;
                return true;
            }

            if ($this->source == 'productlink')
            {
                $value = $productLink;
                $this->value = $this->prefix . $value . $this->suffix;
                return true;
            }

            if (isset($productArr[$this->source]))
            {
                $value = $productArr[$this->source];

                if ($this->mathexpression != '' && is_numeric($value) && is_numeric($this->mathexpression_val))
                {
                    //$Calculator = new MathExpCalculator();
                    //$result = $Calculator->calculate($this->mathexpression_val);

                    switch ($this->mathexpression) {
                        case '+':
                            $value = $value + $this->mathexpression_val;
                            break;
                        case '-':
                            $value = $value - $this->mathexpression_val;
                            break;
                        case '*':
                            $value = $value * $this->mathexpression_val;
                            break;
                        case '/':
                            $value = $value / $this->mathexpression_val;
                            break;
                    }
                }

                $this->value = $this->prefix . $value . $this->suffix;
                return true;
            }
        }

        return false;
    }

}

class MathExpCalculator
{

    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';
    const PARENTHESIS_DEPTH = 10;

    public function calculate($input)
    {
        if (strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null)
        {
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $input);

            //  Calculate each of the parenthesis from the top
            $i = 0;
            while (strpos($input, '(') || strpos($input, ')')) {
                $input = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $input);

                $i++;
                if ($i > self::PARENTHESIS_DEPTH)
                {
                    break;
                }
            }

            //  Calculate the result
            if (preg_match(self::PATTERN, $input, $match))
            {
                return $this->compute($match[0]);
            }

            return 0;
        }

        return $input;
    }

    private function compute($input)
    {
        $compute = create_function('', 'return ' . $input . ';');

        return 0 + $compute();
    }

    private function callback($input)
    {
        if (is_numeric($input[1]))
        {
            return $input[1];
        }
        elseif (preg_match(self::PATTERN, $input[1], $match))
        {
            return $this->compute($match[0]);
        }

        return 0;
    }

}

?>