<?php
	
	require_once EXTENSIONS . '/symquery/extension.driver.php';
	
	class Extension_QuickExport extends Extension {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
		
		public function about() {
			return array(
				'name'			=> 'Quick Export',
				'version'		=> '1.0.0',
				'release-date'	=> '2010-02-01',
				'author'		=> array(
					'name'			=> 'Rowan Lewis',
					'website'		=> 'http://rowanlewis.com/',
					'email'			=> 'me@rowanlewis.com'
				),
				'description'	=> 'Ads an export option to the with-selected dropdown.'
			);
		}
		
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				)
			);
		}
		
		public function initaliseAdminPageHead($context) {
			$admin = Administration::instance();
			$admin->Page->addScriptToHead(URL . '/extensions/quickexport/assets/publish.js', 3112401);
		}
		
	/*-------------------------------------------------------------------------
		Exporting:
	-------------------------------------------------------------------------*/
		
		public function export(Array $entries) {
			$db = Symphony::Database();
			$sm = new SectionManager(Administration::instance());
			$section_id = $db->fetchVar('section_id', 0, sprintf(
				'
					SELECT
						e.section_id
					FROM
						`tbl_entries` AS e
					WHERE
						e.id = %d
					LIMIT 1
				',
				$entries[0]
			));
			$section = $sm->fetch($section_id);
			$fields = $section->fetchFields();
			$query = SymRead($section);
			
			// All fields, all field modes:
			foreach ($fields as $field) {
				$modes = $field->fetchIncludableElements();
				
				foreach ($modes as $mode) $query->get($mode);
			}
			
			// Only specified entries:
			foreach ($entries as $entry) {
				$query->where(SymQuery::SYSTEM_ID, $entry, SymQuery::FILTER_OR);
			}
			
			$document = $query->readDOMDocument('export');
			$document->formatOutput = false;
			$output = $document->saveXML();
			
			header('Content-Type: application/octet-stream');
			header(sprintf(
				'Content-Disposition: attachment; filename="%s.symphonyExport"', $section->get('name')
			));
			header(sprintf(
				'Content-Length: %d', strlen($output)
			));
			
			echo $output; exit;
		}
	}
		
?>