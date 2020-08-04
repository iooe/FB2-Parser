<?php

namespace Tizis\FB2\Helpers;

use DiDom\Document;
use DiDom\Element;

/**
 * Class DocumentFormatter
 * @package FB2\Helpers
 */
class DocumentFormatter
{

  /**
   * @param $html
   * @return string
   */
  public static function FB2TagsToHTML(string $html): string
  {
    return str_replace([
      '<section>',
      '</section>',
      '<empty-line></empty-line>',
      '<emphasis>',
      '</emphasis>'
    ], [
      '',
      '',
      '<br/>',
      '<i>',
      '</i>'
    ], $html);
  }

  /**
   * @param Element $document
   */
  public static function nodesReplace(Element $document): void
  {
    $tags = [
      'title' => 'h2',
      'subtitle' => 'h3'
    ];
    foreach ($tags as $tag => $replace) {
      $nodes = (array)$document->findInDocument($tag);
      foreach ($nodes as $node) {
        $element = new Element($replace, $node->text());
        $node->replace($element);
      }
    }
  }

  /**
   * @param $document
   * @return string
   */
  public static function getDocumentLinkPrefix(Document $document): string
  {
    $default = 'l';
    if (($a = $document->has('a')) || $document->has('image')) {
      $element = $a ? 'a' : 'image';
      if ($document->first($element)->attr($default . ':href') === null) {
        $fictionBook = $document->first('FictionBook');
        preg_match('/xmlns:(.*)=\"http:\/\/www.w3.org\/1999\/xlink\"/', $fictionBook->html(), $linkType);
        return $linkType[1];
      }
    }
    return $default;
  }

  /**
   * It hack. I don't know why DiDOM return null on find binary#id,
   * so i just load all images array with key/id into memory
   *
   * @param Document $document
   * @return array
   */
  public static function getBinaryImages(Document $document): array
  {
    $images = [];
    $nodes = (array)$document->find('binary');
    $counter = 0;
    foreach ($nodes as $node) {
      $imageId = $node->attr('id');
      $images[$imageId] = [
        'id' => $counter,
        'id_original' => $imageId,
        'content' => $node->innerHtml()
      ];
      $node->remove();
      $counter++;
    }
    $response['images'] = $images;
    $response['xmlDOM'] = $document;
    return $response;
  }

  /**
   * @param Document $document
   * @param $linkType
   * @return array
   */
  public static function getBookNotes(Document $document, $linkType): array
  {
    $counter = 0;
    $notes = [];
    $nodes = (array)$document->find('a[type=note]');
    foreach ($nodes as $node) {
      $noteId = trim($node->attr($linkType . ':href'), '#');
      $noteContentId = 'section#' . $noteId;
      if (($noteContentNode = $document->first($noteContentId)) !== null) {
        $noteContent = trim(str_replace(
          $noteContentNode->first('title'),
          '',
          $noteContentNode->innerHtml()
        ));
        $notes[$noteId] = [
          'id' => 'note_' . $counter,
          'id_original' => $noteId,
          'content' => $noteContent
        ];
        $noteContentNode->remove();
        $counter++;
      }
    }
    $response['notes'] = $notes;
    $response['xmlDOM'] = $document;
    return $response;
  }
}