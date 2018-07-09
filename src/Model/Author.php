<?php

namespace Tizis\FB2\Model;
/**
 * Class Author
 * @package FB2\Model
 */
class Author extends Model
{

  public $firstName = '';
  public $lastName = '';
  public $fullName = '';

  /**
   * Book constructor.
   */
  public function __construct()
  {
    $this->unset('attributes');
  }
}