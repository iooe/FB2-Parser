<?php

namespace Tizis\FB2\Parser;

use DiDom\Document;
use DiDom\Element;
use Tizis\FB2\FB2AttributesManager;
use Tizis\FB2\Model\IModel;

/**
 * Class Parser
 * @package FB2\Parser
 */
abstract class Parser extends FB2AttributesManager
{

  protected $xmlDOM;
  protected $xmlElement;
  protected $model;
  protected $response;

  /**
   * @param IModel $value
   */
  public function setModel(IModel $value): void
  {
    $this->model = $value;
  }

  /**
   * @return Document
   */
  public function getXmlDOM(): Document
  {
    return $this->xmlDOM;
  }

  /**
   * @param Document $value
   */
  public function setXmlDOM(Document $value): void
  {
    $this->xmlDOM = $value;
  }

  /**
   * @return Element
   */
  public function getXmlElement(): Element
  {
    return $this->xmlElement;
  }

  /**
   * @param Element $value
   */
  public function setXmlElement(Element $value): void
  {
    $this->xmlElement = $value;
  }

}