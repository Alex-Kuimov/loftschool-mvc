<?php

namespace Base;

class TwigView {
	public static function  render( $templatePath, $data=[] ){
		$twig = null;

		$parts = explode( '/', $templatePath );
		$viewName = $parts[0];
		$templateName = $parts[1];

		if ( ! $twig ) {
			$loader = new \Twig\Loader\FilesystemLoader( '../app/View/' . $viewName );
			$twig = new \Twig\Environment( $loader );
		}

		return $twig->render( $templateName, $data );
	}

}