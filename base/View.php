<?php
namespace Base;

class View {

	private $twig;

	public function render( $templatePath, $data=[] ){
		ob_start();
		include '../app/View/'.$templatePath;
		return ob_get_clean();
	}

	public function renderTwig( $templatePath, $data=[] ){
		$parts = explode( '/', $templatePath );
		$viewName = $parts[0];
		$templateName = $parts[1];

		if ( ! $this->twig ) {
			$loader = new \Twig\Loader\FilesystemLoader( '../app/View/' . $viewName );
			$this->twig = new \Twig\Environment( $loader );
		}

		return $this->twig->render( $templateName, $data );
	}
}