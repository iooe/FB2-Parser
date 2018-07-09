<?php

namespace FB2\Model;
/**
 * Class Book
 * @package FB2\Model
 */
class Book extends Model
{

  public $authors = [];
  public $translators = [];
  public $chapters = [];
  public $info = [];

  /**
   * Book constructor.
   */
  public function __construct()
  {
    $this->unset('attributes');
  }
}