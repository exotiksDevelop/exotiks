<?php 
class ModelModuleSmartNotifications extends Model {

	public function install($moduleName) {

		$this->load->model('design/layout');
		$layouts = array();
		$layouts = $this->model_design_layout->getLayouts();
			
		foreach ($layouts as $layout) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_module 
				SET layout_id = '" . (int)$layout['layout_id'] . "', code = '" . $this->db->escape($moduleName) . "', position = '" . 
				$this->db->escape('content_bottom') . "', sort_order = '0'");
			$this->event->trigger('post.admin.edit.layout', $layout['layout_id']);
		}
  	} 
  
  	public function uninstall($moduleName) {
		$this->load->model('design/layout');
		$layouts = array();
		$layouts = $this->model_design_layout->getLayouts();
			
		foreach ($layouts as $layout) {
			$this->db->query("DELETE FROM " . DB_PREFIX . 
				"layout_module 
				WHERE layout_id = '" . (int)$layout['layout_id'] . "' and  
				code = '" . $this->db->escape($moduleName)."'");
		}
  	}
	
  }
?>