<?php
namespace App\Model;

use Base\Db;

class User {
    private $id;
    private $name;
	private $email;
    private $password;
    private $createdAt;

    public function __construct($data = []) {
        if ( $data ) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? null;
			$this->email = $data['email'] ?? null;
            $this->password = $data['password'] ?? null;
            $this->createdAt = $data['created_at'] ?? null;
        }
    }

    public function getName() {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function save() {
        $db = Db::getInstance();
        $insert = "INSERT INTO users (`name`, `email`, `password`) VALUES (
            :name, :email, :password
        )";

        $db->exec($insert, __METHOD__, [
            ':name' => $this->name,
			':email' => $this->email,
            ':password' => $this->password
        ]);

        $id = $db->lastInsertId();
        $this->id = $id;

        return $id;
    }

    public static function getById(int $id) {
        $db = Db::getInstance();
        $select = "SELECT * FROM users WHERE id = $id";
        $data = $db->fetchOne($select, __METHOD__);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

    public static function getByName(string $name) {
        $db = Db::getInstance();
        $select = "SELECT * FROM users WHERE `name` = :name";
        $data = $db->fetchOne($select, __METHOD__, [
            ':name' => $name
        ]);

        if (!$data) {
            return null;
        }

        return new self($data);
    }

	public static function getByEmail(string $email) {
		$db = Db::getInstance();
		$select = "SELECT * FROM users WHERE `email` = :email";
		$data = $db->fetchOne($select, __METHOD__, [
			':email' => $email
		]);

		if (!$data) {
			return null;
		}

		return new self($data);
	}

    public static function getPasswordHash(string $password) {
        return sha1('sdfsdf' . $password);
    }

	public static function isAdmin($user_id) {
		return in_array($user_id, ADMIN_IDS);
	}
}