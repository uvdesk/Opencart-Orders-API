<?php

class ControllerExtensionModuleWkocuvdesk extends Controller {
	private $error = array();

	public function index() {
		$data = array_merge($this->load->language('extension/module/wkocuvdesk'));

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_wkocuvdesk', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/wkocuvdesk', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/module/wkocuvdesk', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$config_array = array(
			'module_wkocuvdesk_status',
			'module_wkocuvdesk_api_key',
			'module_wkocuvdesk_api_password',
		);

		foreach ($config_array as $config_val) {

			if (isset($this->request->post[$config_val])) {
				$data[$config_val] = $this->request->post[$config_val];
			} else {
				$data[$config_val] = $this->config->get($config_val);
			}

		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/wkocuvdesk', $data));
	}

	protected function validate() {

		if (!$this->user->hasPermission('modify', 'extension/module/wkocuvdesk')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['module_wkocuvdesk_api_key']) || !$this->request->post['module_wkocuvdesk_api_key']) {
			$this->error['warning'] = $this->language->get('error_key');
		}

		if (!isset($this->request->post['module_wkocuvdesk_api_password']) || !$this->request->post['module_wkocuvdesk_api_password']) {
			$this->error['warning'] = $this->language->get('error_password');
		}

		return !$this->error;
	}
}
