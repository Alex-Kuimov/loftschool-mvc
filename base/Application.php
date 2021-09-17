<?php
namespace Base;

class Application {
	private $route;
	private $controller;
	private $content;

	public function __construct() {
		$this->route = new Route();
	}

	public function run() {
		$this->initSession();
		$this->initRoutes();
		$this->initControllers();
		$this->initAction();

		echo $this->content;
	}

	private function initRoutes() {
		$this->route->addRoute('','Main', 'indexAction');
		$this->route->addRoute('login','User', 'loginAction');
		$this->route->addRoute('logout','User', 'logoutAction');
		$this->route->addRoute('register','User', 'registerAction');
		$this->route->addRoute('profile','User', 'profileAction');
		$this->route->addRoute('blog','Blog', 'indexAction');
		$this->route->addRoute('blog/twig','Blog', 'twigAction');
		$this->route->addRoute('blog/delete','Blog', 'deleteAction');
		$this->route->addRoute('admin','Admin', 'indexAction');
		$this->route->addRoute('admin/edit','Admin', 'editAction');
		$this->route->addRoute('admin/delete','Admin', 'deleteAction');
		$this->route->addRoute('api','Api', 'getUserMessagesAction');
	}

	private function initControllers() {
		$this->route->routing();
		$controllerName = $this->route->getControllerName();

		if ( class_exists( $controllerName ) ) {
			$this->controller = new $controllerName();
		}
	}

	private function initAction(){
		$actionName = $this->route->getActionName();


		if ( method_exists( $this->controller, $actionName ) ) {
			$this->content = $this->controller->{$actionName}();
		}
	}

	private function initSession(){
		session_start();
	}

}

