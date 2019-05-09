<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	require FCPATH . 'vendor/autoload.php';
	/**
	* Controller for handling search using solarium package 
	* using composer package in Vendor Folder
	*
	* 
	*/

	class Test extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->config->load('solarium');
			$this->endpoint1 = new Solarium\Client($this->config->item('endpoint1'));
			$this->endpoint2 = new Solarium\Client($this->config->item('endpoint2'));
		}

		/**
		* Select all the records
		*
		* Using solarium packages
		*/
		public function test_endpoint1() {
			$query = $this->endpoint1->createSelect();

			$result = $this->endpoint1->select($query);
			
			echo 'NumFound: '.$result->getNumFound() . PHP_EOL;

			foreach ($result as $document) {
			
			echo '<hr/><table border="1">';
			
			// the documents are also iterable, to get all fields
			foreach($document AS $field => $value)
			{
				// this converts multivalue fields to a comma-separated string
				if(is_array($value)) $value = implode(', ', $value);
				echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
			}
				echo '</table>';
			}
		}
		
		public function test_endpoint2() {
			$query = $this->endpoint2->createSelect();

			$result = $this->endpoint2->select($query);
			
			echo 'NumFound: '.$result->getNumFound() . PHP_EOL;

			foreach ($result as $document) {
			
			echo '<hr/><table border="1">';
			
			// the documents are also iterable, to get all fields
			foreach($document AS $field => $value)
			{
				// this converts multivalue fields to a comma-separated string
				if(is_array($value)) $value = implode(', ', $value);
				echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
			}
				echo '</table>';
			}
		}
	}
?>