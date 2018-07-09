<?php

namespace Tizis\FB2;

use Tizis\FB2\Helpers\FileHandler;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FB2Controller
 * @package FB2
 */
class FB2Controller extends FB2ClassAttributesHandler
{

  protected $xml;
  protected $book;

  /**
   * FB2Controller constructor.
   * @param $file
   */
  public function __construct($file)
  {
    $this->xml = FileHandler::FB2FileCleaner($file);
  }

  /**
   *  start parse book
   */
  public function startParse(): void
  {
    $FB2Parser = new FB2Parser($this->xml);
    $FB2Parser->setAttributes($this->get('attributes'));
    $FB2Parser->startParse();
    $this->book = $FB2Parser->getBook();
  }

  public function getBook()
  {
    return $this->book;
  }

  public function getAuthors()
  {
    return $this->book->authors;
  }

  public function getTranslators()
  {
    return $this->book->translators;
  }

  /**
   * @param array $attributes
   */
  public function withImages(array $attributes): void
  {
    $this->insert('attributes', true, 'isImages');
    foreach ($attributes as $key => $attribute) {
      if ($key === 'directory') {
        $this->insert('attributes', $attribute, 'imagesDirectory');
      }
      if ($key === 'imagesWebPath') {
        $this->insert('attributes', (string)$attribute, 'imagesWebPath');
      }
    }
    $fileSystem = new Filesystem();
    try {
      $fileSystem->mkdir($this->get('attributes')['imagesDirectory']);
    } catch (IOExceptionInterface $exception) {
      echo 'An error occurred while creating your directory at' . $exception->getPath();
    }
  }

  public function withNotes(): void
  {
    $this->insert('attributes', true, 'isNotes');
  }
}