<?php
namespace VS\TimeSheet\Domain\Model;

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * @FLOW3\Entity
 */
class Customer {

    /**
     * @var string
     * @FLOW3\Validate(type="StringLength", options={ "minimum"=1, "maximum"=100 })
     * @FLOW3\Identity
     * @ORM\Column(length=100)
     */
    protected $number;

    /**
     * @var string
     * @ORM\Column(length=100)
     */
    protected $code;

    /**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $name;

    /**
     * @var string
     * @ORM\Column(length=50)
     */
    protected $color;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\ElectronicAddress>
	 * @ORM\ManyToMany
	 */
	protected $electronicAddresses;

	/**
	 * @var \TYPO3\Party\Domain\Model\ElectronicAddress
	 * @ORM\ManyToOne
	 */
	protected $primaryElectronicAddress;

    /**
	 * @var \Doctrine\Common\Collections\Collection<\VS\TimeSheet\Domain\Model\Project>
	 * @ORM\OneToMany(mappedBy="customer", cascade={"all"})
	 */
	protected $projects;

    /**
     * Constructor
     */
    public function __construct() {
		$this->electronicAddresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
	}

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElectronicAddresses()
    {
        return $this->electronicAddresses;
    }

    /**
     * @param \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress
     * @return void
     */
    public function addElectronicAddress(\TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$this->electronicAddresses->add($electronicAddress);
	}

    /**
     * @param \TYPO3\Party\Domain\Model\PersonName $name
     */
    public function setName(\TYPO3\Party\Domain\Model\PersonName $name)
    {
        $this->name = $name;
    }

    /**
     * @return \TYPO3\Party\Domain\Model\PersonName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return \TYPO3\Party\Domain\Model\ElectronicAddress
     */
    public function getPrimaryElectronicAddress()
    {
        return $this->primaryElectronicAddress;
    }

    /**
     * @param Project $project
     * @return void
     */
    public function addProject(\VS\TimeSheet\Domain\Model\Project $project) {
		$this->projects->add($project);
        $project->setCustomer($this);
	}

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
