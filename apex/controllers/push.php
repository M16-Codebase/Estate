<?php
	class Push extends MX_Controller {
		
		public function index($part=null)
    {
		$this->load->view('template/push_manifest.json');
	}
}