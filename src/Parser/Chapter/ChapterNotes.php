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

  /**
   * ChapterNotes constructor.
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
    // if parse with notes
    if ($this->isWithNotes()) {
      $notes = $this->notesHandler();
      $this->insertNotesIntoContent($notes);
    } else {
      // else remove notes from chapter
      $this->removeNotesLinks();
    }
    return $this->getXmlElement();
  }

  private function isWithNotes(): bool
  {
    return $this->getAttributes()['isNotes'];
  }

  /**
   * @return array
   */
  private function notesHandler(): array
  {
    $globalNotes = $this->getAttributes()['notes'];
    $chapterNotes = [];
    $linkType = $this->getAttributes()['linkType'];
    $nodes = (array)$this->getXmlElement()->findInDocument('a[type=note]');
    // each notes
    foreach ($nodes as $node) {
      // get note link
      $noteId = trim($node->attr($linkType . ':href'), '#');
      if ($note = $globalNotes[$noteId]) {
        $chapterNotes[] = $note;
        // make new note link && replace
        $element = new Element('a', $node->innerHtml(), ['href' => '#' . $note['id']]);
        $node->replace($element);
      }
    }
    return $chapterNotes;
  }

  /**
   * insert nodes block into chapter
   * @param array $notes
   */
  private function insertNotesIntoContent(array $notes): void
  {
    if (\count($notes) !== 0) {
      $ul = new Element('ol');
      $hr = new Element('hr');
      $li = [];
      foreach ($notes as $note) {
        $p = new Element('p', strip_tags(html_entity_decode($note['content']), '<a>'));
        $el = new Element('li', '', ['id' => $note['id']]);
        $el->appendChild($p);
        $li[] = $el;
      }
      $ul->appendChild($li);
      $wrapper = new Element('div');
      $wrapper->appendChild($hr);
      $wrapper->appendChild($ul);
      $this->getXmlElement()->appendChild($wrapper);
    }
  }

  /**
   * remove notes links from chapter
   */
  private function removeNotesLinks(): void
  {
    $nodes = (array)$this->getXmlElement()->findInDocument('a[type=note]');
    foreach ($nodes as $node) {
      $element = new Element('span', $node->innerHtml());
      $node->replace($element);
    }
  }
}