<?php

namespace Solarium\Component\Result\Debug;

/**
 * Select component debug document result.
 */
class Document extends Detail implements \IteratorAggregate, \Countable
{
    /**
     * Key.
     *
     * @var string
     */
    protected $key;

    /**
     * Details.
     *
     * @var array
     */
    protected $details;

    /**
     * Constructor.
     *
     * @param string $key
     * @param bool   $match
     * @param float  $value
     * @param string $description
     * @param array  $details
     */
    public function __construct($key, $match, $value, $description, $details)
    {
        parent::__construct($match, $value, $description);
        $this->key = $key;
        $this->details = $details;
    }

    /**
     * Get key value for this document.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get details.
     *
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * IteratorAggregate implementation.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->details);
    }

    /**
     * Countable implementation.
     *
     * @return int
     */
    public function count()
    {
        return count($this->details);
    }
}
