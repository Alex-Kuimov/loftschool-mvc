<?php
namespace App\Controller;

use Base\AbstractController;

class Main extends AbstractController  {
	public function indexAction() {
		return $this->render('Main/index.phtml');
	}
}