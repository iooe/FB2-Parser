<?php

namespace FB2\Parser;

use FB2\Model\BookInfo as BookInfoModel;

/**
 * Class BookInfo
 * @package FB2\Parser
 */
class BookInfo extends Parser
{
  /**
   * BookInfo constructor.
   * @param $xmlDOM
   */
  public function __construct(&$xmlDOM)
  {
    $this->set('xmlDOM', $xmlDOM);
    $this->set('model', new BookInfoModel);
  }

  /**
   * start parse
   */
  public function parse()
  {
    $this->parseTitle();
    $this->parseAnnotation();
    $this->parseLang();
    $this->parseGenres();
    $this->parseKeywords();
    $this->set('response', $this->model);
    return $this->get('response');
  }

  /**
   * set title
   */
  private function parseTitle(): void
  {
    $bookTitleNode = $this->get('xmlDOM')->first('book-title');
    $bookTitle = $bookTitleNode && $bookTitleNode->text() ? trim($bookTitleNode->text()) : '';
    $this->get('model')->set('title', $bookTitle);
  }

  /**
   * set annotation
   */
  private function parseAnnotation(): void
  {
    $bookTitleNode = $this->get('xmlDOM')->first('annotation');
    $bookTitle = $bookTitleNode && $bookTitleNode->html() ? trim(strip_tags($bookTitleNode->innerHtml(), '<p>')) : '';
    $this->get('model')->set('annotation', $bookTitle);
  }

  /**
   * set lang
   */
  private function parseLang(): void
  {
    $xmlDOM = $this->get('xmlDOM');
    $model = $this->get('model');
    // nodes
    $langNode = $xmlDOM->first('lang');
    $srcLangNode = $xmlDOM->first('src-lang');
    // current lang && original lang
    $lang = $langNode && $langNode->text() ? trim($langNode->text()) : null;
    $srcLang = $srcLangNode && $srcLangNode->text() ? trim($srcLangNode->text()) : null;
    // set lang
    $model->insert('lang', $lang, 'lang');
    $model->insert('lang', $srcLang, 'src');
  }

  /**
   * set genres
   */
  private function parseGenres(): void
  {
    $items = (array)$this->get('xmlDOM')->find('genre');
    $model = $this->get('model');
    foreach ($items as $item) {
      $item = trim($item->text());
      if (!empty($item)) {
        $model->insert('genres', $item);
      }
    }
  }

  /**
   * set keywords
   */
  private function parseKeywords(): void
  {
    $item = $this->get('xmlDOM')->first('keywords');
    if ($item && $item->text()) {
      $this->get('model')->set('keywords', trim($item->text()));
    }
  }
}