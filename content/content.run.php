<?php
	
	require_once(TOOLKIT . '/class.administrationpage.php');
	
	class ContentExtensionQuickExportRun extends AdministrationPage {
		protected $driver = null;
		protected $uri = null;
		
		public function __construct(&$parent){
			parent::__construct($parent);
			
			$this->uri = URL . '/symphony/extension/quickexport';
			$this->driver = $this->_Parent->ExtensionManager->create('quickexport');
		}
		
		public function __actionIndex() {
			// Exportable:
			if (is_array($_POST['items'])) {
				$document = $this->driver->export(array_keys($_POST['items']));
			}
			
			// Nothing selected:
			else if (isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER']) {
				$redirect = $_SERVER['HTTP_REFERER'];
			}
			
			else {
				$section = $this->driver->getAuditSection();
				$redirect = sprintf(
					'%s/symphony/publish/%s/',
					URL, $section->get('handle')
				);
			}
			
			if (isset($redirect)) redirect($redirect); exit;
		}
	}
	
?>
