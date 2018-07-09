<?php

namespace Tizis\FB2\Model;
/**
 * Class Translator
 * @package FB2\Model
 */
class Translator extends Model
{

  public $firstName = '';
  public $lastName = '';
  public $middleName = '';
  public $fullName = '';
  public $nickName = '';
  public $email = '';

  /**
   * Book constructor.
   */
  public function __construct()
  {
    $this->unset('attributes');
  }
}