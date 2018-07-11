<?php

namespace Tizis\FB2\Parser;

use DiDom\Element;
use Tizis\FB2\Model\Author as AuthorModel;

/**
 * Class Author
 * @package FB2\Parser
 */
class Author extends Parser
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
   * Author constructor.
   * @param Element $elementNode
   */
  public function __construct(Element $elementNode)
  {
    $this->setModel(new AuthorModel());
    $this->setXmlElement($elementNode);
  }

  /**
   * @return AuthorModel
   */
  public function parse(): AuthorModel
  {
    $this->setModel(new AuthorModel());
    $model = $this->getModel();
    $nodes = (array)$this->nodes;
    // each author nodes
    foreach ($nodes as $key => $nodeName) {
      $node = $this->getXmlElement()->first($nodeName);
      $element = $node && $node->text() ? trim($node->text()) : null;
      if ($element !== null) {
        switch ($key) {
          case 'firstName';
            $model->setFirstName($element);
            break;
          case 'lastName';
            $model->setLastName($element);
            break;
        }
      }
    }
    $itemFullName = trim($model->getFirstName() . ' ' . $model->getLastName());
    $model->setFullName($itemFullName);
    // save to response if author full name is note empty
    return $model;
  }

  /**
   * @return AuthorModel
   */
  public function getModel(): AuthorModel
  {
    return $this->model;
  }
}