<?php

namespace Tizis\FB2\Parser;

use DiDom\Element;
use Tizis\FB2\Model\Translator as TranslatorModel;

/**
 * Class Translator
 * @package FB2\Parser
 */
class Translator extends Parser
{
  /**
   * translators xmlDOM nodes
   * @var array
   */
  protected $nodes = [
    'firstName' => 'first-name',
    'middleName' => 'middle-name',
    'lastName' => 'last-name',
    'nickName' => 'nickname',
    'email' => 'email',
  ];

  /**
   * Translator constructor.
   * @param $elementNode
   */
  public function __construct(Element $elementNode)
  {
    $this->setXmlElement($elementNode);
    $this->setModel(new TranslatorModel());
  }

  /**
   * @return TranslatorModel
   */
  public function parse(): TranslatorModel
  {
    $nodes = (array)$this->nodes;
    $model = $this->getModel();
    // each author nodes
    foreach ($nodes as $key => $nodeName) {
      $node = $this->getXmlElement()->first($nodeName);
      $element = $node && $node->text() ? trim($node->text()) : null;
      if ($element !== null) {
        switch ($key) {
          case 'firstName';
            $model->setFirstName($element);
            break;
          case 'middleName';
            $model->setMiddleName($element);
            break;
          case 'lastName';
            $model->setLastName($element);
            break;
          case 'nickName';
            $model->setNickName($element);
            break;
          case 'email';
            $model->setEmail($element);
            break;
        }
      }
    }
    $itemFullName = trim($model->getFirstName() . ' ' . $model->getMiddleName() . ' ' . $model->getNickName());
    $model->setFullName($itemFullName);
    return $model;
  }

  /**
   * @return TranslatorModel
   */
  public function getModel(): TranslatorModel
  {
    return $this->model;
  }
}