# PromoSMS API, PHP класс

Простенький PHP класс для отравки sms сообщений через REST API от сервиса http://promosms.ru

Пример использования:
<pre><code>
require 'promosms.php';

// пример использования api promosms.ru
try{
	$sms = new Promosms( array('user'=>'{USER}', 'password'=>'{PASSWORD}', 'sender'=>'{SENDER}') );
	$sms->send_to( '79033540452', 'Привет мрачный, это тест смс отправки' );
}catch( Exception $e ){
	die( $e->getMessage() );
}	
</code></pre>