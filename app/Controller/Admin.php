<?php

namespace App\Controller;

use Base\AbstractController;
use App\Model\Eloquent\User;

class Admin extends AbstractController{
	public function indexAction() {

		$data['msg'] = '';
		$data['users'] = '';

		if( ! $this->getUserId() ){
			$this->redirect('login');
		}

		if( ! User::isAdmin( $this->getUserId() ) ) {
			$this->redirect('blog');
		}

		$users = User::getList();
		if ( $users ) {
			$data['users'] = $users;
			$data['isAdmin'] = User::isAdmin( $this->getUserId() );
		}

		if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) && isset( $_POST['password1'] ) && isset( $_POST['password2'] )) {
			$name = htmlspecialchars( $_POST['name'] );
			$email = htmlspecialchars( $_POST['email'] );
			$password1 = htmlspecialchars( $_POST['password1'] );
			$password2 = htmlspecialchars( $_POST['password2'] );
			$reg = true;

			if ( ! $name && ! $email && ! $password1 && ! $password2 ) {
				$data['msg'] = 'Все поля обязательны!';
				$reg = false;
			}

			$user = User::getByEmail($email);

			if ( $user ) {
				$data['msg'] = 'Такой пользователь уже существует!';
				$reg = false;
			}

			if ( $password1 !== $password2 ) {
				$data['msg'] = 'Пароли не совпадают!';
				$reg = false;
			}

			if( $reg ){
				$user = new User();
				$user->setName($name);
				$user->setEmail($email);

				$password = User::getPasswordHash($password1);
				$user->setPassword($password);

				$user->save();

				$this->redirect('admin');
			}
		}

		return $this->render( 'Admin/index.phtml', $data );
	}

	public function editAction() {
		$data['msg'] = '';
		$data['user'] = '';

		if( ! $this->getUserId() ) {
			$this->redirect('login');
		}

		if( ! User::isAdmin( $this->getUserId() ) ) {
			$this->redirect('blog');
		}

		if( isset( $_GET['id'] ) ) {
			$userId = htmlspecialchars( $_GET['id'] );

			if ( isset( $_POST['name'] ) ) {
				$name = htmlspecialchars( $_POST['name'] );

				$update = true;

				if ( ! $name ) {
					$data['msg'] = 'Имя обязательное поле!';
					$update = false;
				}

				if ( $update ) {
					User::updateUserName( $userId, $name );
					$data['msg'] = 'Данные обновлены!';
				}
			}

			if ( isset( $_POST['password1'] ) && isset( $_POST['password2'] ) ) {
				$password1 = htmlspecialchars( $_POST['password1'] );
				$password2 = htmlspecialchars( $_POST['password2'] );

				$update = true;

				if ( empty( $password1 ) && empty( $password2 ) ) {
					$data['msg'] = 'Пароль не может быть пустым!';
					$update = false;
				}

				if ( $password1 !== $password2 ) {
					$data['msg'] = 'Пароли не совпадают!';
					$update = false;
				}

				if ( $update ) {
					$password = User::getPasswordHash( $password1 );
					User::updatePassword( $userId, $password );
					$data['msg'] = 'Пароль обновлен!';
				}
			}


			$user = User::getById( $userId );

			if ( $user ) {
				$data['user'] = $user;
			}

		}

		return $this->render( 'Admin/edit.phtml', $data );
	}

	public function deleteAction() {
		if( isset( $_GET['id'] ) && User::isAdmin( $this->getUserId() ) ){
			$userId = htmlspecialchars( $_GET['id'] );
			User::deleteUser( $userId );
			$this->redirect('admin');
		}
	}
}