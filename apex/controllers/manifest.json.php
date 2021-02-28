<?php
	class Manifest extends MX_Controller {
		
		public function index($part=null)
    {
		$this->load->view('template/manifest.json');
	}
}