<?php

  namespace DeBaasMedia\OutlineBuilder;

  use DeBaasMedia\OutlineBuilder\Exception\InvalidArgumentException
    , SimpleXMLElement;

  class Index
  {

    private $nestingLevel;

    private $indexElement;

    private $currentGroup;

    private $currentLevel;

    private $elementCount;

    public function __construct ($arg_nestingLevel)
    {
      if (1 > $arg_nestingLevel)
      {
        InvalidArgumentException::raiseValueIsNotIntegerHigherThan($arg_nestingLevel, 1);
      }
      
      $this->nestingLevel = $arg_nestingLevel;
      $this->indexElement = new SimpleXMLElement("<ol/>");
      $this->currentGroup = $this->indexElement;
      $this->currentLevel = 1;
      $this->elementCount = 0;
    }

    public function __toString ()
    {
      $element = dom_import_simplexml($this->getIndexElement());

      return $element->ownerDocument->saveXML($element);
    }

    public function addReferenceForLevel ($arg_level)
    {
      $delta = $arg_level - $this->currentLevel;

      if ($delta < 0)
      {
        $query = './..' . str_repeat('/..', $delta * -1);
        $items = $this->currentGroup->xpath($query);

        $this->currentGroup = $items[0];
      }
      else
      {
        while ($delta > 0)
        {
          $this->currentGroup = $this->currentGroup
                                     ->addChild('li')
                                     ->addChild('ol');

          $delta--;
        }
      }

      $reference = $this->currentGroup->addChild('li');

      $this->currentLevel = $arg_level;

      $this->elementCount++;

      return $reference;
    }

    public function getIndexElement ()
    {
      return $this->indexElement;
    }

    public function count ()
    {
      return $this->elementCount;
    }

  }