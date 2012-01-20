<?php
namespace VS\TimeSheet\Security\Policy;

/**
 *
 */
class Role
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
