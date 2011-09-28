<?php

  namespace DeBaasMedia\OutlineBuilder;

  use DoctrineExtensions\Sluggable\SlugNormalizer
    , SimpleXMLElement
    , Symfony\Component\CssSelector\CssSelector;

  class Builder
  {

    const DEFAULT_NAMESPACE_PREFIX = 'xhtml';

    private $_article;

    private $_index;

    private $_namespacePrefix;

    public function __construct (SimpleXMLElement $arg_article, Index $arg_index, $arg_namespacePrefix = self::DEFAULT_NAMESPACE_PREFIX, $arg_namespace = NULL)
    {
      $this->_article         = $arg_article;
      $this->_index           = $arg_index;
      $this->_namespacePrefix = '';

      if (NULL !== $arg_namespace)
      {
        $this->registerNamespace($arg_namespacePrefix, $arg_namespace);
      }
    }

    public function execute ()
    {
      $prefix = $this->getNamespacePrefix() . 'h';
      $query  = $prefix . implode(', ' . $prefix, range(1, 6));

      foreach ($this->getArticle()->xpath(CssSelector::toXpath($query)) as $node)
      {
        $text       = (string) $node;
        $level      = $this->_extractHeadingLevel($node->getName());
        $normalizer = new SlugNormalizer($text);
        $dom        = dom_import_simplexml($node);
        $urn        = $normalizer->normalize();

        $element = $dom->ownerDocument->createElement('a', $text);

        $element->setAttribute('id', $urn);
        $element->setAttribute('class', 'table-of-contents');

        $dom->replaceChild($element, $dom->firstChild);

        $this->_index->addReferenceForLevel($level)
                     ->addChild('a', $text)
                     ->addAttribute('href', "#$urn");
      }
    }

    public function registerNamespace ($arg_namespacePrefix, $arg_namespace)
    {
      $this->getArticle()->registerXPathNamespace($arg_namespacePrefix, $arg_namespace);
      $this->setNamespacePrefix($arg_namespacePrefix);
    }

    public function setNamespacePrefix ($arg_namespacePrefix)
    {
      if (strpos($arg_namespacePrefix, '|') !== strlen($arg_namespacePrefix))
      {
        throw new InvalidArgumentException("Incorrect namespace prefix");
      }

      $this->_namespacePrefix = $arg_namespacePrefix;

      return $this;
    }

    public function getNamespacePrefix ()
    {
      return $this->_namespacePrefix;
    }

    public function getArticle ()
    {
      return $this->_article;
    }

    private function _extractHeadingLevel ($arg_headingTag)
    {
      return (int) ltrim(strtolower($arg_headingTag), 'h');
    }

  }
