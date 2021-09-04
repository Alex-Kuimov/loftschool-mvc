<?php
namespace Base;

class Route {

	private $controllerName;
	private $actionName;
	private $routes;

	public function routing(){
		$parts = parse_url($_SERVER['REQUEST_URI']);
		$path = $parts['path'];
		$parts = explode('/', $path);
		$page = $parts[2] ?? null;
		$action = $parts[3] ?? null;

		$routePage = $this->routes[$page]['page'] ?? null;

		if( $routePage === $page ) {

			$this->controllerName = 'App\\Controller\\'.$this->routes[$page]['controller'];
			$this->actionName = $this->routes[$page]['action'];

			if( $action ){
				$this->actionName = $action.'Action';
			}

		} else {
			$this->controllerName = 'App\\Controller\\Error404';
			$this->actionName = 'indexAction';
		}
	}

	public function addRoute($page, $controllerName, $actionName) {
		$this->routes[$page]['page'] = $page;
		$this->routes[$page]['controller'] = $controllerName;
		$this->routes[$page]['action'] = $actionName;
	}

	public function getControllerName() {
		return $this->controllerName;
	}

	public function getActionName() {
		return $this->actionName;
	}
}