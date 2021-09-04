<?php
namespace App\Controller;

use App\Model\User as UserModel;
use Base\AbstractController;

class User extends AbstractController {

	private $msg = '';
	private $check = true;
	private $auth = true;

    public function loginAction() {

		if( $this->getUserId() ){
			$this->redirect('blog');
		}

		if ( isset( $_POST['email'] ) && isset( $_POST['password'] ) ) {
			$email = htmlspecialchars( $_POST['email'] );
			$password = htmlspecialchars( $_POST['password'] );

			$user = UserModel::getByEmail($email);

			if ( ! $user ) {
				$this->msg = 'Неверный логин и пароль';
				$this->auth = false;
			}
			
			if( $user->getPassword() !== UserModel::getPasswordHash( $password ) ){
				$this->msg = 'Неверный логин и пароль';
				$this->auth = false;
			}

			if($this->auth) {
				$this->authUser($user->getId());
			}

		}

		return $this->render( 'User/login.phtml', $this->msg );
    }

	public function registerAction() {

		if( $this->getUserId() ){
			$this->redirect('blog');
		}

		if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) && isset( $_POST['password1'] ) && isset( $_POST['password2'] )) {

			$name = htmlspecialchars( $_POST['name'] );
			$email = htmlspecialchars( $_POST['email'] );
			$password1 = htmlspecialchars( $_POST['password1'] );
			$password2 = htmlspecialchars( $_POST['password2'] );

			if ( ! $name && ! $email && ! $password1 && ! $password2 ) {
				$this->msg = 'Все поля обязательны!';
				$this->check = false;
			}

			$user = UserModel::getByEmail($email);

			if ( $user ) {
				$this->msg = 'Такой пользователь уже существует!';
				$this->check = false;
			}

			if ( $password1 !== $password2 ) {
				$this->msg = 'Пароли не совпадают!';
				$this->check = false;
			}

			if( $this->check ){
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

		return $this->render('User/register.phtml', $this->msg);
	}

	public function logoutAction() {
		session_destroy();
		$this->redirect('login');
	}
}