<?php
namespace MailerLite;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \MailerLite\Validation as Validation;
use \MailerLite\Field_Model as FieldModel;
use \MailerLite\Subscriber_Model as SubscriberModel;

require 'helpers/Validation.php';
require 'models/Base_Model.php';
require 'models/Field_Model.php';
require 'models/Subscriber_Model.php';

class App
{
    /**
     * Stores an instance of the Slim application.
     *
     * @var \Slim\App
     */
    private $app;

    public function __construct() {
		$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

		// ##########################
		// Subscriber handler
		// ##########################
		$app->group('/subscriber', function () {

		    $this->map(['GET'], '', function (Request $request, Response $response) {
		        return $response->withJson(['message' => 'Invalid endpoint'], 404);
		    });

			// GET
		    $this->get('/{id}', function (Request $request, Response $response, $args) {
		        if(Validation::validateId($args['id'])) {
					$model = new SubscriberModel;
					$data = $model->get($args['id']);

					if($data->id != null){
						return $response->withJson(['message' => "Success", "data" => $data], 200);
					} else {
						return $response->withJson(['message' => "Id not found"], 404);
					}
		        }
		    });

			// POST, PUT, PATCH
		    $this->map(['POST', 'PUT', 'PATCH'], '', function (Request $request, Response $response, $args) {

				$requestBody = $request->getParsedBody();
	
				if(!array_key_exists('payload', $requestBody)){
					return $response->withJson(['message' => "No payload found"], 404);
				} else {
					$parsedData = json_decode($requestBody['payload']);

					// Validates minum field requirements
					if(!Validation::meetsMinimumFieldRequirement($parsedData->fields)){
						return $response->withJson(['message' => "Payload does not contain minium subscriber fields (name, email_address, state)"], 503);
					}

					// Validates if email is valid (formatting and active dns)
					if(!Validation::isValidEmail($parsedData->fields)){
						return $response->withJson(['message' => "Invalid e-mail address format or domain is not active"], 503);
					}

					// Check for new or existing subscriber
					$model = new SubscriberModel;

					if(isset($parsedData->subscriber_id)){
						$subscriber = $model->get($parsedData->subscriber_id);
						if($subscriber->id == 0){
							return $response->withJson(['message' => "Subscriber not found"], 404);
						}
					}

					// Checks to see if aditional sent fields sent are actually valid
					$modelField = new FieldModel;
					$validFields = $modelField->getAll();

					if(Validation::areValidFields($parsedData->fields, $validFields)) {
						// Inserts/updates subscriber
						$return = $model->put($parsedData);
						if($return) {
							return $response->withJson(['message' => "Success"], 200);
						} else {
							return $response->withJson(['message' => "Error"], 500);
						}
					} else {
						return $response->withJson(['message' => "One or more fields not found"], 404);
					}
				}

		    });

			// DELETE
		    $this->delete('/{id}', function (Request $request, Response $response, $args) {
				if(Validation::validateId($args['id'])) {

					$model = new SubscriberModel;

					// Checks if subscriber exists before deletion
					$subscriber = $model->get($args['id']);
					if($subscriber->id == 0){
						return $response->withJson(['message' => "Subscriber not found"], 404);
					}

					$data = $model->delete($args['id']);

					if($data){
						return $response->withJson(['message' => "Success"], 200);
					} else {
						return $response->withJson(['message' => "Id not found"], 404);
					}
		        }
		    });

		});

		// ##########################
		// Fields handler
		// ##########################
		$app->group('/field', function () {

		    $this->map(['GET'], '', function (Request $request, Response $response) {
		        return $response->withJson(['message' => 'Invalid endpoint'], 404);
		    });

			// GET
		    $this->get('/{id}', function (Request $request, Response $response, $args) {
		        if(Validation::validateId($args['id'])) {

					$model = new FieldModel;
					$data = $model->get($args['id']);

					if($data){
						return $response->withJson(['message' => "Success", "data" => $data], 200);
					} else {
						return $response->withJson(['message' => "Id not found"], 404);
					}
		        }
		    });

			// POST, PUT, PATCH
		    $this->map(['POST', 'PUT', 'PATCH'], '', function (Request $request, Response $response, $args) {

				$requestBody = $request->getParsedBody();
			
				if(!array_key_exists('payload', $requestBody)){
					return $response->withJson(['message' => $requestBody], 200);
				} else {
					$parsedData = json_decode($requestBody['payload']);
					if(!Validation::isValidType($parsedData->type)){
						return $response->withJson(['message' => "Field type not found"], 404);
					} else {
						$model = new FieldModel;
						$return = $model->put($parsedData);
						if($return) return $response->withJson(['message' => "Success"], 200);
						else return $response->withJson(['message' => "Error"], 404);
					}
				}

				if($return){
					return $response->withJson(['message' => "Success"], 200);
				} else {
					return $response->withJson(['message' => "Error while persisting payload"], 404);
				}

		    });

			// DELETE
		    $this->delete('/{id}', function (Request $request, Response $response, $args) {
				if(Validation::validateId($args['id'])) {

					$model = new FieldModel;
					$data = $model->delete($args['id']);

					if($data){
						return $response->withJson(['message' => "Success"], 200);
					} else {
						return $response->withJson(['message' => "Id not found"], 404);
					}
		        }
		    });

		});


        $this->app = $app;
    }
    /**
     * Get an instance of the application.
     *
     * @return \Slim\App
     */
    public function get()
    {
        return $this->app;
    }
}
?>
