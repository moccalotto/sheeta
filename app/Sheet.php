<?php

namespace App;

use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Persistable;
use Moccalotto\Valit\Facades\Ensure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Sheet implements Persistable
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
        return app('mongo')->selectCollection(
            'Sheets',
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        );
    }

    public static function find($id)
    {
        $docArray = static::collection()->findOne([
            '_id' => is_string($id) ? new ObjectID($id) : $id,
        ]);

        return $found ? static::fromArray($docArray) : null;
    }

    public static function findOrFail($id)
    {
        $entity = $this->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Entity not found');
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

    public function bsonSerialize() : array
    {
        $data = $this->toArray();
        unset($data['id']);
        $data['_id'] = $this->id;

        return $data;
    }

    public function save()
    {
        return $this->collection()->updateOne(
            ['_id' => $this->id, ],
            $this,
            ['upsert' => true]
        );
    }
}
