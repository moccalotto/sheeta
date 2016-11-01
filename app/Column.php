<?php

namespace App;

use Moccalotto\Valit\Facades\Check;
use Moccalotto\Valit\Facades\Ensure;

class Column
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $readonly = false;

    /**
     * @var string
     */
    protected $format = '%s';

    /**
     * @var int
     */
    protected $min = null;

    /**
     * @var int
     */
    protected $max = null;

    /**
     * @var string
     */
    protected $regex = null;

    /**
     * @var string[]
     */
    protected $oneOf = null;

    /**
     * @var array
     */
    protected $defaults = [];

    protected function getOrSet(bool $set, string $key, $value)
    {
        if (!$set) {
            return $this->$key;
        }

        $this->$key = $value;
        return $this;
    }

    public function __construct(array $settings)
    {
        Ensure::that($settings)
            ->as('settings')
            ->hasKey('name');

        foreach ($this as $key => $value) {
            $this->defaults[$key] = $value;
        }

        foreach ($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function patch(array $settings)
    {
        foreach ($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function validate($value)
    {
        $check = Check::that($value)->as('cell');
        if ($this->min !== null) {
            $check->isGreaterThanOrEqual($this->min);
        }
        if ($this->max !== null) {
            $check->isLowerThanOrEqual($this->max);
        }
        if ($this->regex !== null) {
            $check->matches($this->regex);
        }
        if ($this->oneOf !== null) {
            $check->isOneOf($this->oneOf);
        }

        $check->orThrowException();
    }

    public function formatValue($value)
    {
        $this->validate($value);

        if ($this->format === null) {
            return (string) $value;
        }

        return sprintf($this->format, $value);
    }

    public function jsonSerialize()
    {
        $return = [];

        foreach ($this as $key => $value) {
            if ($value !== $this->defaults[$key]) {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /**
     * Get or set name.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function name(string $value = null)
    {
         return $this->getOrSet(func_num_args(), 'name', $value);
    }

    /**
     * Get or set readonly.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function readonly(bool $value = null)
    {
         return $this->getOrSet(func_num_args(), 'readonly', $value);
    }

    /**
     * Get or set format.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function format(string $value = null)
    {
         return $this->getOrSet(func_num_args(), 'format', $value);
    }

    /**
     * Get or set min.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function min(int $value = null)
    {
        return $this->getOrSet(func_num_args(), 'min', $value);
    }

    /**
     * Get or set max.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function max(int $value = null)
    {
         return $this->getOrSet(func_num_args(), 'max', $value);
    }

    /**
     * Get or set regex.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function regex(string $value = null)
    {
         return $this->getOrSet(func_num_args(), 'regex', $value);
    }

    /**
     * Get or set oneOf.
     *
     * This method is a getter if it is called without any arguments.
     * This method is a setter if called with a single argument.
     *
     * This method returns the value of the given name if used as a getter.
     * This method returns $this if used as a setter.
     */
    public function oneOf(array $value = null)
    {
         return $this->getOrSet(func_num_args(), 'oneOf', $value);
    }
}
