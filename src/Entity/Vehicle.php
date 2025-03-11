<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "vehicles")]
#[ORM\Index(name: "ix_vehicles_id", columns: ["id"])]
#[ORM\Index(name: "ix_vehicles_email_address", columns: ["email_address"])]
class Vehicle
{
  #[ORM\Id, ORM\GeneratedValue, ORM\Column]
  private ?int $id = null;

  #[ORM\Column(name: "license_plate", length: 255)]
  private string $licensePlate;

  #[ORM\Column(length: 255)]
  private string $vehicle;

  #[ORM\Column(length: 255)]
  private string $organization;

  #[ORM\Column(length: 255)]
  private string $category;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $salutation = null;

  #[ORM\Column(name: "email_address", length: 255)]
  private string $emailAddress;

  public function __construct(string $licensePlate, string $vehicle, string $organization, string $category, string $salutation, string $emailAddress)
  {
    $this->licensePlate = $licensePlate;
    $this->vehicle = $vehicle;
    $this->organization = $organization;
    $this->category = $category;
    $this->salutation = $salutation;
    $this->emailAddress = $emailAddress;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getLicensePlate(): string
  {
    return $this->licensePlate;
  }

  public function setLicensePlate(string $licensePlate): void
  {
    $this->licensePlate = $licensePlate;
  }

  public function getVehicle(): string
  {
    return $this->vehicle;
  }

  public function setVehicle(string $vehicle): void
  {
    $this->vehicle = $vehicle;
  }

  public function getOrganization(): string
  {
    return $this->organization;
  }

  public function setOrganization(string $organization): void
  {
    $this->organization = $organization;
  }

  public function getCategory(): string
  {
    return $this->category;
  }

  public function setCategory(string $category): void
  {
    $this->category = $category;
  }

  public function getSalutation(): ?string
  {
    return $this->salutation;
  }

  public function setSalutation(?string $salutation): void
  {
    $this->salutation = $salutation;
  }

  public function getEmailAddress(): string
  {
    return $this->emailAddress;
  }

  public function setEmailAddress(string $emailAddress): void
  {
    $this->emailAddress = $emailAddress;
  }
}
