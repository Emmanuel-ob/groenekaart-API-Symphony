<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource(
  operations: [
    new Get(uriTemplate: '/foobar/{id}'),
    new GetCollection(uriTemplate: '/foobar'),
    new Post(uriTemplate: '/foobar'),
    new Patch(uriTemplate: '/foobar/{id}'),
    new Delete(uriTemplate: '/foobar/{id}'),
  ]
)]
class FooBar
{
  #[ORM\Id, ORM\Column, ORM\GeneratedValue]
  private ?int $id = null;

  #[ORM\Column]
  private string $title = '';

  #[ORM\Column]
  private string $description = '';

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): void
  {
    $this->description = $description;
  }
}
