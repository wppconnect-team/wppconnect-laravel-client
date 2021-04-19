# WPPConnect Team
## _Wppconnect Laravel Client_

A simple API with Guzzle wrapper, providing easy access to wppconnect's endpoints.

# Requirements

* PHP 7.4 or newer
* Laravel [8.x](https://laravel.com/docs/8.x) or newer

Note that the above requirements will always reflect the latest release. Older releases may support older PHP and Laravel versions.

## Install - Laravel

Require this package with Composer (Packagist), using the following command:

``` bash
$ composer require wppconnect-team/wppconnect-laravel-client
```

Register the GuzzleApiServiceProvider to the providers array in `config/app.php`:

``` php
 WPPConnectTeam\Wppconnect\WppconnectServiceProvider::class
```

Publish vendor files (config file):
``` bash
$ php artisan vendor:publish
```

**Optional**
Register the facade in `config/app.php`:
``` php
'Wppconnect' => WPPConnectTeam\Wppconnect\Facades\Wppconnect::class
```

## Config

Associative array of Request Options, that are applied to every request, created by the client.

Example:
``` php
'defaults' => [
     /**
      * Configures a base URL for the client so that requests created using a relative URL are combined with the base_url
      * See: http://guzzle.readthedocs.org/en/latest/quickstart.html#creating-a-client
      */
     'base_uri' => 'http://192.168.0.39:21465',

     /**
      * Secret Key
      * See: https://github.com/wppconnect-team/wppconnect-server#secret-key
      */
     'secret_key' => 'MYKeYPHP'
 ]
```

## Usage

You can use this package without any configuration. Just use the Wppconnect facade in your controller or Inject into the class where you need the client:

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

**Facade Example:**

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
        if(Session::get('token') and Session::get('session') and Session::get('init')):
            Wppconnect::make($this->url);
            $response = Wppconnect::to('/api/'. Session::get('session').'/send-message')->withBody([
                'phone' => '0000000000000',
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
	if(Session::get('token') and Session::get('session') and Session::get('init')):
	    Wppconnect::make($this->url);
	    $response = Wppconnect::to('/api/'. Session::get('session').'/send-file-base64')->withBody([
		'phone' => '0000000000000',
		'base64' => 'data:image/jpg;base64,' . base64_encode(file_get_contents(resource_path('/img/xpto.jpg')))
	    ])->withHeaders([
		'Authorization' => 'Bearer '.Session::get('token')
	    ])->asJson()->post();
	    $response = json_decode($response->getBody()->getContents(),true);
	    dd($response);
	endif;
```

# Debugging

Using `debug(bool|resource)` before sending a request turns on Guzzle's debugger, more information about that [here](http://docs.guzzlephp.org/en/stable/request-options.html#debug).

The debugger is turned off after every request, if you need to debug multiple requests sent sequentially you will need to turn on debugging for all of them.

**Example**

```php
$logFile = './client_debug_test.log';
$logFileResource = fopen($logFile, 'w+');

$this->client->debug($logFileResource)->to('post')->withBody([
	'foo' => 'bar'
])->asJson()->post();

fclose($logFileResource);
```

This writes Guzzle's debug information to `client_debug_test.log`.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[manual]: http://guzzle.readthedocs.org/en/latest/
