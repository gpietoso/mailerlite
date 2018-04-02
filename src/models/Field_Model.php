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
 * This class provides model methods for fields
 */
class FieldModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns field data
     *
     * @param int $id The passed id
     */
    public function get($id)
    {
        $stmt = $this->_connection->prepare("SELECT
									f.id,
									f.title,
									ft.name AS type
								FROM fields f
								INNER JOIN field_types ft ON ft.id = f.type_id
								WHERE f.id=".$id);
        $stmt->execute();
        $data = $stmt->fetch();
        $obj = null;

        if ($data) {
            $obj = new stdClass;
            $obj->id = $data['id'];
            $obj->title = $data['title'];
            $obj->type = $data['type'];
        }

        return $obj;
    }

    /**
     * Inserts field data
     *
     * @param Object $obj Payload data
     */
    public function put($obj)
    {
        $sql = "SELECT id FROM field_types WHERE name = '".$obj->type."'";
        $stmt = $this->_connection->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();

        $sql = "INSERT INTO fields
					(title
					,type_id)
				VALUES
					('@title'
					,@type_id)
  				ON DUPLICATE KEY
				UPDATE
					title='@title'
					,type_id=@type_id";

        $sql = str_replace("@title", $obj->title, $sql);
        $sql = str_replace("@type_id", $data['id'], $sql);

        $stmt = $this->_connection->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Deletes field data by id
     *
     * @param int $id The passed id
     */
    public function delete($id)
    {
        $sql = "DELETE FROM fields WHERE id = ".$id;
        $stmt = $this->_connection->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Returns all field data
    */
    public function getAll()
    {
        $stmt = $this->_connection->prepare("SELECT title FROM fields");
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $data;
    }
}
