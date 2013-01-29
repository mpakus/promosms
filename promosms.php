<?php 

/**
 * Класс для отправки sms сообшения через сервис promosms.ru 
 *
 * @author Ibragimov "MpaK" Renat <info@mrak7.com> http://aomega.ru http://mrak7.com
 */
class Promosms{
	protected
		$settings   = array(),
		$last_error = ''
	;
	const
		url = 'http://sms.promosms.ru:26676/smw/aisms'
	;

	/**
	 * Инизиализация параметров 
	 *
	 * @param $settings array настройки от promosms
	 */
	public function __construct( $settings=array() ){
		if( empty($settings['user']) )     throw new Exception("Не указан пользовательский логин user", 100);
		if( empty($settings['password']) ) throw new Exception("Не указан пользовательский пароль password", 101);
		if( empty($settings['sender']) )   throw new Exception("Не указан отправитель sender точно как в кабинете", 102);
		$this->settings = array( 
			'user'   => $settings['user'],
			'pass'   => $settings['password'],
			'sender' => $settings['sender'],
		);
	}

	/**
	 * Отправка сообщения по номеру телефона
	 *
	 * @param $phone string номер телефона ex. 89053579666
	 * @param $message string текст sms сообщения
	 */
	public function send_to( $phone, $message ){
		$post = array(
			'user'    => $this->settings['user'],
			'pass'    => $this->settings['pass'],
			'gzip'    => 'none',			
			'action'  => 'post_sms',
			'target'  => '+'.$phone,
			'sender'  => $this->settings['sender'],
			'message' => $message,
		);
		$res = $this->send( $post );
		if( $res === FALSE ) throw new Exception("CURL Ошибка при отправке #".$this->last_error, 103);
	}

	protected function send( $post ){
		$ch = curl_init(); 
		curl_setopt( $ch, CURLOPT_URL, self::url ); 
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8") );
		$post = (is_array($post)) ? http_build_query($post) : $post;
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post ); 
		$res = curl_exec( $ch );
		if( $res === FALSE ) $this->last_error = curl_errno($ch);
		curl_close( $ch );
		return $res;	
	}
}
