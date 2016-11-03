<?php

namespace App\Util;

use MongoDB\Driver\Query;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\WriteResult;

class Mongo
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $db;

    /**
     * Constructor
     */
    public function __construct(Manager $manager, string $db)
    {
        $this->manager = $manager;
        $this->db = $db;
    }

    /**
     * Get the wrapped MongoDB Manager.
     *
     * @return Manager
     */
    public function manager() : Manager
    {
        return $this->manager;
    }

    /**
     * Turn a collection name into a fully qualified namespace (i.e. prepend the db name)
     *
     * @param string $collection
     *
     * @return string
     */
    public function makeNamespace(string $collection) : string
    {
        return $this->db . '.' . $collection;
    }

    /**
     * Create a MongoWriter instance to help building write/delete/update commands.
     *
     * @param string $collection The collection these commands are to be executed on.
     *
     * @return MongoWriter
     */
    public function write(string $collection) : MongoWriter
    {
        return new MongoWriter($this, new BulkWrite(), $this->makeNamespace($collection));
    }

    /**
     * Execute a search query.
     *
     * @param string $collection
     * @param array $filter
     * @param array $queryOptions
     *
     * @return Cursor
     */
    public function query(string $collection, array $filter = [], array $queryOptions = []) : Cursor
    {
        return $this->manager->executeQuery(
            $this->makeNamespace($collection),
            new Query($filter, $queryOptions)
        );
    }
}
