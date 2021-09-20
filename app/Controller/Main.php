<?php
namespace App\Controller;

use Base\AbstractController;
use Base\View;

class Main extends AbstractController  {
	public function indexAction() {
		return View::render('Main/index.phtml');
	}
}