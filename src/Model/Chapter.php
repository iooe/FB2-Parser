<?php

namespace FB2\Model;
/**
 * Class Chapter
 * @package FB2\Model
 */
class Chapter extends Model
{
  public $title;
  public $content;

  /**
   * Book constructor.
   */
  public function __construct()
  {
    $this->unset('attributes');
  }
}