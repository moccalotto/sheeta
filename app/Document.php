<?php

namespace App;

use RuntimeException;

class Document
{
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

    public function jsonSerialize()
    {
        return [
            'template' => (bool) $this->template,
            'name' => $this->name,
            'tables' => $this->table->jsonSerialize(),
        ];
    }
}
