<?php

namespace Tizis\FB2\Model;
/**
 * Class Book
 * @package FB2\Model
 */
class Book
{
  /**
   * @var array
   */
  public $authors = [];
  /**
   * @var array
   */
  public $translators = [];
  /**
   * @var array
   */
  public $chapters = [];
  /**
   * @var BookInfo
   */
  public $info = [];

  /**
   * @return BookInfo
   */
  public function getInfo(): BookInfo
  {
    return $this->info;
  }

  /**
   * @param BookInfo $value
   */
  public function setInfo(BookInfo $value): void
  {
    $this->info = $value;
  }

  /**
   * @return array
   */
  public function getTranslators(): array
  {
    return $this->translators;
  }

  /**
   * @param array $value
   */
  public function setTranslators(array $value): void
  {
    $this->translators = $value;
  }

  /**
   * @return array
   */
  public function getChapters(): array
  {
    return $this->chapters;
  }

  /**
   * @param array $value
   */
  public function setChapters(array $value): void
  {
    $this->chapters = $value;
  }

  /**
   * @return array
   */
  public function getAuthors(): array
  {
    return $this->authors;
  }

  /**
   * @param array $value
   */
  public function setAuthors(array $value): void
  {
    $this->authors = $value;
  }
}