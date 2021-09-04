<?php
namespace Base;


class AbstractController extends View {

	public function authUser(int $id) {
		$_SESSION['user_id'] = $id;
	}

	public function getUserId() {
		return $_SESSION['user_id'] ?? false;
	}

	public function redirect($path) {
		$url = $_SERVER['HTTP_ORIGIN'].'/mvc/'.$path;
		header('Location: ' . $url);
	}

}