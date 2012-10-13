<?php

/**
 * This class is part of PHP API for jsTree (jQuery plugin).
 * It generate responses for request in "xml_data" mode.
 */
class cre8PropelTreeUtils {
    const TREE_XML_ROOT = 'root';
    const TREE_XML_ROOT_ITEM = 'item';
    const TREE_XML_ROOT_ITEM_CONTENT = 'content';
    const TREE_XML_ROOT_ITEM_TYPE = 'rel';
    const TREE_XML_ROOT_ITEM_CONTENT_NAME = 'name';
    const TREE_XML_ROOT_ITEM_ATTR_ID = 'id';
    const TREE_XML_ROOT_ITEM_ATTR_ID_PREFIX = 'node_';

    /**
     * Return XML string
     *
     * @param cre8CategoryTree $treeRoot
     * @param string $xmlVersion
     * @param string $xmlEncoding
     * @return string/boolean
     */
    public static function transformTreeToXml(cre8CategoryTree $treeRoot, $xmlVersion = '1.0', $xmlEncoding = 'UTF-8') {
        $xmlDocument = new DOMDocument($xmlVersion, $xmlEncoding);
        $root = $xmlDocument->createElement(self::TREE_XML_ROOT);
        $xmlDocument->appendChild($root);

        if ($treeRoot->isRoot()) {
            self::addNode($xmlDocument, $root, $treeRoot);
        } else {
            self::addChildren($xmlDocument, $root, $treeRoot);
        }

        return $xmlDocument->saveXML();
    }

    /**
     * Recursively transform nested set to xml
     *
     * @param DOMDocument $xmlDocument
     * @param DOMElement $xmlRoot
     * @param cre8CategoryTree $node 
     */
    private static function addNode(DOMDocument $xmlDocument, DOMElement $xmlRoot, cre8CategoryTree $node) {
        $nestedRoot = $xmlDocument->createElement(self::TREE_XML_ROOT_ITEM);
        $nestedRoot->setAttribute(self::TREE_XML_ROOT_ITEM_ATTR_ID, self::TREE_XML_ROOT_ITEM_ATTR_ID_PREFIX . $node->getId());
        if ($node->getType()) {
            $nestedRoot->setAttribute(self::TREE_XML_ROOT_ITEM_TYPE, $node->getType());
        }

        $contentNode = $xmlDocument->createElement(self::TREE_XML_ROOT_ITEM_CONTENT);
        $titleNode = $xmlDocument->createElement(self::TREE_XML_ROOT_ITEM_CONTENT_NAME);
        $titleNode->appendChild($xmlDocument->createTextNode($node->getName()));
        $contentNode->appendChild($titleNode);

        $nestedRoot->appendChild($contentNode);
        $xmlRoot->appendChild($nestedRoot);

        if ($node->hasChildren()) {
            foreach ($node->getChildren() as $element) {
                self::addNode($xmlDocument, $nestedRoot, $element);
            }
        }
    }

    /**
     * Recursively transform nested set to xml. It render only children for given subnode
     *
     * @param DOMDocument $xmlDocument
     * @param DOMElement $xmlRoot
     * @param cre8CategoryTree $node
     */
    private static function addChildren(DOMDocument $xmlDocument, DOMElement $xmlRoot, cre8CategoryTree $node) {
        if ($node->hasChildren()) {
            foreach ($node->getChildren() as $element) {
                $nestedRoot = $xmlDocument->createElement(self::TREE_XML_ROOT_ITEM);
                $nestedRoot->setAttribute(self::TREE_XML_ROOT_ITEM_ATTR_ID, self::TREE_XML_ROOT_ITEM_ATTR_ID_PREFIX . $element->getId());
                if ($node->getType()) {
                    $nestedRoot->setAttribute(self::TREE_XML_ROOT_ITEM_TYPE, $node->getType());
                }

                $contentNode = $xmlDocument->createElement(self::TREE_XML_ROOT_ITEM_CONTENT);
                $titleNode = $xmlDocument->createElement(self::TREE_XML_ROOT_ITEM_CONTENT_NAME);
                $titleNode->appendChild($xmlDocument->createTextNode($element->getName()));
                $contentNode->appendChild($titleNode);

                $nestedRoot->appendChild($contentNode);
                $xmlRoot->appendChild($nestedRoot);
                if ($element->hasChildren()) {
                    self::addChildren($xmlDocument, $nestedRoot, $element);
                }
            }
        }
    }

}
