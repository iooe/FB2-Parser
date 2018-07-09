<?php

namespace FB2\Parser;

use FB2\Model\Translator as TranslatorModel;

/**
 * Class Translators
 * @package FB2\Parser
 */
class Translators extends Parser
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
    'email' => 'email'
  ];

  /**
   * Translators constructor.
   * @param $xmlDOM
   */
  public function __construct(&$xmlDOM)
  {
    $this->set('xmlDOM', $xmlDOM);
    $this->set('response', []);
  }

  /**
   * @return array
   */
  public function parse(): array
  {
    $translators = (array)$this->xmlDOM->find('translator');
    foreach ($translators as $item) {
      $this->set('model', new TranslatorModel());
      $nodes = (array)$this->get('nodes');
      $model = $this->get('model');
      foreach ($nodes as $key => $nodeName) {
        $node = $item->first($nodeName);
        $element = $node && $node->text() ? trim($node->text()) : null;
        $model->set($key, $element);
      }
      $itemFullName = trim($model->get('firstName') . ' ' . $model->get('middleName') . ' ' . $model->get('lastName'));

      $model->set('fullName', $itemFullName);
      if (!empty($model->get('fullName'))
        || (null !== $model->get('email'))
        || (null !== $model->get('nickName'))) {
        $this->insert('response', $model);
      }
    }
    return $this->get('response');
  }
}