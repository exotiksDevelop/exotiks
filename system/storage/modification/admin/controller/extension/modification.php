<?php
/**
 * Modifcation XML Documentation can be found here:
 *
 * https://github.com/opencart/opencart/wiki/Modification-System
 */
class ControllerExtensionModification extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $modification_id) {
				$this->model_extension_modification->deleteModification($modification_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function refresh() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if ($this->validate()) {
			// Just before files are deleted, if config settings say maintenance mode is off then turn it on
			$maintenance = $this->config->get('config_maintenance');

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', true);

			//Log
			$log = array();
			$error_log = array();

			// Clear vqmod cache
			$vqmod_path = substr(DIR_SYSTEM, 0, -7) . 'vqmod/';

			if (file_exists($vqmod_path)) {
				$vqmod_cache = glob($vqmod_path.'vqcache/vq*');

				if ($vqmod_cache) {
					foreach ($vqmod_cache as $file) {
						if (file_exists($file)) {
							@unlink($file);
						}
					}
				}

				if (file_exists($vqmod_path.'mods.cache')) {
					@unlink($vqmod_path.'mods.cache');
				}

				if (file_exists($vqmod_path.'checked.cache')) {
					@unlink($vqmod_path.'checked.cache');
				}
			}
      
			// Clear all modification files
			$files = array();

			// Make path into an array
			$path = array(DIR_MODIFICATION . '*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Reverse sort the file array
			rsort($files);

			// Clear vqmod cache
			$vqmod_path = substr(DIR_SYSTEM, 0, -7) . 'vqmod/';

			if (file_exists($vqmod_path)) {
				$vqmod_cache = glob($vqmod_path.'vqcache/vq*');

				if ($vqmod_cache) {
					foreach ($vqmod_cache as $file) {
						if (file_exists($file)) {
							@unlink($file);
						}
					}
				}

				if (file_exists($vqmod_path.'mods.cache')) {
					@unlink($vqmod_path.'mods.cache');
				}

				if (file_exists($vqmod_path.'checked.cache')) {
					@unlink($vqmod_path.'checked.cache');
				}
			}
      
			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);

					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}

			// Begin
			$xml = array();

			// Load the default modification XML
			$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

			// This is purly for developers so they can run mods directly and have them run without upload sfter each change.
			$files = glob(DIR_SYSTEM . '*.ocmod.xml');

			if ($files) {
				foreach ($files as $file) {
					$xml[] = file_get_contents($file);
				}
			}

			// Get the default modification file
			$results = $this->model_extension_modification->getModifications();

			foreach ($results as $result) {
				if ($result['status']) {
					$xml[] = $result['xml'];
				}
			}

			$modification = array();

			foreach ($xml as $xml) {
				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->preserveWhiteSpace = false;
				$dom->loadXml($xml);

				// Log
				$log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;
				$error_log_mod = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

				// Wipe the past modification store in the backup array
				$recovery = array();

				// Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
				if (isset($modification)) {
					$recovery = $modification;
				}

				$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

				foreach ($files as $file) {
					$operations = $file->getElementsByTagName('operation');

					$files = explode('|', $file->getAttribute('path'));

					foreach ($files as $file) {
						$path = '';

						// Get the full path of the files that are going to be used for modification
						if (substr($file, 0, 7) == 'catalog') {
							$path = DIR_CATALOG . str_replace('../', '', substr($file, 8));
						}

						if (substr($file, 0, 5) == 'admin') {
							$path = DIR_APPLICATION . str_replace('../', '', substr($file, 6));
						}

						if (substr($file, 0, 6) == 'system') {
							$path = DIR_SYSTEM . str_replace('../', '', substr($file, 7));
						}

						if ($path) {
							$files = glob($path, GLOB_BRACE);

							if ($files) {
								foreach ($files as $file) {
									// Get the key to be used for the modification cache filename.
									if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
										$key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
									}

									if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
										$key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
									}

									if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
										$key = 'system/' . substr($file, strlen(DIR_SYSTEM));
									}

									// If file contents is not already in the modification array we need to load it.
									if (!isset($modification[$key])) {
										$content = file_get_contents($file);

										$modification[$key] = preg_replace('~\r?\n~', "\n", $content);
										$original[$key] = preg_replace('~\r?\n~', "\n", $content);

										// Log
										$log[] = 'FILE: ' . $key;
									}

									foreach ($operations as $operation) {
										$error = $operation->getAttribute('error');

										// Ignoreif
										$ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

										if ($ignoreif) {
											if ($ignoreif->getAttribute('regex') != 'true') {
												if (strpos($modification[$key], $ignoreif->textContent) !== false) {
													continue;
												}
											} else {
												if (preg_match($ignoreif->textContent, $modification[$key])) {
													continue;
												}
											}
										}

										$status = false;

										// Search and replace
										if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
											// Search
											$search = $operation->getElementsByTagName('search')->item(0)->textContent;
											$trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
											$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

											// Trim line if no trim attribute is set or is set to true.
											if (!$trim || $trim == 'true') {
												$search = trim($search);
											}

											// Add
											$add = $operation->getElementsByTagName('add')->item(0)->textContent;
											$trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
											$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
											$offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

											if ($offset == '') {
												$offset = 0;
											}

											// Trim line if is set to true.
											if ($trim == 'true') {
												$add = trim($add);
											}

											// Log
											$log[] = 'CODE: ' . $search;

											// Check if using indexes
											if ($index !== '') {
												$indexes = explode(',', $index);
											} else {
												$indexes = array();
											}

											// Get all the matches
											$i = 0;

											$lines = explode("\n", $modification[$key]);

											for ($line_id = 0; $line_id < count($lines); $line_id++) {
												$line = $lines[$line_id];

												// Status
												$match = false;

												// Check to see if the line matches the search code.
												if (stripos($line, $search) !== false) {
													// If indexes are not used then just set the found status to true.
													if (!$indexes) {
														$match = true;
													} elseif (in_array($i, $indexes)) {
														$match = true;
													}

													$i++;
												}

												// Now for replacing or adding to the matched elements
												if ($match) {
													switch ($position) {
														default:
														case 'replace':
															$new_lines = explode("\n", $add);

															if ($offset < 0) {
																array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));

																$line_id -= $offset;
															} else {
																array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
															}

															break;
														case 'before':
															$new_lines = explode("\n", $add);

															array_splice($lines, $line_id - $offset, 0, $new_lines);

															$line_id += count($new_lines);
															break;
														case 'after':
															$new_lines = explode("\n", $add);

															array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

															$line_id += count($new_lines);
															break;
													}

													// Log
													$log[] = 'LINE: ' . $line_id;

													$status = true;
												}
											}

											$modification[$key] = implode("\n", $lines);
										} else {
											$search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
											$limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
											 $quote = $operation->getElementsByTagName('search')->item(0)->getAttribute('quote');
											$replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

											// Limit
											if (!$limit) {
												$limit = -1;
											}

// Quote
if ($quote == 'true') {
    $search = preg_quote($search);
}

											// Log
											$match = array();

											preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

											// Remove part of the the result if a limit is set.
											if ($limit > 0) {
												$match[0] = array_slice($match[0], 0, $limit);
											}

											if ($match[0]) {
												$log[] = 'REGEX: ' . $search;

												for ($i = 0; $i < count($match[0]); $i++) {
													$log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
												}

												$status = true;
											}

											// Make the modification
											$modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
										}

										if (!$status) {
											if ($error != 'skip') {
												$error_log[] = '----------------------------------------------------------------';
												$error_log[] = 'NOT FOUND!';
												$error_log[] = 'CODE: ' . $search;
												$error_log[] = 'FILE: ' . $key;
												$error_log[] = $error_log_mod;
											}

											// Log
											$log[] = 'NOT FOUND!';

											// Skip current operation
											if ($error == 'skip') {
											continue;
											}

											// Abort applying this modification completely.
											if ($error == 'abort') {
												$modification = $recovery;

												// Log
												$log[] = 'ABORTING!';

												break 5;
											}
										}
									}
								}
							}
						}
					}
				}

				// Log
				$log[] = '----------------------------------------------------------------';
			}

			// Log
			$ocmod = new Log('ocmod.log');
			$ocmod->write(implode("\n", $log));

			// Write all modification files
			foreach ($modification as $key => $value) {
				// Only create a file if there are changes
				if ($original[$key] != $value) {
					$path = '';

					$directories = explode('/', dirname($key));

					foreach ($directories as $directory) {
						$path = $path . '/' . $directory;

						if (!is_dir(DIR_MODIFICATION . $path)) {
							@mkdir(DIR_MODIFICATION . $path, 0777);
						}
					}

					$handle = fopen(DIR_MODIFICATION . $key, 'w');

					fwrite($handle, $value);

					fclose($handle);
				}
			}

			// Maintance mode back to original settings
			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $maintenance);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['modification_id'])) {
				$this->response->redirect($this->url->link('extension/modification/edit', 'token=' . $this->session->data['token'] . '&modification_id=' . $this->request->get['modification_id'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->getList();
	}

	public function clear() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if ($this->validate()) {
			$files = array();

			// Make path into an array
			$path = array(DIR_MODIFICATION . '*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Reverse sort the file array
			rsort($files);

			// Clear vqmod cache
			$vqmod_path = substr(DIR_SYSTEM, 0, -7) . 'vqmod/';

			if (file_exists($vqmod_path)) {
				$vqmod_cache = glob($vqmod_path.'vqcache/vq*');

				if ($vqmod_cache) {
					foreach ($vqmod_cache as $file) {
						if (file_exists($file)) {
							@unlink($file);
						}
					}
				}

				if (file_exists($vqmod_path.'mods.cache')) {
					@unlink($vqmod_path.'mods.cache');
				}

				if (file_exists($vqmod_path.'checked.cache')) {
					@unlink($vqmod_path.'checked.cache');
				}
			}
      
			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);

					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function enable() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if (isset($this->request->get['modification_id']) && $this->validate()) {
			$this->model_extension_modification->enableModification($this->request->get['modification_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function disable() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if (isset($this->request->get['modification_id']) && $this->validate()) {
			$this->model_extension_modification->disableModification($this->request->get['modification_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function clearlog() {
		$this->load->language('extension/modification');

		if ($this->validate()) {

			if (file_exists(DIR_LOGS . 'ocmod_error.log')) {
				$handle = fopen(DIR_LOGS . 'ocmod_error.log', 'w+');

				fclose($handle);
			}
			$handle = fopen(DIR_LOGS . 'ocmod.log', 'w+');

			fclose($handle);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
      	if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

      	if (isset($this->request->get['filter_xml'])) {
			$filter_xml = $this->request->get['filter_xml'];
		} else {
			$filter_xml = null;
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
		}
      
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_modified';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

      	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_xml'])) {
			$url .= '&filter_xml=' . urlencode(html_entity_decode($this->request->get['filter_xml'], ENT_QUOTES, 'UTF-8'));
		}
      
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['refresh'] = $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['filter_action'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
		$data['add'] = $this->url->link('extension/modification/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['clear'] = $this->url->link('extension/modification/clear', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/modification/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['modifications'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_author'	  => $filter_author,
			'filter_xml'	  => $filter_xml,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$modification_total = $this->model_extension_modification->getTotalModifications($filter_data);

		$results = $this->model_extension_modification->getModifications($filter_data);

		foreach ($results as $result) {
			$data['modifications'][] = array(
				'modification_id' => $result['modification_id'],
				'name'            => $result['name'],
				'author'          => $result['author'],
				'version'         => $result['version'],
				'status'          => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'link'            => $result['link'],
				'date_modified'      => (date('Ymd') == date('Ymd', strtotime($result['date_modified'])) ? date('G:i', strtotime($result['date_modified'])) : date($this->language->get('date_format_short'), strtotime($result['date_modified']))),
				'edit'			  => $this->url->link('extension/modification/edit', 'token=' . $this->session->data['token'] . '&modification_id=' . $result['modification_id'], 'SSL'),
				'enable'          => $this->url->link('extension/modification/enable', 'token=' . $this->session->data['token'] . '&modification_id=' . $result['modification_id'], 'SSL'),
				'disable'         => $this->url->link('extension/modification/disable', 'token=' . $this->session->data['token'] . '&modification_id=' . $result['modification_id'], 'SSL'),
				'enabled'         => $result['status'],
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_refresh'] = $this->language->get('text_refresh');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_version'] = $this->language->get('column_version');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

      	$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_xml'] = $this->language->get('entry_xml');

		$data['tab_error'] = $this->language->get('tab_error');
		$data['tab_files'] = $this->language->get('tab_files');

		$data['column_date_modified'] = $this->language->get('column_date_modified');

      	$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_reset'] = $this->language->get('button_reset');
      
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_link'] = $this->language->get('button_link');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_log'] = $this->language->get('tab_log');

		$data['token'] = $this->session->data['token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

      	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_xml'])) {
			$url .= '&filter_xml=' . urlencode(html_entity_decode($this->request->get['filter_xml'], ENT_QUOTES, 'UTF-8'));
		}
      
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url, 'SSL');
		$data['sort_author'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$data['sort_version'] = $this->url->link('extension/version', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

      	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_xml'])) {
			$url .= '&filter_xml=' . urlencode(html_entity_decode($this->request->get['filter_xml'], ENT_QUOTES, 'UTF-8'));
		}
      
		$pagination = new Pagination();
		$pagination->total = $modification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($modification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($modification_total - $this->config->get('config_limit_admin'))) ? $modification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $modification_total, ceil($modification_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;

		$data['filter_name'] = $filter_name;
		$data['filter_author'] = $filter_author;
		$data['filter_xml'] = $filter_xml;

		$data['modified_files'] = array();

		$modified_files = self::modifiedFiles(DIR_MODIFICATION);

		$modification_files = $this->getModificationXmlFiles();

		foreach($modified_files as $modified_file) {
			if(isset($modification_files[$modified_file])){
				$modifications = $modification_files[$modified_file];
			} else {
				$modifications = array();
			}

			$data['modified_files'][] = array(
				'file' => $modified_file,
				'modifications' => $modifications
			);
		}

		$data['order'] = $order;

		// Log
  		$error_file = DIR_LOGS . 'ocmod_error.log';

		if (file_exists($error_file)) {
			$data['error_log'] = htmlentities(file_get_contents($error_file, FILE_USE_INCLUDE_PATH, null));
		} else {
			$data['error_log'] = '';
		}
		
		$file = DIR_LOGS . 'ocmod.log';

		if (file_exists($file)) {
			$data['log'] = htmlentities(file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
		} else {
			$data['log'] = '';
		}

		$data['clear_log'] = $this->url->link('extension/modification/clearlog', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/modification_list.tpl', $data));
	}


	public function add() {
		$this->load->language('extension/modification');

		$this->load->model('extension/modification');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$xml = html_entity_decode($this->request->post['xml'], ENT_QUOTES, 'UTF-8');

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->loadXml($xml);

			$data = array(
				'version' => '',
				'author' => '',
				'link' => '',
				'status' => 1
			);

			$data['xml'] = $xml;

			$data['name'] = $dom->getElementsByTagName('name')->item(0)->textContent;

			$data['code'] = $dom->getElementsByTagName('code')->item(0)->textContent;

			if ($dom->getElementsByTagName('version')->length) {
				$data['version'] = $dom->getElementsByTagName('version')->item(0)->textContent;
			}

			if ($dom->getElementsByTagName('author')->length) {
				$data['author'] = $dom->getElementsByTagName('author')->item(0)->textContent;
			}

			$this->model_extension_modification->addModification($data);

			$modification_id = $this->db->getLastId();

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '&modification_id=' . $modification_id;

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/modification/edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/modification');

		$this->load->model('extension/modification');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !empty($this->request->get['modification_id']) && $this->validateForm()) {
			$xml = html_entity_decode($this->request->post['xml'], ENT_QUOTES, 'UTF-8');

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->loadXml($xml);

			$data = array();

			$data['xml'] = $xml;

			$data['name'] = $dom->getElementsByTagName('name')->item(0)->textContent;

			$data['code'] = $dom->getElementsByTagName('code')->item(0)->textContent;

			if ($dom->getElementsByTagName('version')->length) {
				$data['version'] = $dom->getElementsByTagName('version')->item(0)->textContent;
			} else {
				$data['version'] = '';
			}

			if ($dom->getElementsByTagName('author')->length) {
				$data['author'] = $dom->getElementsByTagName('author')->item(0)->textContent;
			} else {
				$data['author'] = '';
			}

			if ($dom->getElementsByTagName('link')->length) {
				$data['link'] = $dom->getElementsByTagName('link')->item(0)->textContent;
			} else {
				$data['link'] = '';
			}

			$this->model_extension_modification->editModification($this->request->get['modification_id'], $data);
			
			$url = '&modification_id=' . $this->request->get['modification_id'];

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['refresh'])) {
				$this->response->redirect($this->url->link('extension/modification/refresh', 'modification_id=' . $this->request->get['modification_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			if ($this->db->countAffected()) {
				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('extension/modification/edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->getForm();
	}

	public function getForm() {
		$data['token'] = $this->session->data['token'];

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif (!empty($this->error)) {
			$data['error_warning'] = $this->language->get('error_warning');
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = false;
		}

		if (isset($this->error['xml'])) {
			$data['error_xml'] = $this->error['xml'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->request->get['modification_id'])) {
			$modification_info = $this->model_extension_modification->getModification($this->request->get['modification_id']);

			$data['text_form'] = sprintf($this->language->get('text_edit'), $modification_info['name']);

			$data['action'] = $this->url->link('extension/modification/edit', '&modification_id=' . $modification_info['modification_id'] . '&token=' . $this->session->data['token'], 'SSL');
			
			$data['refresh'] = $this->url->link('extension/modification/edit', '&modification_id=' . $modification_info['modification_id'] . '&refresh=1&token=' . $this->session->data['token'], 'SSL');

			$this->document->setTitle($modification_info['name'] . ' » ' . $data['heading_title']);
		} else {
			$data['text_form'] = $this->language->get('text_add');
			
			$data['refresh'] = false;

			$data['action'] = $this->url->link('extension/modification/add', 'token=' . $this->session->data['token'], 'SSL');

			$this->document->setTitle($data['heading_title']);
		}

		$data['cancel'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');

		$data['modification'] = array();

		if (!empty($modification_info)) {
			$data['modification']['status'] = $modification_info['status'];
		} else {
			$data['modification']['status'] = 0;
		}

		if (isset($this->request->post['xml'])) {
			$data['modification']['xml'] = html_entity_decode($this->request->post['xml'], ENT_QUOTES, 'UTF-8');
		} elseif (!empty($modification_info)) {
			$data['modification']['xml'] = $modification_info['xml'];
		} else {
			$data['modification']['xml'] = <<<'EOT'
<?xml version="1.0" encoding="utf-8"?>
<modification>
  <name></name>
  <code></code>
  <version></version>
  <author></author>
  <link></link>
<!--
<file path="">
	<operation>
      <search index="0"></search>
      <add position="replace"></add>
    </operation>
  </file>
-->
</modification>
EOT;
		}

		$this->document->addStyle('view/javascript/codemirror/lib/codemirror.css');
		$this->document->addScript('view/javascript/codemirror/lib/codemirror.js');
		$this->document->addScript('view/javascript/codemirror/mode/xml/xml.js');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/modification_form.tpl', $data));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$error = false;

		// Required
		if (empty($this->request->post['xml'])) {
			$error = $this->language->get('error_required');
		}

		// 2. Validate XML
		if (!$error) {
			libxml_use_internal_errors(true);

			$dom = new DOMDocument('1.0', 'UTF-8');

			if(!$dom->loadXml(html_entity_decode($this->request->post['xml'], ENT_QUOTES, 'UTF-8'))){

			    foreach (libxml_get_errors() as $error) {
			        $msg = '';

			        switch ($error->level) {
			            case LIBXML_ERR_WARNING :
			                $msg .= "Warning $error->code: ";
			                break;
			            case LIBXML_ERR_ERROR :
			                $msg .= "Error $error->code: ";
			                break;
			            case LIBXML_ERR_FATAL :
			                $msg .= "Fatal Error $error->code: ";
			                break;
			        }

			        $msg .= trim ( $error->message ) . "
Line: $error->line";

			        $error = $msg;
			    }

			    libxml_clear_errors();
			}

			libxml_use_internal_errors(false);
		}

		// 3. Required tags
		if (!$error && (!$dom->getElementsByTagName('name') || $dom->getElementsByTagName('name')->length == 0 || $dom->getElementsByTagName('name')->item(0)->textContent == '')) {
			$error = $this->language->get('error_name');
		}

		if (!$error && (!$dom->getElementsByTagName('code') || $dom->getElementsByTagName('code')->length == 0 || $dom->getElementsByTagName('code')->item(0)->textContent == '')) {
			$error = $this->language->get('error_code');
		}

		// 4. Check code isn't duplicate
		if (!$error) {
			$code = $dom->getElementsByTagName('code')->item(0)->textContent;

			$this->load->model('extension/modification');
			$modification_info = $this->model_extension_modification->getModificationByCode($code);

			if ($modification_info && (!isset($this->request->get['modification_id']) || $modification_info['modification_id'] != $this->request->get['modification_id'])) {
				$error = sprintf($this->language->get('error_exists'), $modification_info['name'], $modification_info['code']);
			}
		}

		if ($error) {
			$this->error['xml'] = $error;
		}

		return !$this->error;
	}

	static function modifiedFiles($dir, $dirLen = 0) {
		$tree = glob(rtrim($dir, '/') . '/*');
		if (!$dirLen) {
			$dirLen = strlen($dir);
		}
		$files = array();

	    if (is_array($tree)) {
	        foreach($tree as $file) {
	        	if ($file == $dir . 'index.html') {
					continue;
				} elseif (is_file($file)) {
	                $files[] = substr($file, $dirLen);
	            } elseif (is_dir($file)) {
	                $files = array_merge($files, self::modifiedFiles($file, $dirLen));
	            }
	        }
	    }

	    return $files;
	}

	protected function getModificationXmlFiles() {
		$return = array();

		$baseLen = strlen(substr(DIR_SYSTEM, 0, -7));

		$xml = array();

		$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

		$files = glob(DIR_SYSTEM . '*.ocmod.xml');

		if ($files) {
			foreach ($files as $file) {
				$xml[] = file_get_contents($file);
			}
		}

		$results = $this->model_extension_modification->getModifications();

		foreach ($results as $result) {
			if ($result['status']) {
				$xml[] = $result['xml'];
			}
		}

		foreach ($xml as $xml) {
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->loadXml($xml);

			$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

			foreach ($files as $file) {
				$operations = $file->getElementsByTagName('operation');

				$files = explode(',', $file->getAttribute('path'));

				foreach ($files as $file) {
					$path = '';

					// Get the full path of the files that are going to be used for modification
					if (substr($file, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . str_replace('../', '', substr($file, 8));
					}

					if (substr($file, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . str_replace('../', '', substr($file, 6));
					}

					if (substr($file, 0, 6) == 'system') {
						$path = DIR_SYSTEM . str_replace('../', '', substr($file, 7));
					}

					if ($path) {
						$files = glob($path, GLOB_BRACE);

						if ($files) {
							foreach ($files as $file) {
								$file = substr($file, $baseLen);

								if (!isset($return[$file])) {
									$return[$file] = array();
								}

								$return[$file][] = array(
									'code' => $dom->getElementsByTagName('code')->item(0)->textContent,
									'name' => $dom->getElementsByTagName('name')->item(0)->textContent,
									'author' => $dom->getElementsByTagName('author')->item(0)->textContent
								);
							}
						}
					}
				}
			}
		}

		return $return;
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
