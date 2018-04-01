<?php


use Phinx\Migration\AbstractMigration;

class SetupDatabase extends AbstractMigration
{
    public function change()
    {
		//Subscriber table
		$tableSubscribers = $this->table('subscribers',  ['id' => true, 'primary_key' => 'id', 'signed' => false])
		$tableSubscribers->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
			->create();

		// Field types table
	  	$tableFieldTypes = $this->table('field_types',  ['id' => true, 'primary_key' => 'id', 'signed' => false]);
  		$tableFieldTypes->addColumn('name', 'string')
  			->create();

		// Fields table
		$tableFields = $this->table('fields',  ['id' => true, 'primary_key' => 'id', 'signed' => false]);
		$tableFields->addColumn('title', 'string')
			->addColumn('type_id', 'integer', ['signed' => false])
			->addForeignKey('type_id', 'field_types', 'id', ['delete'=> 'RESTRICT'])
			->addIndex(['title', 'type_id'], ['unique' => true])
			->create();

		//N to N relation table
		$tableSubscriberFields = $this->table('subscriber_x_fields');
		$tableSubscriberFields->addColumn('value', 'string', ['signed' => false])
			->addColumn('subscriber_id', 'integer', ['signed' => false])
			->addColumn('field_id', 'integer', ['signed' => false])
			->addForeignKey('subscriber_id', 'subscribers', 'id', ['delete'=> 'CASCADE'])
			->addForeignKey('field_id', 'fields', 'id', ['delete'=> 'CASCADE'])
			->addIndex(['subscriber_id', 'field_id'], ['unique' => true])
			->create();
    }

	/**
    * Migrate Up.
	*/
	public function up(){

	}

	/**
    * Migrate Down.
	*/
	public function down(){
	}
}
