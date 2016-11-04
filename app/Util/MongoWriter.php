<?php

namespace App\Util;

use MongoDB\Driver\BulkWrite;

class MongoWriter
{
    /**
     * @var Mongo
     */
    protected $mongo;

    /**
     * @var BulkWrite
     */
    protected $bulkWrite;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Constructor
     */
    public function __construct(Mongo $mongo, BulkWrite $bulkWrite, $namespace)
    {
        $this->mongo = $mongo;
        $this->bulkWrite = $bulkWrite;
        $this->namespace = $namespace;
    }

    public function __call($method, $args)
    {
        call_user_func_array([$this->bulkWrite, $method], $args);

        return $this;
    }

    public function update(array $filter, $document, array $options = [])
    {
        $this->bulkWrite->update($filter, $document, $options);
        return $this;
    }

    public function count()
    {
        return $this->bulkWrite->count();
    }

    public function insertAndGetId($document)
    {
        return $this->bulkWrite->insert($document);
    }

    public function execute()
    {
        $this->mongo->manager()
            ->executeBulkWrite($this->namespace, $this->bulkWrite);
    }
}
