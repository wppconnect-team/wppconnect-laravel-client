# WPPConnect Team
## _Wppconnect Laravel Client_

Uma API simples com empacotador Guzzle, fornecendo acesso fácil aos endpoints do WPPConnect Server.

## Nossos canais online

[![Discord](https://img.shields.io/discord/844351092758413353?color=blueviolet&label=Discord&logo=discord&style=flat)](https://discord.gg/Zp87zesMPY)
[![Telegram Group](https://img.shields.io/badge/Telegram-Group-32AFED?logo=telegram)](https://t.me/wppconnect)
[![WhatsApp Group](https://img.shields.io/badge/WhatsApp-Group-25D366?logo=whatsapp)](https://chat.whatsapp.com/C1ChjyShl5cA7KvmtecF3L)
[![YouTube](https://img.shields.io/youtube/channel/subscribers/UCD7J9LG08PmGQrF5IS7Yv9A?label=YouTube)](https://www.youtube.com/c/wppconnect)

# Requisitos

* PHP 7.4 ou superior.
* Laravel [8.x](https://laravel.com/docs/8.x) ou superior.

## Intalação - Laravel

Baixe o pacote com o Composer (Packagist), utilizando o seguinte comando:

``` bash
$ composer require wppconnect-team/wppconnect-laravel-client
```

Registre o WppconnectServiceProvider nos providers dentro de `config/app.php`:

``` php
 WPPConnectTeam\Wppconnect\WppconnectServiceProvider::class
```

Publique os arquivos do vendo (arquivo de configuração):
``` bash
$ php artisan vendor:publish
```

**Opcional**
Registre o facade em `config/app.php`:
``` php
'Wppconnect' => WPPConnectTeam\Wppconnect\Facades\Wppconnect::class
```

## Configuração

Configuração aplicada a todas as solicitações criadas pela API.

Exemplo:
``` php
'defaults' => [
     /**
      * URL do WPPConnect Server
      */
     'base_uri' => 'http://192.168.0.39:21465',

     /**
      * Secret Key
      * Veja: https://github.com/wppconnect-team/wppconnect-server#secret-key
      */
     'secret_key' => 'MYKeYPHP'
 ]
```

## Uso

Utilize este pacote sem qualquer configuração com o Wppconnect facade em seu controlador, ou, injete-o na classe onde o cliente se faz necessário:

```php
/**
 * @var RequestInterface
 */
protected $client;

/**
 * @param Wppconnect $client
 */
public function __construct(Wppconnect $client)
{
    $this->client = $client;
}
```

**Exemplo com o Facade:**

``` php
class WppconnectController extends Controller
{

    protected $url;
    protected $key;
    protected $session;

    /**
     * __construct function
     */
    public function __construct()
    {
        $this->url = config('wppconnect.defaults.base_uri');
        $this->key = config('wppconnect.defaults.secret_key');
	$this->session = "mySession";
    }

    public function index(){

	#Function: Generated Token
	# /api/:session/generate-token
	
        //Session::flush();
        if(!Session::get('token') and !Session::get('session')):
            Wppconnect::make($this->url);
            $response = Wppconnect::to('/api/'.$this->session.'/'.$this->key.'/generate-token')->asJson()->post();
            $response = json_decode($response->getBody()->getContents(),true);
            if($response['status'] == 'Success'):
                Session::put('token', $response['token']);
                Session::put('session', $response['session']);
            endif;
        endif;

	#Function: Start Session 
	# /api/:session/start-session
		
        if(Session::get('token') and Session::get('session') and !Session::get('init')):
            Wppconnect::make($this->url);
            $response = Wppconnect::to('/api/'.Session::get('session').'/start-session')->withHeaders([
                'Authorization' => 'Bearer '.Session::get('token')
            ])->asJson()->post();
            $response = json_decode($response->getBody()->getContents(),true);
            Session::put('init', true);
        endif;
	
    }
 }
 ```
 ``` php
	#Function: Check Connection Session
	# /api/:session/check-connection-session
		
	if(Session::get('token') and Session::get('session') and Session::get('init')):
	    Wppconnect::make($this->url);
	    $response = Wppconnect::to('/api/'. Session::get('session').'/check-connection-session')->withHeaders([
		'Authorization' => 'Bearer '.Session::get('token')
	    ])->asJson()->get();
	    $response = json_decode($response->getBody()->getContents(),true);
	    dd($response);
	endif;
 ```
 ``` php
	#Function: Close Session
	# /api/:session/close-session

	if(Session::get('token') and Session::get('session') and Session::get('init')):
	    Wppconnect::make($this->url);
	    $response = Wppconnect::to('/api/'. Session::get('session').'/close-session')->withHeaders([
		'Authorization' => 'Bearer '.Session::get('token')
	    ])->asJson()->post();
	    $response = json_decode($response->getBody()->getContents(),true);
	    dd($response);
	endif;
 ```
 ``` php
	#Function: Send Message
	# /api/:session/send-message
		
	if(Session::get('token') and Session::get('session') and Session::get('init')):
	    Wppconnect::make($this->url);
	    $response = Wppconnect::to('/api/'. Session::get('session').'/send-message')->withBody([
		'phone' => '5500000000000',
		'message' => 'Opa, funciona mesmo!'
	    ])->withHeaders([
		'Authorization' => 'Bearer '.Session::get('token')
	    ])->asJson()->post();
	    $response = json_decode($response->getBody()->getContents(),true);
	    dd($response);
	endif;
 ```
 ``` php	
	#Function: Send File Base64
	# /api/:session/send-file-base64
		
	if(Session::get('token') and Session::get('session') and Session::get('init')):
	    Wppconnect::make($this->url);
	    $response = Wppconnect::to('/api/'. Session::get('session').'/send-file-base64')->withBody([
		'phone' => '5500000000000',
		'base64' => 'data:image/jpg;base64,' . base64_encode(file_get_contents(resource_path('/img/xpto.jpg')))
	    ])->withHeaders([
		'Authorization' => 'Bearer '.Session::get('token')
	    ])->asJson()->post();
	    $response = json_decode($response->getBody()->getContents(),true);
	    dd($response);
	endif;
```

# Debug

Usar `debug(bool|resource)` antes de enviar uma solicitação para ativar o depurador do Guzzle. Para mais informações acesse a [documentação](http://docs.guzzlephp.org/en/stable/request-options.html#debug).

O debug é desligado após cada solicitação, se você precisar depurar várias solicitações enviadas sequencialmente, será necessário ativar a depuração para todas elas.

**Exemplo**

```php
$logFile = './client_debug_test.log';
$logFileResource = fopen($logFile, 'w+');

$this->client->debug($logFileResource)->to('post')->withBody([
	'foo' => 'bar'
])->asJson()->post();

fclose($logFileResource);
```
Os logs serão salvos no arquivo `client_debug_test.log`.

## Postman
Acesse o [Postman Collection do WPPConnect](https://www.postman.com/hbdbim/workspace/wppconnect-server) com todos os endpoints.
