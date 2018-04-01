<?php
/**
 * Mailerlite API
 *
 * @link      https://github.com/gpietoso/mailerlite
 * @copyright Copyright (c) 2018 Giuliano Pietoso
 */

namespace MailerLite;

use \stdClass;

/**
 * Validation
 *
 * This class provides model methods for subscriber
 */
class Subscriber_Model extends Base_Model
{
	function __construct(){
		parent::__construct();
	}

	/**
	 * Returns subscriber data
	 *
	 * @param int $id The passed id
	 */
	public function get($id){
		$stmt = $this->_connection->prepare("SELECT
									sf.subscriber_id AS id,
									f.title,
									sf.value
								FROM subscriber_x_fields sf
								INNER JOIN fields f ON sf.field_id = f.id AND sf.subscriber_id=".$id);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$obj = new stdClass;
		$obj->id = null;

		if(sizeof($rows) > 0){
			$obj->id = $rows[0]['id'];
		}

		foreach($rows as $row){
			$obj->fields[] = array("title" => $row['title'], "value" => $row["value"]);
		}

		return $obj;
	}

	/**
	 * Inserts subscriber data
	 *
	 * @param Object $obj Payload data
	 */
	public function put($obj){

		try{
	        $this->_connection->beginTransaction();

			$subsriberId = 0;

			if(!isset($obj->subscriber_id)){
				$sql = "INSERT INTO subscribers (id) values (null)";
				$stmt = $this->_connection->prepare($sql);
				$stmt->execute();
				$subsriberId =  $this->_connection->lastInsertId();
			} else {
				$subsriberId = $obj->subscriber_id;
			}

			foreach($obj->fields as $o){
				$sql = "SELECT id FROM fields WHERE title = '".$o->title."'";
				$stmt = $this->_connection->prepare($sql);
				$stmt->execute();
				$dataField = $stmt->fetch();

				$sql = "INSERT INTO subscriber_x_fields
							(subscriber_id
							,field_id
							,value)
						VALUES
							(@subscriber_id
							,@field_id
							,'@value')
						ON DUPLICATE KEY
						UPDATE
							subscriber_id=@subscriber_id
							,field_id=@field_id
							,value='@value'";
				$sql = str_replace("@value", $o->value, $sql);
				$sql = str_replace("@subscriber_id", $subsriberId, $sql);
				$sql = str_replace("@field_id", $dataField['id'], $sql);

				$stmt = $this->_connection->prepare($sql);
				$stmt->execute();
			}

	        $this->_connection->commit();
			return true;
	    } catch(PDOException $pdoe) {
	        $this->_connection->rollBack();
	        throw $pdoe;
	    }
	}

	/**
	 * Deletes sbuscriber data by id
	 *
	 * @param int $id The passed id
	 */
	public function delete($id){
		$sql = "DELETE FROM subscribers WHERE id = ".$id;
		$stmt = $this->_connection->prepare($sql);
		return $stmt->execute();
	}
}
