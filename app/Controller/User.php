<?php
namespace App\Controller;

use App\Model\Eloquent\User as UserModel;
use Base\AbstractController;
use Base\Request;
use Base\View;

class User extends AbstractController {

	public function loginAction() {

		$auth = true;
		$text = '';

		if( $this->getUserId() ){
			$this->redirect('blog');
		}

		$email = Request::post('email');
		$password = Request::post('password');

		if ( $email && $password ) {
			$user = UserModel::getByEmail( $email );

			if ( ! $user ) {
				$text = 'Неверный логин и пароль';
				$auth = false;
			}
			
			if( $user->getPassword() !== UserModel::getPasswordHash( $password ) ){
				$text = 'Неверный логин и пароль';
				$auth = false;
			}

			if( $auth ) {
				$this->authUser( $user->getId() );
				$this->redirect('blog');
			}

		}

		return View::render( 'User/login.phtml', $text );
    }

	public function registerAction() {

		$reg = true;
		$text = '';

		if( $this->getUserId() ){
			$this->redirect('blog');
		}

		$name = Request::post('name');
		$email = Request::post('email');
		$password1 = Request::post('password1');
		$password2 = Request::post('password2');

		if ( $name !== null && $email !== null && $password1 !== null && $password2 !== null ) {

			if ( ! $name && ! $email && ! $password1 && ! $password2 ) {
				$text = 'Все поля обязательны!';
				$reg = false;
			}

			if ( $password1 !== $password2  ) {
				$text = 'Пароли не совпадают!';
				$reg = false;
			}

			if( strlen( $password1 ) < 4 && strlen( $password2 ) < 4) {
				$text = 'Минимальная длина пароля 4 символа';
				$reg = false;
			}

			if( $email && UserModel::getByEmail( $email ) ){
				$text = 'Такой пользователь уже существует!';
				$reg = false;
			}

			if( $reg ){
				$user = new UserModel();
				$user->setName($name);
				$user->setEmail($email);

				$password = UserModel::getPasswordHash($password1);
				$user->setPassword($password);

				$user->save();
				$this->authUser( $user->getId() );
				$this->redirect('blog');
			}

		}

		return View::render('User/register.phtml', $text );
	}

	public function logoutAction() {
		session_destroy();
		$this->redirect('login');
	}
}