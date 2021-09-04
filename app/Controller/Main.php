<?php
namespace App\Controller;

use Base\AbstractController;

class Main extends AbstractController  {

	private $data = [];

	public function indexAction() {

		$this->data['msg'] = '';

		return $this->render('Main/index.phtml', $this->data);
	}
}