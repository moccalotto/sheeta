<?php

namespace App;

use RuntimeException;

class Table
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $rowsMin = null;

    /**
     * @var int
     */
    protected $rowsMax = null;

    /**
     * @var bool
     */
    protected $headlineVisible = true;

    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var Row[]
     */
    protected $rows = [];

    public function insertColumn(Column $column, $index = null)
    {
        if ($index === null) {
            $index = count($this->columns);
        }

        $this->columns = array_merge(
            array_slice($this->columns, 0, $index),
            [$column],
            array_slice($this->columns, $index)
        );

        // insert $column into $columns at the correct index

        foreach ($this->rows as $row) {
            $row->insertCell($index, '');
        }
    }

    public function canAddMoreRows($numberOfRows = 1)
    {
        if (!$this->rowsMax) {
            return true;
        }

        return count($this->rows) + $numberOfRows < $this->rows;
    }

    public function createRow()
    {
        return new Row(range(0, count($this->columns) - 1));
    }

    public function insertRow($index = null)
    {
        if (!$this->canAddMoreRows()) {
            throw new RuntimeException('You cannot add any more rows');
        }

        $this->rows = array_merge(
            array_slice($this->rows, 0, $index),
            [$this->createRow()],
            array_slice($this->rows, $index)
        );
    }

    public function setCell($rowIndex, $colIndex, $value)
    {
        if (!isset($this->rows[$rowIndex])) {
            throw new RuntimeException(sprintf('You cannot access rowIndex #%d', $rowIndex));
        }

        if (!isset($this->columns[$colIndex])) {
            throw new RuntimeException(sprintf('You cannot access colIndex #%d', $colIndex));
        }

        $row = $this->rows[$rowIndex];
        $col = $this->columns[$colIndex];

        $normalizedValue = $col->formatValue($value);

        $row->setCell($rowIndex, $normalizedValue);
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'rowsMin' => $this->rowsMin,
            'rowsMax' => $this->rowsMax,
            'headlineVisible' => $this->headlineVisible,
            'columns' => array_map(function ($col) {
                return $col->jsonSerialize();
            }, $this->columns),
            'rows' => array_map(function ($row) {
                return $row->jsonSerialize();
            }, $this->rows),
        ];
    }
}
