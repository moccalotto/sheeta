<?php

namespace App;

use RuntimeException;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Unserializable;
use Moccalotto\Valit\Facades\Ensure;

class Sheet implements Unserializable
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

    public static function collection()
    {
        return 'Sheets';
    }

    public static function writer()
    {
        return app('mongo')->write(static::collection());
    }

    public static function query(array $filter, array $queryOptions = [])
    {
        return app('mongo')->query(static::collection(), $filter, $queryOptions);
    }

    public static function find($id)
    {
        if (!$id instanceof ObjectID) {
            $id = new ObjectID((string) $id);
        }
        $cursor = static::query(['_id' => $id, ]);

        $cursor->setTypeMap([
            'root' => static::class,
            'document' => 'array',
            'array' => 'array',
        ]);

        // return the first element if any.
        // otherwise return null.
        foreach ($cursor as $entity) {
            return $entity;
        }
    }

    public static function findOrFail($id)
    {
        $entity = static::find($id);
        if (!$entity) {
            throw new RuntimeException('Entity not found');
        }
    }

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

    public function bsonUnserialize(array $docArray)
    {
        $other = static::fromArray($docArray);

        foreach ($other as $key => $value) {
            $this->$key = $value;
        }
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

    public function save()
    {
        return $this->writer()->update(
            ['_id' => $this->id, ],
            $this->toArrayForMongo(),
            ['multi' => false, 'upsert' => true]
        )->execute();
    }

}
