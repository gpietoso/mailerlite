<?php


use Phinx\Seed\AbstractSeed;

class FieldSeeder extends AbstractSeed
{
    public function run()
    {
		$fieldSeeder = $this->table('fields');

		$data = [
			[
				'title'    => 'name',
				'type_id'  => 3,
			],
			[
				'title'    => 'email_address',
				'type_id'  => 3,
			],
			[
				'title'    => 'state',
				'type_id'  => 3,
			]
		];

		$fieldSeeder->insert($data)
			  ->save();
    }
}
