<?php

namespace FB2\Model;
/**
 * Class BookInfo
 * @package FB2\Model
 */
class BookInfo extends Model
{

  public $title = '';
  public $annotation = '';
  public $genres = [];
  public $keywords = '';
  public $lang = [
    'lang' => null,
    'src' => null
  ];

  /**
   * Book constructor.
   */
  public function __construct()
  {
    $this->unset('attributes');
  }
}