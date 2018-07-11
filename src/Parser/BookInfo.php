<?php

namespace Tizis\FB2\Parser;

use DiDom\Element;
use Tizis\FB2\Model\BookInfo as BookInfoModel;

/**
 * Class BookInfo
 * @package FB2\Parser
 */
class BookInfo extends Parser
{
  /**
   * BookInfo constructor.
   * @param $element
   */
  public function __construct(Element $element)
  {
    $this->setXmlElement($element);
    $this->setModel(new BookInfoModel());
  }

  /**
   * @return BookInfoModel
   */
  public function parse(): BookInfoModel
  {
    $this->parseTitle();
    $this->parseAnnotation();
    $this->parseLang();
    $this->parseGenres();
    $this->parseKeywords();
    return $this->getModel();
  }

  /**
   * set title
   */
  private function parseTitle(): void
  {
    $bookTitleNode = $this->getXmlElement()->first('book-title');
    $bookTitle = $bookTitleNode && $bookTitleNode->text() ? trim($bookTitleNode->text()) : '';
    $this->getModel()->setTitle($bookTitle);
  }

  /**
   * @return BookInfoModel
   */
  public function getModel(): BookInfoModel
  {
    return $this->model;
  }

  /**
   * set annotation
   */
  private function parseAnnotation(): void
  {
    $bookAnnotationNode = $this->getXmlElement()->first('annotation');
    $bookAnnotation = $bookAnnotationNode && $bookAnnotationNode->html() ? trim(strip_tags($bookAnnotationNode->innerHtml(), '<p>')) : '';
    $this->getModel()->setAnnotation($bookAnnotation);
  }

  /**
   * set lang
   */
  private function parseLang(): void
  {
    $xmlDOM = $this->getXmlElement();
    $model = $this->getModel();
    // nodes
    $langNode = $xmlDOM->first('lang');
    $srcLangNode = $xmlDOM->first('src-lang');
    // current lang && original lang
    $lang['lang'] = $langNode && $langNode->text() ? trim($langNode->text()) : null;
    $lang['src'] = $srcLangNode && $srcLangNode->text() ? trim($srcLangNode->text()) : null;
    // set lang
    $model->setLang($lang);
  }

  /**
   * set genres
   */
  private function parseGenres(): void
  {
    $items = (array)$this->getXmlElement()->find('genre');
    $genres = [];
    $model = $this->getModel();
    foreach ($items as $item) {
      $item = trim($item->text());
      if (!empty($item)) {
        $genres[] = $item;
      }
    }
    $model->setGenres($genres);
  }

  /**
   * set keywords
   */
  private function parseKeywords(): void
  {
    $item = $this->getXmlElement()->first('keywords');
    if ($item && $item->text()) {
      $this->getModel()->setKeywords(trim($item->text()));
    }
  }
}