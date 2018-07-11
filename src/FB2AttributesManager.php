<?php

namespace Tizis\FB2;

/**
 * Class FB2AttributesManager
 * @package Tizis\FB2
 */
class FB2AttributesManager
{

  protected $attributes = [];

  /**
   * @param $value
   * @param null $key_array
   */
  public function insertAttributes($value, $key_array = null): void
  {
    if (null === $key_array) {
      $this->attributes[] = $value;
    } else {
      $this->attributes[$key_array] = $value;
    }
  }

  /**
   * @return mixed
   */
  public function getAttributes()
  {
    return $this->attributes;
  }

  /**
   * @param array $values
   */
  public function setAttributes(array $values): void
  {
    $this->attributes = $values;
  }
}