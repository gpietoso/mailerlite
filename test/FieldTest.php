<?php
use MailerLite\App;
use Slim\Http\Environment;
use Slim\Http\Request;

class FieldTest extends PHPUnit_Framework_TestCase
{
	protected $app;

    public function setUp()
    {
        $this->app = (new MailerLite\App())->get();
    }

    // Tests GET endpoint /field/{id}
	public function testFieldGet() {
		$env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
            'REQUEST_URI'    => '/field/1'
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
		$data = json_decode($response->getBody());
        $this->assertSame($data->message, "Success");
    }

	// Tests DELETE endpoint /field/{id}
	public function testFieldDelete() {
		$env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
            'REQUEST_URI'    => '/field/5'
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

	// Tests POST, PUT, PATCH endpoint /field/
	public function testFieldPost() {
		$_POST['payload'] = '{"fields": [{"title":"address" , "type":"string"}]}';

		$env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
            'REQUEST_URI'    => '/field',
			'CONTENT_TYPE' => 'application/x-www-form-urlencoded'
		]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
		$data = json_decode($response->getBody());
		var_dump($data);

    }
}
