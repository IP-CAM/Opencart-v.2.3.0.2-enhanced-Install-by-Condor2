<?php
class ControllerExtensionFeedFeed extends Controller {
	public function index() {
        $log = new Log('feed.log');  

		$this->load->model('extension/feed/feed');
		$this->load->model('tool/image');  

        $start = microtime(true);

		$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
		$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
		$output .= '  <channel>';
		$output .= '  <title>' . $this->config->get('config_name') . '</title>';
		$output .= '  <description>' . $this->config->get('config_meta_description') . '</description>';
		$output .= '  <link>' . $this->config->get('config_url') . '</link>';

		$this->load->model('catalog/product');

		$products = $this->model_catalog_product->getProducts();
            if ($products) {
                foreach ($products as $product) {

                    $output .= "<item>\n";
                    $output .= " <g:id>" .$product['model']. "</g:id>\n";
                    $output .= " <g:title>" . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . "</g:title>\n";
                    $output .= " <description><![CDATA[" . strip_tags(html_entity_decode(preg_replace('/\s+/', ' ',$product['description']), ENT_QUOTES, 'UTF-8')) . "]]></description>\n";
                    $output .= " <g:link>" . $this->url->link('product/product', 'product_id=' . $product['product_id']) ."</g:link>\n";

                    if (!empty($product['image']) && is_file(DIR_IMAGE. html_entity_decode($product['image']))) {
                        //$width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width');
                        //$height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height');

                        //$output .= '    <g:image_link>' . @$this->model_tool_image->resize($product['image'],$width,$height) . "</g:image_link>\n";
                        $output .= ' <g:image_link>' . $this->model_tool_image->resize($product['image'],500,500) . "</g:image_link>\n";
                    } else {
                        $output .= ' <g:image_link></g:image_link>';
                    }

                    // change new to used if you sell used goods
                    $output .= " <g:condition>new</g:condition>\n";
                    $output .= " <g:quantity>" . $product['quantity'] . "</g:quantity>\n";
	         	   
                    $currencies = array(
						'RON',
						'EUR'
					);

                    if (in_array($this->session->data['currency'], $currencies)) {
						$currency_code = $this->session->data['currency'];
						$currency_value = $this->currency->getValue($this->session->data['currency']);
					} else {
						$currency_code = 'EUR';
						$currency_value = $this->currency->getValue('EUR');
					}

					if ((float)$product['special']) {
						$output .= "<g:price>" .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false) . "</g:price>\n";
					} else {
						$output .= "<g:price>" . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false) . "</g:price>\n";
					}

                    $output .= "  <g:gtin>" . $product['ean'] . "</g:gtin>\n";
                    $output .= "  <g:brand>" . htmlspecialchars($product['manufacturer'], ENT_QUOTES, 'UTF-8') ."</g:brand>\n";
                    $output .= "  <g:availability><![CDATA[" . ($product['quantity'] ? 'in stock' : 'out of stock') . "]]></g:availability>\n";
                    /*$output .= "  <g:adult>yes</g:adult>\n";*/ //for adult sales 
                    $output .= "</item>\n";

                }

            }

            $output .= "</channel>\n";
            $output .= "</rss>\n";

            $time = microtime(true) - $start;

            //$log->write(sprintf('Sitemap was generated for %.4F s. ', $time).'Request from '.$_SERVER["REMOTE_ADDR"].' '.$_SERVER['HTTP_USER_AGENT']);

		$this->response->addHeader('Content-Type: application/rss+xml');
		$this->response->setOutput($output);
	}
}