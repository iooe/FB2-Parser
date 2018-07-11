<?php

namespace Tizis\FB2;

use DiDom\Document;
use Tizis\FB2\Model\Book;

/**
 * Class FB2Parser
 * @package FB2
 */
class FB2Parser extends FB2AttributesManager
{
  /**
   * @var Book
   */
  protected $book;
  private $xmlDOM;

  /**
   * FB2Parser constructor.
   * @param $xml
   */
  public function __construct($xml)
  {
    $this->loadElements($xml);
    $this->setAttributes([
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
    $items = [];
    $nodes = (array)$this->xmlDOM->find('author');
    foreach ($nodes as $node) {
      $item = (new Parser\Author($node))->parse();
      if ($item->getFullName()) {
        $items[] = $item;
      }
    }
    $this->book->setAuthors($items);
  }

  private function parseTranslators(): void
  {
    $items = [];
    $nodes = (array)$this->xmlDOM->find('translator');
    foreach ($nodes as $node) {
      $item = (new Parser\Translator($node))->parse();
      if (!empty($item->getFullName())
        || (null !== $item->getEmail())
        || (null !== $item->getNickName())) {
        $items[] = $item;
      }
    }
    $this->book->setTranslators($items);
  }

  private function parseBookInfo(): void
  {
    $this->book->setInfo((new Parser\BookInfo($this->xmlDOM->first('description')))->parse());
  }

  private function parseChapters(): void
  {
    $this->book->setChapters((new Parser\Chapters($this->xmlDOM, $this->getAttributes()))->parse());
  }

  /**
   * @param array $attributes
   */
  public function loadAttributes(array $attributes): void
  {
    foreach ($attributes as $attribute => $state) {
      if ($this->getAttributes()[$attribute] !== null) {
        $this->insertAttributes($state, $attribute);
      }
    }
  }

  /**
   * @return Book
   */
  public function getBook(): Book
  {
    return $this->book;
  }
}