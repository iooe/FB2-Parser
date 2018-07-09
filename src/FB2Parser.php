<?php

namespace Tizis\FB2;

use DiDom\Document;
use Tizis\FB2\Model\Book;

/**
 * Class FB2Parser
 * @package FB2
 */
class FB2Parser extends FB2ClassAttributesHandler
{

  protected $book;
  private $xmlDOM;

  /**
   * FB2Parser constructor.
   * @param $xml
   */
  public function __construct($xml)
  {
    $this->loadElements($xml);
    $this->set('attributes', [
      'isImages' => false,
      'imagesWebPath' => false,
      'imagesDirectory' => false,
      'isNotes' => false
    ]);
  }

  /**
   * load elements
   * @param $xml
   */
  private function loadElements($xml): void
  {
    $this->xmlDOM = (new Document())->loadXml($xml);
    $this->book = new Book();
  }

  /**
   * start process
   */
  public function startParse(): void
  {
    $this->parseAuthors();
    $this->parseTranslators();
    $this->parseBookInfo();
    $this->parseChapters();
  }

  private function parseAuthors(): void
  {
    $this->book->authors = (new Parser\Authors($this->xmlDOM))->parse();
  }

  private function parseTranslators(): void
  {
    $this->book->translators = (new Parser\Translators($this->xmlDOM))->parse();
  }

  private function parseBookInfo(): void
  {
    $this->book->info = (new Parser\BookInfo($this->xmlDOM))->parse();
  }

  private function parseChapters(): void
  {
    $this->book->chapters = (new Parser\Chapters($this->xmlDOM, $this->attributes))->parse();
  }

  /**
   * @param array $attributes
   */
  public function setAttributes(array $attributes): void
  {
    foreach ($attributes as $attribute => $state) {
      if ($this->get('attributes')[$attribute] !== null) {
        $this->insert('attributes', $state, $attribute);
      }
    }
  }

  /**
   * @return Book
   */
  public function getBook(): Book
  {
    return $this->get('book');
  }
}