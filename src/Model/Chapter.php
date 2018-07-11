<?php

namespace Tizis\FB2\Model;
/**
 * Class Chapter
 * @package FB2\Model
 */
class Chapter implements IModel
{
  /**
   * @var string
   */
  private $title = '';
  /**
   * @var string
   */
  private $content;


  /**
   * @return string
   */
  public function getContent(): string
  {
    return $this->content;
  }

  /**
   * @param string $value
   */
  public function setContent($value): void
  {
    $this->content = $value;
  }


  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @param string $value
   */
  public function setTitle(string $value): void
  {
    $this->title = $value;
  }

}