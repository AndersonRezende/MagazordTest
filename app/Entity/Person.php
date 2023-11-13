<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('person')]
class Person
{
    #[Id]
    #[Column, GeneratedValue(strategy: "AUTO")]
    public $id = 1;

    #[Column(length: 100)]
    public string $name;

    #[Column(length: 11, unique: true)]
    public string $cpf;

    #[OneToMany(targetEntity: Contact::class, mappedBy: 'person', cascade: ['persist', 'remove'])]
    private Collection $contacts;

    /**
     * @param int $id
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact)
    {
        $contact->setPerson($this);
        $this->contacts->add($contact);
        return $this;
    }
}