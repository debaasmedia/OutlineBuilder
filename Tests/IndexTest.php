<?php

  namespace DeBaasMedia\OutlineBuilder\Tests;

  use DeBaasMedia\OutlineBuilder\Index
    , DeBaasMedia\OutlineBuilder\Exception\InvalidArgumentException;

  require_once __DIR__ . '/../Index.php';
  require_once __DIR__ . '/../Exception/ExceptionInterface.php';
  require_once __DIR__ . '/../Exception/InvalidArgumentException.php';

  class IndexTest extends \PHPUnit_Framework_TestCase
  {

    public function testInstantiate ()
    {
      try
      {
        new Index(-1);
      }
      catch (InvalidArgumentException $exception)
      {
        return new Index(3);
      }

      $this->fail('No exception was raised when instantiating a Index with a negative nesting level');
    }

    /**
     * @depends testInstantiate
     */
    public function testAdd (Index $arg_index)
    {
      $arg_index->addReferenceForLevel(1, new \SimpleXMLElement("<span>Item (1)</span>"));

      $expected = "<?xml version=\"1.0\"?>\n"
                . "<ol><li>Item (1)</li></ol>\n";

      $this->assertEquals($expected, (string) $arg_index, "The index contains a single item");

      return $arg_index;
    }

    /**
     * @depends testAdd
     */
    public function testAddIndentedItem (Index $arg_index)
    {
      $arg_index->addReferenceForLevel(2, new \SimpleXMLElement("<span>Item (1.1)</span>"));

      $expected = "<?xml version=\"1.0\"?>\n"
                . "<ol>"
                .   "<li>Item (1)</li>"
                .   "<li><ol><li>Item (1.1)</li></ol></li>"
                . "</ol>\n";

      $this->assertEquals($expected, (string) $arg_index, "The index contains a single item with a sub item");

      return $arg_index;
    }

    /**
     * @depends testAddIndentedItem
     */
    public function testAddOutdentedItem (Index $arg_index)
    {
      $arg_index->addReferenceForLevel(1, new \SimpleXMLElement("<span>Item (2)</span>"));

      $expected = "<?xml version=\"1.0\"?>\n"
                . "<ol>"
                .   "<li>Item (1)</li>"
                .   "<li><ol><li>Item (1.1)</li></ol></li>"
                .   "<li>Item (2)</li>"
                . "</ol>\n";


      $this->assertEquals($expected, (string) $arg_index, "The index contains a single item with a sub item and a second item");

      return $arg_index;
    }

  }