<?php


use Phinx\Seed\AbstractSeed;

class FieldTypeSeeder extends AbstractSeed
{
    public function run()
    {
		$fieldTypeTable = $this->table('field_types');

		$data = [
            [
                'name'    => 'date'
            ],
            [
                'name'    => 'number'
            ],
			[
                'name'    => 'string'
            ],
			[
                'name'    => 'booelan'
            ]
        ];

        $fieldTypeTable->insert($data)
              ->save();
    }
}
