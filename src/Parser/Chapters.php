<?php

namespace Tizis\FB2\Parser;

use Tizis\FB2\Helpers\DocumentFormatter;
use Tizis\FB2\Model\Chapter as ChapterModel;
use Tizis\FB2\Parser\Chapter\ChapterImages;
use Tizis\FB2\Parser\Chapter\ChapterNotes;

/**
 * Class Chapters
 * @package FB2\Parser
 */
class Chapters extends Parser
{
  protected $chapterDOM;
  protected $images = [];

  /**
   * Chapters constructor.
   * @param $xmlDOM
   * @param array $attributes
   */
  public function __construct(&$xmlDOM, array $attributes = [])
  {
    $this->set('xmlDOM', $xmlDOM);
    $this->set('attributes', $attributes);
    // set document link prefix, fb2 access custom prefix for links
    $this->insert('attributes', DocumentFormatter::getDocumentLinkPrefix($xmlDOM), 'linkType');
    // global notes|images counters. Needs for note element identifications.
    $this->insert('attributes', 0, 'imagesCounter');
    $this->insert('attributes', 0, 'notesCounter');
  }

  /**
   * parse start
   * @return array
   */
  public function parse(): array
  {
    $items = (array)$this->xmlDOM->find('body>section');
    foreach ($items as $key => $item) {
      $this->set('model', new ChapterModel());
      $this->set('chapterDOM', $item);
      $this->set('chapterNodes', []);
      $this->titleHandler();
      $this->notesHandler();
      $this->imagesHandler();
      $this->contentHandler();
      $this->insert('response', $this->model);
    }
    return $this->get('response');
  }

  /**
   * set chapter title && update chapterDOM
   */
  private function titleHandler(): void
  {
    $chapterDOM = $this->get('chapterDOM');
    if ($chapterDOM->has('title')) {
      $title = $chapterDOM->firstInDocument('title');
      $this->get('model')->set('title', $title->text());
      $title->remove();
    }
  }

  /**
   * notes handler && update chapterDOM
   */
  private function notesHandler(): void
  {
    $notesParser = new ChapterNotes($this->get('chapterDOM'), $this->get('xmlDOM'), $this->get('attributes'));
    $this->set('chapterDOM', $notesParser->parse());
    // update global notes counter
    $this->insert('attributes', $notesParser->getCounter(), 'notesCounter');
  }

  /**
   * save images && update chapterDOM
   */
  private function imagesHandler(): void
  {
    if (\count($this->get('images')) === 0) {
      $this->set('images', DocumentFormatter::getBinaryImages($this->get('xmlDOM')));
    }
    $imagesParser = new ChapterImages($this->get('chapterDOM'), $this->get('images'), $this->get('attributes'));
    $this->set('chapterDOM', $imagesParser->parse());
    // update global images counter
    $this->insert('attributes', $imagesParser->getCounter(), 'imagesCounter');
  }

  /**
   * content cleaner
   */
  private function contentHandler(): void
  {
    // replace some nodes
    DocumentFormatter::nodesReplace($this->chapterDOM);
    // replace fb2 nodes to html standard nodes
    $content = DocumentFormatter::FB2TagsToHTML($this->chapterDOM->innerHtml());
    $this->get('model')->set('content', $content);
  }
}