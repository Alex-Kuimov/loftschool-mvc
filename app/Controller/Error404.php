<?php
namespace App\Controller;

use Base\View;;

class Error404 extends View {
	public function indexAction() {
		return $this->render( 'Error404/index.phtml' );
	}
}