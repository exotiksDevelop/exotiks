<?php

use Medoo\Medoo;

class DbConnection
{
    private $db;

    public function __construct(Medoo $db)
    {
        $this->db = $db;
    }

    public function createTables()
    {
        $this->db->create('assortment', [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            'assort_id' => [
                'VARCHAR(60)',
                'DEFAULT NULL',
                'UNIQUE'
            ],
            'quantity' => [
                'INT',
                'DEFAULT 0'
            ],
            'ms_code' => [
                'VARCHAR(200)',
                'DEFAULT NULL'
            ],
            'ms_barcode' => [
                'VARCHAR(200)',
                'DEFAULT NULL'
            ],
            'ms_name' => [
                'VARCHAR(200)',
                'DEFAULT NULL'
            ],

            "created" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP"
            ],
            "updated" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP",
                "ON UPDATE CURRENT_TIMESTAMP"
            ]
        ]);

        $this->db->create('ali_products', [
            "ms_product_full_data" => [
                "TEXT"
            ],
            "ms_id" => [
                "VARCHAR(60)",
                "UNIQUE"
            ],
            "name" => [
                "VARCHAR(255)"
            ],
            "article" => [
                "VARCHAR(255)",
                "DEFAULT NULL"
            ],
            "barcode" => [
                "VARCHAR(255)",
                "DEFAULT NULL"
            ],
            "path_name" => [
                "VARCHAR(255)",
                "DEFAULT NULL"
            ],
            "in_stock" => [
                "INT",
                "DEFAULT 0"
            ],
            "ali_state" => [
                "VARCHAR(60)",
                "DEFAULT 'New'"
            ],
            "ali_product_id" => [
                "BIGINT",
                "DEFAULT NULL"
            ],
            "publish" => [
                "tinyint(2)",
                "DEFAULT 1"
            ],
            "ali_online" => [
                "tinyint(2)",
                "DEFAULT 0"
            ],
            "created" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP"
            ],
            "updated" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP",
                "ON UPDATE CURRENT_TIMESTAMP"
            ]
        ]);
        $this->db->create('ali_orders', [
            "id" => [
                "INT",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "ali_order_id" => [
                "VARCHAR(60)",
                "DEFAULT NULL",
                "UNIQUE"
            ],
            "customer" => [
                "VARCHAR(60)",
                "DEFAULT NULL"
            ],
            "status" => [
                "VARCHAR(20)",
                "DEFAULT NULL"
            ],
            "full_response" => [
                "text",
                "DEFAULT NULL"
            ],
            "ms_id" => [
                "VARCHAR(80)",
                "DEFAULT NULL"
            ],
            "created" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP"
            ],
            "updated" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP",
                "ON UPDATE CURRENT_TIMESTAMP"
            ]
        ]);
        $this->db->create('products_in_order', [
            "id" => [
                "INT",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "order_id" => [
                "VARCHAR(60)",
                "DEFAULT NULL",
                "UNIQUE"
            ],
            "product_id" => [
                "VARCHAR(60)",
                "DEFAULT NULL"
            ],
            "count" => [
                "smallint",
                "DEFAULT 0"
            ],
            "sum" => [
                "smallint",
                "DEFAULT 0"
            ],
            "state" => [
                "varchar(30)",
                "DEFAULT NULL"
            ],
            "created" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP"
            ],
            "updated" => [
                "DATETIME",
                "DEFAULT CURRENT_TIMESTAMP",
                "ON UPDATE CURRENT_TIMESTAMP"
            ]
        ]);
    }

    public function createProduct($product, $in_stock, $ms_product_json)
    {
        $article = empty($product['article']) ? $product['code'] : $product['article'];
        $columns = [
            "ali_product_id" => $product['id'],
            "publish" => 1,
            "name" => $product['name'],
            "article" => $article,
            "in_stock" => $in_stock,
            "ali_state" => "New",
        ];
        $this->db->insert("ali_products", $columns);
    }

        public function getSun($ms_id)
    {
      //  $data = $this->db->select('sunTest', '*', ['ms' => $ms_id]);
       // $res = $this->db->select("sunTest", "*", $where);
      //  $res = $this->db->get('sunTest', 'ms', ['ms' => (string)$ms_id]);

        $res = $this->db->select('ali_products', '*', ['article' => $ms_id]);
        //$this->db->get('ali_orders', 'id', ['ali_order_id' => (string)$data->id]);
        return $res;
    }
        public function setSun($ms_id,$ali_id,$article)
    {
       

        $columns = [
            'ms' =>$ms_id,
            'ali'=>$ali_id,
            'article'=>$article
        ];

        $this->db->insert("sunTest", $columns);
        return $res;
    }

    public function getProduct($ms_id, $article = '')
    {
        $where = [];
        if (!empty($article))
            $where["OR"] = ["part" => $article];
        if (!empty($ms_id))
            $where = ['part' => $ms_id];
        return $this->db->get("ci_business_prods", "*", $where);
    }
    public function getProductId($id,$mod_id)
    {
        if($mod_id){
            $where = ['b_id' => $id,'b_modification_id'=>$mod_id];
        }else{
            $where = ['b_id' => $id];
        }
        return $this->db->get("ci_business_prods", "*", $where);
    }

    /**
     * @param $where
     * @param $limit [offset, limit]
     * @return array|bool
     */
    public function selectAssortment($where, $limit = [])
    {
        if (!empty($limit))
            $where["LIMIT"] = $limit;
        return $this->db->select("assortment", "*", $where);
//        } else
//            return $this->db->select("ali_products", "*", $where);
    }

    public function updateProduct($data, $where)
    {
        return $this->db->update("ali_products", $data, $where);
    }

    public function deleteProduct($where)
    {
        return $this->db->delete('ali_products', $where);
    }

    /**
     * @param array $where
     * @param array $limit [offset, limit]
     * @return array|bool
     */
    public function getAllProducts(array $where = [], $limit = [])
    {
        if (!empty($limit)) {
            $where["LIMIT"] = $limit;
            return $this->db->select("ali_products", "*", $where);
        } else
            return $this->db->select("ali_products", "*", $where);
    }

    public function deleteProductsWhereStateDelete()
    {
        return $this->db->delete("ali_products", [
            "ali_state" => "Delete"
        ]);
    }

    public function setAllProductsStateDelete()
    {
        return $this->db->update("ali_products", ["ali_state" => "Delete"]);
    }

    public function setAllUnpublishedProductsStateDelete()
    {
        // kostyl1
        return $this->db->update("ali_products", ["ali_state" => "Delete", "publish" => true], ["publish" => false]);
    }

    public function setAllProductsUnpublished()
    {
        return $this->db->update("ali_products", ["publish" => false]);
    }

    public function setAllProductsState($state, $where = [])
    {
        return $this->db->update("ali_products", ["ali_state" => $state], $where);
    }

    public function createOrder($data)
    {
        $columns = [
            'ali_order_id' => (string)$data->id,
            'customer' => (string)$data->receipt_address->contact_person,
            'status' => (string)$data->order_status,
            'full_response' => json_encode($data)
        ];
        $this->db->insert("ali_orders", $columns);
        $order_id = $this->db->id();
        if (!$order_id) {
            $order_id = $this->db->get('ali_orders', 'id', ['ali_order_id' => (string)$data->id]);
        }
        return $order_id;
    }

    public function getOrder($where, $fields = '*')
    {
        return $this->db->get('ali_orders', $fields, $where);
    }

    function updateOrder($body, $where)
    {
        $this->db->update('ali_orders', $body, $where);
    }

    public function createProductInOrder($product_id, $order_id, $count, $amount)
    {
        $columns = [
            'product_id' => $product_id,
            'order_id' => $order_id,
            'count' => $count,
            'sum' => $amount,
        ];
        return $this->db->insert('products_in_order', $columns);
    }

}
