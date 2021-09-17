<?php
namespace App\Model\Eloquent;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'password',
        'email',
        'created_at',
    ];

    public static function getByEmail( $email ) {
        return self::query()->where('email', '=', $email)->first();
    }

	public static function getNameByID( $userID ) {
		return self::query()->select('name')->where('id', '=', $userID)->first();
	}

    public static function getById( $id ) {
        return self::query()->find($id);
    }

    public static function getList( $limit = 10, $offset = 0 ) {
        return self::query()->limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
    }

    public static function getPasswordHash( $password ) {
        return sha1('.,f.akjsduf' . $password);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

	public function setName( $name ) {
		$this->name = $name;
		return $this;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail( $email ) {
		$this->email = $email;
		return $this;
	}

    public function getPassword() {
        return $this->password;
    }

	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}

	public static function isAdmin( $user_id ) {
		return in_array($user_id, ADMIN_IDS);
	}

	public static function deleteUser( $userId ) {
		return self::destroy($userId);
	}

	public static function updateUserName( $userId, $name ) {
		return self::query()->where('id', '=', $userId)->update(['name'=>$name]);
	}
}