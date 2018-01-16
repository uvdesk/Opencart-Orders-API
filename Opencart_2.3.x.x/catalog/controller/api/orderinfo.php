<?php
class ControllerApiOrderinfo extends Controller {

	public function info() {
		$this->load->language('api/order');

		$json = array();

		if (!$this->config->get('wkocuvdesk_status') || !isset($this->request->post['api_key']) || $this->request->post['api_key'] != $this->config->get('wkocuvdesk_api_key') || !isset($this->request->post['api_password']) || $this->request->post['api_password'] != $this->config->get('wkocuvdesk_api_password')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');

			if (isset($this->request->post['order_id'])) {
				$order_id = $this->request->post['order_id'];
			} else {
				$order_id = 0;
			}

			$json = $this->model_checkout_order->getOrder($order_id);

			$this->load->model('account/order');

			$json['products'] = $this->model_account_order->getOrderProducts($order_id);

			if ($json['products']) {
				foreach ($json['products'] as $key => $product) {
						$json['products'][$key]['url'] = html_entity_decode($this->url->link('product/product', 'product_id=' . $product['product_id'], true));
				}
			}

			if (!$json) {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
