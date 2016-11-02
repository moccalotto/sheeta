<?php

namespace App;

use Moccalotto\Valit\Facades\Ensure;

class Row
{
    /**
     * @var string
     */
    protected $cells;

    /**
     * Throw an exception if we do not have a cell with the given index.
     */
    protected function assertCellExists(int $index)
    {
        Ensure::that($this->cells)->as('Cell')->hasKey($index);
    }

    public static function fromArray(array $cells)
    {
        return new static(array_map('strval', $cells));
    }

    /**
     * Constructor
     *
     * @param array $cells
     */
    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    /**
     * Insert a cell into the row.
     *
     * @param string $value
     * @param int $index
     */
    public function insertCell(string $value, int $index = null)
    {
        if ($index === null) {
            $index = count($this->cells);
        }

        $this->cells = array_merge(
            array_slice($this->cells, 0, $index),
            [$value],
            array_slice($this->cells, $index)
        );
    }

    /**
     * Set the contents of a given cell.
     *
     * @param int $index
     * @param string $value
     */
    public function setCell(int $index, string $value)
    {
        $this->assertCellExists($index);

        $this->cells[$index] = $value;
    }

    /**
     * Get the contents of the nths cell
     *
     * @param int $index
     * @return string
     */
    public function cell(int $index) : string
    {
        $this->assertCellExists($index);

        return $this->cells[$index];
    }

    /**
     * Get the contents as json-friendly array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return $this->cells;
    }
}
