<?php

namespace Tizis\FB2\Parser\Chapter;

use DiDom\Element;
use Intervention\Image\ImageManagerStatic as Image;
use Tizis\FB2\Parser\Parser;

/**
 * Class ChapterImages
 * @package Tizis\FB2\Parser\Chapter
 */
class ChapterImages extends Parser implements IChapterNodes
{

  /**
   * ChapterImages constructor.
   * @param Element $element
   */
  public function __construct(Element $element)
  {
    $this->setXmlElement($element);
  }

  /**
   * start parse
   */
  public function parse()
  {
    // if parse with images
    if ($this->isWithImages()) {
      $this->imagesHandler();
    } else {
      // else remove images from chapter
      $this->removeImages();
    }
    return $this->getXmlElement();
  }

  private function isWithImages(): bool
  {
    $attributes = $this->getAttributes();
    return $attributes['isImages'] === true && $attributes['imagesDirectory'] !== false;
  }

  /**
   * images handler
   */
  private function imagesHandler(): void
  {
    $imagesWebPath = $this->getAttributes()['imagesWebPath'];
    $linkType = $this->getAttributes()['linkType'];
    $images = $this->getAttributes()['images'];
    $imagesDirectory = $this->getAttributes()['imagesDirectory'];
    // each images

    $nodes = (array)$this->getXmlElement()->findInDocument('image');

    if (\count($nodes) !== 0) {

      foreach ($nodes as $node) {
        $noteId = trim($node->attr($linkType . ':href'), '#');
        // if images is exist
        if (isset($images[$noteId])) {
          $image = $images[$noteId];
          // save image
          Image::make(base64_decode($image['content']))->save($imagesDirectory . '/' . $image['id'] . '.jpg');
          // make new img element
          $href = $imagesWebPath ? $imagesWebPath . '/' . $image['id'] . '.jpg' : $image['id'] . '.jpg';
          $element = new Element('img', '', ['src' => $href]);
          $node->replace($element);
        }
      }
    }
  }

  /**
   * remove images from chapter
   */
  private function removeImages(): void
  {
    $nodes = (array)$this->getXmlElement()->findInDocument('image');
    foreach ($nodes as $node) {
      $node->remove();
    }
  }

  public function getCounter(): int
  {
    return $this->getAttributes()['imagesCounter'];
  }
}