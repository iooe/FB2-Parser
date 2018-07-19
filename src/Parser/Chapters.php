<?php

namespace Tizis\FB2\Parser;

use Tizis\FB2\Helpers\DocumentFormatter;
use Tizis\FB2\Parser\Chapter\Chapter;

/**
 * Class Chapters
 * @package FB2\Parser
 */
class Chapters extends Parser
{
  /**
   * Chapters constructor.
   * @param $xmlDOM
   * @param array $attributes
   */
  public function __construct(&$xmlDOM, array $attributes = [])
  {
    $this->setXmlDOM($xmlDOM);
    $this->setAttributes($attributes);
    // set document link prefix, fb2 access custom prefix for links

    $this->insertAttributes(DocumentFormatter::getDocumentLinkPrefix($xmlDOM), 'linkType');
    // global notes|images counters. Needs for note element identifications.
    $this->insertAttributes(0, 'imagesCounter');
    $this->loadElements();
  }

  /**
   *
   */
  private function loadElements(): void
  {
    $formatter = DocumentFormatter::getBinaryImages($this->getXmlDOM());
    $this->insertAttributes($formatter['images'], 'images');

    $formatter = DocumentFormatter::getBookNotes($formatter['xmlDOM'], $this->getAttributes()['linkType']);
    $this->insertAttributes($formatter['notes'], 'notes');

    $this->setXmlDOM($formatter['xmlDOM']);
  }

  /**
   * parse start
   * @return array
   */
  public function parse(): array
  {
    $response = [];
    $items = (array)$this->xmlDOM->find('body>section');
    foreach ($items as $key => $item) {
      $chapter = new Chapter($item);
      $chapter->setAttributes($this->getAttributes());
      $response[] = $chapter->parse();
    }
    return $response;
  }

  /**
   * @return Chapter
   */
  public function getModel(): Chapter
  {
    return $this->model;
  }

}