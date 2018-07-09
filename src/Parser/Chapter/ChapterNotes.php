<?php

namespace Tizis\FB2\Parser\Chapter;

use DiDom\Element;
use Tizis\FB2\Parser\Parser;

/**
 * Class ChapterNotes
 * @package FB2\Parser\Chapter
 */
class ChapterNotes extends Parser implements IChapterNodes
{
  protected $chapterDOM;
  protected $imagesCounter = 0;
  protected $notes = [];

  /**
   * ChapterNotes constructor.
   * @param $chapterDOM
   * @param $xmlDOM
   * @param array $attributes
   */
  public function __construct($chapterDOM, $xmlDOM, array $attributes)
  {
    $this->set('chapterDOM', $chapterDOM);
    $this->set('xmlDOM', $xmlDOM);
    $this->set('attributes', $attributes);
  }

  /**
   * start parse
   */
  public function parse()
  {
    // if parse with notes
    if ($this->isWithNotes()) {
      $this->notesHandler();
      $this->insertNotesIntoContent();
    } else {
      // else remove notes from chapter
      $this->removeNotesLinks();
    }
    return $this->get('chapterDOM');
  }

  private function isWithNotes(): bool
  {
    return $this->get('attributes')['isNotes'];
  }

  /**
   * notes handler
   */
  private function notesHandler(): void
  {
    $linkType = $this->get('attributes')['linkType'];
    $xmlDOM = $this->get('xmlDOM');
    $nodes = (array)$this->get('chapterDOM')->findInDocument('a[type=note]');
    $counter = $this->get('attributes')['imagesCounter'];
    // each notes
    foreach ($nodes as $node) {
      // get note link
      $noteId = trim($node->attr($linkType . ':href'), '#');
      $noteContentId = 'section#' . $noteId;
      // if link container is exists
      if ($xmlDOM->has($noteContentId)) {
        // new id of note
        $noteNewId = 'note_' . $counter;
        // note content
        $noteNode = $xmlDOM->first($noteContentId);
        $noteContent = trim(str_replace($noteNode->first('title'), '', $noteNode->innerHtml()));
        // save into notes array
        $this->insert('notes', [
          'id' => $noteNewId,
          'original_id' => $noteId,
          'content' => $noteContent
        ]);
        // make new note link && replace
        $element = new Element('a', $node->innerHtml(), ['href' => '#' . $noteNewId]);
        $node->replace($element);
        $counter++;
      }
    }
    $this->insert('attributes', $counter, 'imagesCounter');
  }

  /**
   * insert nodes block into chapter
   */
  private function insertNotesIntoContent(): void
  {
    if (\count($this->get('notes')) !== 0) {
      $notes = (array)$this->get('notes');
      $ul = new Element('ol');
      $hr = new Element('hr');
      $li = [];
      foreach ($notes as $note) {
        $li[] = new Element('li', $note['content'], ['id' => $note['id']]);
      }
      $ul->appendChild($li);
      $wrapper = new Element('div');
      $wrapper->appendChild($hr);
      $wrapper->appendChild($ul);
      $this->chapterDOM->appendChild($wrapper);
    }
  }

  /**
   * remove notes links from chapter
   */
  private function removeNotesLinks(): void
  {
    $nodes = (array)$this->get('chapterDOM')->findInDocument('a[type=note]');
    foreach ($nodes as $node) {
      $element = new Element('span', $node->innerHtml());
      $node->replace($element);
    }
  }

  public function getCounter(): int
  {
    return $this->get('attributes')['imagesCounter'];
  }
}