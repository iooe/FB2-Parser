<?php

namespace Tizis\FB2\Parser\Chapter;

use DiDom\Element;
use Tizis\FB2\Helpers\DocumentFormatter;
use Tizis\FB2\Model\Chapter as ChapterModel;
use Tizis\FB2\Parser\Parser;

/**
 * Class ChapterImages
 * @package FB2\Parser\Chapter
 */
class Chapter extends Parser
{

  public function __construct(Element $elementNode)
  {
    $this->setModel(new ChapterModel());
    $this->setXmlElement($elementNode);
  }

  public function parse()
  {
    $this->titleHandler();
    $this->notesHandler();
    $this->imagesHandler();
    $this->contentHandler();
    return $this->getModel();
  }

  /**
   * set chapter title && update chapterDOM
   */
  private function titleHandler(): void
  {
    $chapterDOM = $this->getXmlElement();
    if ($chapterDOM->has('title')) {
      $title = $chapterDOM->firstInDocument('title');
      $this->getModel()->setTitle($title->text());
      $title->remove();
    }
  }

  /**
   * @return ChapterModel
   */
  public function getModel(): ChapterModel
  {
    return $this->model;
  }

  /**
   * notes handler && update chapterDOM
   */
  private function notesHandler(): void
  {
    $notesParser = new ChapterNotes($this->getXmlElement());
    $notesParser->setAttributes($this->getAttributes());
    $this->setXmlElement($notesParser->parse());
  }

  /**
   * save images && update chapterDOM
   */
  private function imagesHandler(): void
  {
    $imagesParser = new ChapterImages($this->getXmlElement());
    $imagesParser->setAttributes($this->getAttributes());
    $this->setXmlElement($imagesParser->parse());
  }

  /**
   * content cleaner
   */
  private function contentHandler(): void
  {
    // replace some nodes
    DocumentFormatter::nodesReplace($this->getXmlElement());
    // replace fb2 nodes to html standard nodes
    $content = DocumentFormatter::FB2TagsToHTML($this->getXmlElement()->innerHtml());
    $this->getModel()->setContent($content);
  }

}