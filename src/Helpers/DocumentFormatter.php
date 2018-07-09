<?php

namespace FB2\Helpers;

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
      '',
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
    if ($document->has('a') && $document->first('a')->attr($default . ':href') === null) {
      preg_match('/xmlns:(.*)=/', $document->first('FictionBook')->first('body')->remove()->html(), $linkType);
      return $linkType[1];
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
    foreach ($nodes as $node) {
      $imageId = $node->attr('id');
      $images[$imageId] = [
        'id' => $imageId,
        'content' => $node->innerHtml()
      ];
    }
    return $images;
  }
}