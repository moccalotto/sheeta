<?php

namespace App\Repositories;

use LogicException;
use MongoDB\Driver\Query;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Manager;

class Repository
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Constructor
     */
    public function __construct(Manager $manager, $namespace)
    {
        $this->manager = $manager;
        $this->namespace = $namespace;
    }

    public function normalizeId($id)
    {
        if ($id instanceof ObjectId) {
            return $id;
        }
        if (is_string($id)) {
            return new ObjectId($id);
        }

        throw new LogicException(sprintf(
            'id must be a string or an instance of %s',
            ObjectId::class
        ));
    }

    public function query($filter, $options = [])
    {
        return $this->manager->executeQuery(
            $this->namespace,
            new Query($filter, $options)
        );
    }

    public function find($id)
    {
        return $this->query(['_id' => $this->normalizeId($id)]);
    }
}
