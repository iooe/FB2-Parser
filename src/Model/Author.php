<?php

namespace Tizis\FB2\Model;
/**
 * Class Author
 * @package FB2\Model
 */
class Author implements IModel
{
  /**
   * @var string
   */
  private $firstName = '';
  /**
   * @var string
   */
  private $lastName = '';
  /**
   * @var string
   */
  private $fullName = '';

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  /**
   * @param string $value
   */
  public function setFirstName(string $value): void
  {
    $this->firstName = $value;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /***
   * @param string $value
   */
  public function setLastName(string $value): void
  {
    $this->lastName = $value;
  }

  /**
   * @return string
   */
  public function getFullName(): string
  {
    return $this->fullName;
  }

  /**
   * @param string $item
   */
  public function setFullName(string $value): void
  {
    $this->fullName = $value;
  }
}