<?php
namespace Base;

class View {
	public function render( $templatePath, $data=[] ){
		ob_start();
		include '../app/View/'.$templatePath;
		return ob_get_clean();
	}
}