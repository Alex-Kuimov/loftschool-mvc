<?php
namespace App\Controller;

use App\Model\Eloquent\User as UserModel;
use Base\AbstractController;

class User extends AbstractController {

	public function loginAction() {

		$auth = true;
		$text = '';

		if( $this->getUserId() ){
			$this->redirect('blog');
		}

		if ( isset( $_POST['email'] ) && isset( $_POST['password'] ) ) {
			$email = htmlspecialchars( $_POST['email'] );
			$password = htmlspecialchars( $_POST['password'] );

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

		return $this->render( 'User/login.phtml', $text );
    }

	public function registerAction() {

		$reg = true;
		$text = '';

		if( $this->getUserId() ){
			$this->redirect('blog');
		}

		if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) && isset( $_POST['password1'] ) && isset( $_POST['password2'] )) {

			$name = htmlspecialchars( $_POST['name'] );
			$email = htmlspecialchars( $_POST['email'] );
			$password1 = htmlspecialchars( $_POST['password1'] );
			$password2 = htmlspecialchars( $_POST['password2'] );

			if ( ! $name && ! $email && ! $password1 && ! $password2 ) {
				$text = 'Все поля обязательны!';
				$reg = false;
			}

			$user = UserModel::getByEmail($email);

			if ( $user ) {
				$text = 'Такой пользователь уже существует!';
				$reg = false;
			}

			if ( $password1 !== $password2 ) {
				$text = 'Пароли не совпадают!';
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

		return $this->render('User/register.phtml', $text );
	}

	public function logoutAction() {
		session_destroy();
		$this->redirect('login');
	}
}