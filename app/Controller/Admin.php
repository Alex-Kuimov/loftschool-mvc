<?php

namespace App\Controller;

use Base\AbstractController;
use App\Model\Eloquent\User;
use Base\Request;
use Base\View;

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

		$name = Request::post('name');
		$email = Request::post('email');
		$password1 = Request::post('password1');
		$password2 = Request::post('password2');

		if ( $name !== null && $email !== null && $password1 !== null && $password2 !== null ) {
			$reg = true;

			if ( ! $name && ! $email && ! $password1 && ! $password2 ) {
				$data['msg'] = 'Все поля обязательны!';
				$reg = false;
			}

			if ( $password1 !== $password2 ) {
				$data['msg'] = 'Пароли не совпадают!';
				$reg = false;
			}

			if( strlen( $password1 ) < 4 && strlen( $password2 ) < 4) {
				$data['msg'] = 'Минимальная длина пароля 4 символа';
				$reg = false;
			}

			if( $email && User::getByEmail( $email ) ){
				$data['msg'] = 'Такой пользователь уже существует!';
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

		return View::render( 'Admin/index.phtml', $data );
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

		$userId = Request::get('id');

		if ( $userId ) {
			$name = Request::post('name');
			$password1 = Request::post('password1');
			$password2 = Request::post('password2');
			$update = true;

			if( $name !== null || ( $password1 !== null && $password2 !== null ) ) {
				if ( $name ) {
					if ( strlen( $name ) < 0 ) {
						$data['msg'] = 'Имя обязательное поле!';
						$update = false;
					}

					if ( $update ) {
						User::updateUserName( $userId, $name );
						$data['msg'] = 'Данные обновлены!';
					}
				}

				if ( $password1 && $password2 ) {
					if ( $password1 !== $password2 ) {
						$data['msg'] = 'Пароли не совпадают!';
						$update = false;
					}

					if ( strlen( $password1 ) < 4 && strlen( $password2 ) < 4 ) {
						$data['msg'] = 'Минимальная длина пароля 4 символа';
						$update = false;
					}

					if ( $update ) {
						$password = User::getPasswordHash( $password1 );
						User::updatePassword( $userId, $password );
						$data['msg'] = 'Пароль обновлен!';
					}
				}
			}
			$user = User::getById( $userId );

			if ( $user ) {
				$data['user'] = $user;
			}

		}

		return View::render( 'Admin/edit.phtml', $data );
	}

	public function deleteAction() {
		$id = Request::get('id');

		if( $id && User::isAdmin( $this->getUserId() ) ){
			$userId = htmlspecialchars( $_GET['id'] );
			User::deleteUser( $userId );
			$this->redirect('admin');
		}
	}
}