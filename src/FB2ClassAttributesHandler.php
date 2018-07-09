<?php

namespace Tizis\FB2;

/**
 * Class FB2ClassAttributesHandler
 * @package FB2
 */
abstract class FB2ClassAttributesHandler
{
  /**
   * default attributes array
   */
  protected $attributes = [];

  /**
   * @param $key
   * @param $value
   */
  public function set($key, $value): void
  {
    if (property_exists(\get_class($this), $key)) {
      $this->{$key} = $value;
    }
  }

  /**
   * @param $key
   */
  public function unset($key): void
  {
    if (property_exists(\get_class($this), $key)) {
      unset($this->{$key});
    }
  }

  /**
   * @param $key
   * @return mixed
   */
  public function get($key)
  {
    if (property_exists(\get_class($this), $key)) {
      return $this->{$key};
    }
    return false;
  }

  /**
   * @param $key
   * @param $value
   * @param null $key_array
   */
  public function insert($key, $value, $key_array = null): void
  {
    if (property_exists(\get_class($this), $key)) {
      if (null === $key_array) {
        $this->{$key}[] = $value;
      } else {
        $this->{$key}[$key_array] = $value;
      }
    }
  }
}