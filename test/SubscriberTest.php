<?php
use MailerLite\App;
use Slim\Http\Environment;
use Slim\Http\Request;

class SubscriberTest extends PHPUnit_Framework_TestCase
{
	protected $app;

    public function setUp()
    {
        $this->app = (new MailerLite\App())->get();
    }

    // Tests GET endpoint /field/{id}
	public function testSubscriberGet() {
		// Set id of existing subsscriber
		$id = 1;

		$env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
            'REQUEST_URI'    => '/subscriber/'.$id
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
		$data = json_decode($response->getBody());
        $this->assertSame($data->message, "Success");
    }

	// Tests DELETE endpoint /subscriber/{id}
	public function testSubscriberDelete() {
		// Set id of existing subscriber
		$id = 1;
		
		$env = Environment::mock([
			'REQUEST_METHOD' => 'DELETE',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
			'REQUEST_URI'    => '/subscriber/'.$id
			]);
		$req = Request::createFromEnvironment($env);
		$this->app->getContainer()['request'] = $req;
		$response = $this->app->run(true);
		$this->assertSame($response->getStatusCode(), 200);
		$data = json_decode($response->getBody());
		$this->assertSame($data->message, "Success");
	}
	
	// Tests POST, PUT, PATCH endpoint /subscriber/
	public function testFieldPostInsert() {
		$_POST['payload'] = '{"fields": [{"title":"email_address" , "value":"giulianosigma@hotmail.com"}, 
										{"title":"state" , "value":"bounced"}, 
										{"title":"name" , "value":"Giuliano Pietoso"}]}';

		$env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
            'REQUEST_URI'    => '/subscriber',
			'CONTENT_TYPE' => 'application/x-www-form-urlencoded'
		]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
		$data = json_decode($response->getBody());
		$this->assertSame($data->message, "Success");
    }
	
	// Tests POST, PUT, PATCH endpoint /subscriber/
	public function testFieldPostUpdate() {
		$_POST['payload'] = '{"subscriber_id": "2", "fields": [{"title":"email_address" , "value":"giulianosigma@hotmail.com"}, 
										{"title":"state" , "value":"junk"}, 
										{"title":"name" , "value":"Giuliano Only!"}]}';

		$env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
			'SERVER_NAME' => '127.0.0.1',
			'SERVER_PORT' => 8080,
            'REQUEST_URI'    => '/subscriber',
			'CONTENT_TYPE' => 'application/x-www-form-urlencoded'
		]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
		$data = json_decode($response->getBody());
		$this->assertSame($data->message, "Success");
    }
}
