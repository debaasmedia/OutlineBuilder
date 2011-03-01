<?php

  namespace DeBaasMedia\OutlineBuilder\Exception;

  use OutOfBoundsException as BaseOutOfBoundsException;

  class OutOfBoundsException extends BaseOutOfBoundsException implements ExceptionInterface
  {

    static public function raiseIndexDoesNotExist ($arg_index)
    {
      $message = "The index '%s' does not exist.";

      throw new static(sprintf($message, $arg_index));
    }

  }
