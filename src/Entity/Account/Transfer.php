<?php

namespace App\Entity\Account;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a transfer of money between accounts.
 * @ORM\Entity(repositoryClass="App\Repository\Account\TransferRepository")
 */
class Transfer {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float")
     */
    private ?float $total = 0;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     */
    private ?DateTime $time = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsSource")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private ?Account $source = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsTarget")
     * @Assert\NotNull()
     * @Assert\NotEqualTo(propertyPath="target", message="The target account can not be equal to the source account.")
     */
    private ?Account $target = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    public function __construct() {
        $this->creationTime = new DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getTotal() : float {
        return $this->total;
    }

    public function setTotal(float $total): self {
        $this->total = $total;
        return $this;
    }

    public function getTime(): ?Carbon {
        return $this->time !== null ? Carbon::instance($this->time) : null;
    }

    public function setTime(DateTime $time): self {
        $this->time = $time;

        return $this;
    }

    public function getSource(): ?Account {
        return $this->source;
    }

    public function setSource(?Account $source): self {
        $this->source = $source;

        return $this;
    }

    public function getTarget(): ?Account {
        return $this->target;
    }

    public function setTarget(?Account $target): self {
        $this->target = $target;

        return $this;
    }

    public function getCreationTime(): ?Carbon {
        return $this->creationTime !== null ? Carbon::instance($this->creationTime) : null;
    }

    public function setCreationTime(DateTime $time): self {
        $this->creationTime = $time;

        return $this;
    }
}
