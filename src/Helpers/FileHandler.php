<?php

namespace Tizis\FB2\Helpers;
/**
 * Class FileHandler
 * @package FB2\Helpers
 * // */
class FileHandler
{

  /**
   * @param $file
   * @return mixed
   */
  public static function FB2FileCleaner($file)
  {
    return str_replace('http://www.gribuser.ru/xml/fictionbook/2.0', '', $file);
  }
}