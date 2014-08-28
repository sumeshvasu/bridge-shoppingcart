<?php

/**
 * @project Bridge shoppingcart
 * Manage Product actions
 */
include_once 'controller/application-controller.php';

class ProductController extends AppController
{

    public $protocal_array    = '';
    public $host              = '';
    public $protocal          = '';
    public $request_uri_array = '';
    public $request_uri       = '';

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all products/Specific product
     * @param array $filters
     * @return array
     */
    public function get($filters = array())
    {
        if (isset($filters['id']))
        {
            $result = $this->database->productGetById($filters['id']);
        }
        else if (isset($filters['catId']))
        {
            $result = $this->database->productGetByCategory($filters['catId']);
        }
        else
        {
            $result = $this->database->productGetAll();
        }
        return $result;
    }

    /**
     * Insert the products data
     * @param array $data
     * @return int
     */
    public function insert($data)
    {
        $result = $this->database->productInsert($data);
        return $result;
    }

    /**
     * Delete the product data
     * @param int $id
     */
    public function delete($id)
    {
        $result = $this->database->productDelete($id);
        return $result;
    }

    /*
     * Genarete and email the download link of the product
     * @param int purchase_id
     * @param string $email
     * @param int $product_id
     * @return boolean
     */

    public function generate_download_link($purchase_id, $buyer_email, $product_id = null, $base_url = '')
    {
        if ($product_id)
        {
            $product_info   = $this->get(array('id' => $product_id));
            // Generate a download token and save to db
            $download_token = md5(mt_rand());
            $expires_on     = time() + $product_info['validity'] * 3600;
            $this->database->save_downlad_token($purchase_id, $download_token, $expires_on);

            //die(print_r($product_info));
            if (!empty($product_info))
            {
                $mail_info = array(
                    'from_email'  => 'sobin87@gmail.com',
                    'from_name'   => 'Sobin Yohannan',
                    'reply_email' => 'sobin87@gmail.com',
                    'reply_name'  => 'Sobin Yohannan',
                    'to_email'    => 'sobin.yohannan@live.com', // Should  be replaced with buyer email
                    'to_name'     => 'Sobin Yohannan'
                );

                $subject       = 'Bridgestore: Product download Link';
                $download_link = $base_url . 'index.php?page=downloader&token=' . $download_token;
                ob_start();
                include('layout/download_link_mail.php');
                $message       = ob_get_contents();
                ob_end_clean();
                if ($this->send_email($mail_info, $subject, $message))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
    }

}
