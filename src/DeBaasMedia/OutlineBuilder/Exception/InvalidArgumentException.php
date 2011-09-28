<?php

  namespace DeBaasMedia\OutlineBuilder\Exception;

  use InvalidArgumentException as BaseInvalidArgumentException;

  class InvalidArgumentException extends BaseInvalidArgumentException implements ExceptionInterface
  {

    static public function raiseValueIsNotPositiveInteger ($arg_value)
    {
      $message = "The value '%s' is not a positive integer.";

      throw new static(sprintf($message, $arg_value));
    }

    static public function raiseValueIsNotIntegerHigherThan ($arg_value, $arg_base)
    {
      $message = "The value '%s' is not a higher than '%d'.";

      throw new static(sprintf($message, $arg_value, $arg_base));
    }

  }