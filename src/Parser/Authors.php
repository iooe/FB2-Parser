<?php

namespace Tizis\FB2\Parser;

use Tizis\FB2\Model\Author as AuthorModel;

/**
 * Class Authors
 * @package FB2\Parser
 */
class Authors extends Parser
{
  /**
   * authors xmlDOM nodes
   * @var array
   */
  protected $nodes = [
    'firstName' => 'first-name',
    'lastName' => 'last-name'
  ];

  /**
   * Authors constructor.
   * @param $xmlDOM
   */
  public function __construct(&$xmlDOM)
  {
    $this->set('xmlDOM', $xmlDOM);
    $this->set('response', []);
  }

  /**
   * start parse
   * @return array
   */
  public function parse(): array
  {
    $authors = (array)$this->xmlDOM->find('author');
    foreach ($authors as $item) {
      $this->set('model', new AuthorModel());
      $nodes = (array)$this->get('nodes');
      $model = $this->get('model');
      // each author nodes
      foreach ($nodes as $key => $nodeName) {
        $node = $item->first($nodeName);
        $element = $node && $node->text() ? trim($node->text()) : null;
        $model->set($key, $element);
      }
      $itemFullName = trim($model->get('firstName') . ' ' . $model->get('lastName'));
      $model->set('fullName', $itemFullName);
      // save to response if author full name is note empty
      if (!empty($model->get('fullName'))) {
        $this->insert('response', $model);
      }
    }
    return $this->get('response');
  }
}