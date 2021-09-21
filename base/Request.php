<?php
namespace Base;

class Request {
	public static function post( $name ) {
		if ( empty( $_POST ) ) {
			return null;
		}

		foreach ( $_POST as $key => $value ) {
			if ( $name === $key ) {
				return htmlspecialchars( $value );
			}
		}

		return false;
	}

	public static function get( $name ) {
		if ( empty( $_GET ) ) {
			return null;
		}

		foreach ( $_GET as $key => $value ) {
			if ( $name === $key ) {
				return htmlspecialchars( $value );
			}
		}

		return false;
	}
}