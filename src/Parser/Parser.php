<?php

namespace FB2\Parser;

use FB2\FB2ClassAttributesHandler;

/**
 * Class Parser
 * @package FB2\Parser
 */
abstract class Parser extends FB2ClassAttributesHandler
{

  protected $xmlDOM;
  protected $model;
  protected $response;
}