<?php

namespace Tizis\FB2\Parser;

use Tizis\FB2\FB2ClassAttributesHandler;

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