<?php

namespace App;

use MongoDB\Database;
use RuntimeException;
use MongoDB\BSON\ObjectID;
use Moccalotto\Valit\Facades\Ensure;

class Document
{
    /**
     * @var ObjectID
     */
    protected $id;
    /**
     * @var bool
     */
    protected $template = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Table[]
     */
    protected $tables = [];

    public static function fromArray(array $docArray)
    {
        Ensure::that($docArray)->as('docArray')->hasKey('name');

        $doc = new static();

        if (isset($docArray['_id'])) {
            $doc->id = $docArray['_id'] instanceof ObjectID
                ? $docArray['_id']
                : new ObjectID($docArray['_id']);
        }

        $doc->name = $docArray['name'];
        $doc->template = (bool) $docArray['template'] ?? false;

        foreach ($docArray['tables'] as $tableArray) {
            $doc->tables[] = Table::fromArray($tableArray);
        }

        return $doc;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = new ObjectID();
    }

    public function insertTable(Table $table, $index = null)
    {
        if ($index === null) {
            $index = count($this->tables);
        }

        $this->tables = array_merge(
            array_slice($this->tables, 0, $index),
            [$table],
            array_slice($this->tables, $index)
        );
    }

    public function toArray() : array
    {
        return [
            'id' => (string) $this->id,
            'template' => (bool) $this->template,
            'name' => $this->name,
            'tables' => array_map(function ($table) {
                return $table->toArray();
            }, $this->tables),
        ];
    }

    public function toArrayForMongo() : array
    {
        $data = $this->toArray();
        unset($data['id']);
        return [
            '_id' => $this->id,
            'template' => (bool) $this->template,
            'name' => $this->name,
            'tables' => array_map(function ($table) {
                return $table->toArray();
            }, $this->tables),
        ];
    }

    public static function collection()
    {
        return app(Database::class)->Sheets;
    }

    public function save()
    {
        return (array) $this->collection()->insertOne($this->toArrayForMongo());
    }

    public static function find(ObjectID $id)
    {
        return Document::fromArray(Document::collection()->findOne([
            '_id' => $id
        ], [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array',
                'array' => 'array',
            ],
        ]));
    }
}
