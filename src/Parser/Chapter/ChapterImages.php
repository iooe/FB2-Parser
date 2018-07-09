<?php

namespace FB2\Parser\Chapter;

use DiDom\Element;
use FB2\Parser\Parser;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class ChapterImages
 * @package FB2\Parser\Chapter
 */
class ChapterImages extends Parser implements IChapterNodes
{

  protected $chapterDOM;
  protected $images = [];
  protected $imagesCounter = 0;

  /**
   * ChapterImages constructor.
   * @param $chapterDOM
   * @param array $images
   * @param array $attributes
   */
  public function __construct(&$chapterDOM, array $images, array $attributes)
  {
    $this->set('chapterDOM', $chapterDOM);
    $this->set('images', $images);
    $this->set('attributes', $attributes);

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
    return $this->get('chapterDOM');
  }

  private function isWithImages(): bool
  {
    $attributes = $this->get('attributes');
    return $attributes['isImages'] === true && $attributes['imagesDirectory'] !== false;
  }

  /**
   * images handler
   */
  private function imagesHandler(): void
  {
    $imagesWebPath = $this->get('attributes')['imagesWebPath'];
    $linkType = $this->get('attributes')['linkType'];
    $imagesDirectory = $this->get('attributes')['imagesDirectory'];
    $counter = $this->get('attributes')['imagesCounter'];
    // each images
    $nodes = (array)$this->get('chapterDOM')->findInDocument('image');
    foreach ($nodes as $node) {
      $noteId = trim($node->attr($linkType . ':href'), '#');
      // if images is exist
      if ($binary = $this->get('images')[$noteId]) {
        // save image
        Image::make(base64_decode($binary['content']))->save($imagesDirectory . '/' . $counter . '.jpg');
        // make new img element
        $href = $imagesWebPath ? $imagesWebPath . '/' . $counter . '.jpg' : $counter . '.jpg';
        $element = new Element('img', '', ['href' => $href]);
        $node->replace($element);
        $this->insert('attributes', $counter + 1, 'imagesCounter');
        $counter++;
      }
    }
    $this->insert('attributes', $counter, 'imagesCounter');
  }

  /**
   * remove images from chapter
   */
  private function removeImages(): void
  {
    $nodes = (array)$this->get('chapterDOM')->findInDocument('image');
    foreach ($nodes as $node) {
      $node->remove();
    }
  }

  public function getCounter(): int
  {
    return $this->get('attributes')['imagesCounter'];
  }
}