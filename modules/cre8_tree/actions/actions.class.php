<?php

class cre8_treeActions extends sfActions {

    /**
     * Depending of operation type function return JSON response, or well formatted XML document
     *
     * @param sfWebRequest $request
     * @return string
     */
    public function executeOperation(sfWebRequest $request) {
        $this->output = null;

        switch ($request->getParameter(cre8JsTree::PARAM_OPERATION)) {
            case cre8JsTree::VAL_OPERATION_GET_CHILDREN:
                $this->output = $this->operationGetChildren($request);
                break;

            case cre8JsTree::VAL_OPERATION_CREATE_NODE:
                $this->output = $this->operationCreateNode($request);
                break;

            case cre8JsTree::VAL_OPERATION_RENAME_NODE:
                $this->output = $this->operationRenameNode($request);
                break;

            case cre8JsTree::VAL_OPERATION_MOVE_NODE:
                $this->output = $this->operationMoveNode($request);
                break;

            case cre8JsTree::VAL_OPERATION_DELETE_NODE:
                $this->output = $this->operationDeleteNode($request);
                break;
        }

        return $this->renderText($this->output);
    }

    /**
     * Function try to retrieve tree from cache. If cache don't exist function retrieve data from database and generate xml tree
     *
     * @param sfWebRequest $request
     * @return string
     */
    private function operationGetChildren(sfWebRequest $request) {
        $childrenId = $request->getParameter(cre8JsTree::PARAM_ID);

        $nodeCache = cre8PropelTreeCache::get($childrenId);
        if (is_null($nodeCache)) {
            $this->root = cre8CategoryTreeQuery::create()->findOneById($childrenId);
            if ($this->root) {
                $this->xml = cre8PropelTreeUtils::transformTreeToXml($this->root);
                cre8PropelTreeCache::set($this->root->getId(), $this->xml);
            }
        } else {
            $this->xml = $nodeCache;
        }

        $this->getResponse()->setHttpHeader('Content-Type', 'application/xml');

        return $this->xml;
    }

    /**
     * If success function return JSON string with status (1) and id of created node, otherwise only status (0)
     *
     * @param sfWebRequest $request
     * @return string
     */
    private function operationCreateNode(sfWebRequest $request) {
        $rootNode = cre8CategoryTreeQuery::create()->findOneById($request->getParameter(cre8JsTree::PARAM_ID));
        $output = array();

        if ($rootNode) {
            $childrenNodeType = $request->getParameter(cre8JsTree::PARAM_NODE_TYPE);
            if ($this->validChildren($rootNode->getType(), $childrenNodeType)) {
                try {
                    $newNode = new cre8CategoryTree();
                    $newNode->setName($request->getParameter(cre8JsTree::PARAM_TITLE));
                    $rootNode->addChild($newNode);
                    $newNode->save();

                    if ($newNode->getId()) {
                        $output[cre8JsTree::PARAM_ID] = $newNode->getId();
                        $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_SUCCESS;
                    } else {
                        $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                    }
                } catch (Exception $e) {
                    $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                }
            } else {
                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                $output[cre8JsTree::PARAM_STATUS_MSG] = "Object of '{$childrenNodeType}' type cannot be created under object of '{$rootNode->getType()}' type";
            }
        } else {
            $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
        }

        return $this->returnJson($output);
    }

    /**
     * If success function return JSON string with status (1) otherwise status (0)
     * 
     * @param sfWebRequest $request
     * @return string
     */
    private function operationRenameNode(sfWebRequest $request) {
        $node = cre8CategoryTreeQuery::create()->findOneById($request->getParameter(cre8JsTree::PARAM_ID));
        $output = array();

        if ($node) {
            try {
                $node->setName($request->getParameter(cre8JsTree::PARAM_TITLE));
                $node->save();

                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_SUCCESS;
            } catch (Exception $e) {
                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
            }
        } else {
            $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
        }

        return $this->returnJson($output);
    }

    /**
     * If success function return JSON string with status (1) and id of moved/copied node, otherwise only status (0)
     *
     * @param sfWebRequest $request
     * @return string
     */
    private function operationMoveNode(sfWebRequest $request) {
        $rootNode = cre8CategoryTreeQuery::create()->findOneById($request->getParameter(cre8JsTree::PARAM_REF));
        $childNode = cre8CategoryTreeQuery::create()->findOneById($request->getParameter(cre8JsTree::PARAM_ID));
        $output = array();

        if ($rootNode && $childNode) {
            if ($this->validChildren($rootNode->getType(), $childNode->getType())) {
                try {
                    $position = $request->getParameter(cre8JsTree::PARAM_POSITION);

                    switch ($request->getParameter(cre8JsTree::PARAM_COPY)) {
                        case cre8JsTree::VAL_COPY_COPY:
                            $newChild = $childNode->copy();
                            if ($position == 0) {
                                $newChild->insertAsFirstChildOf($rootNode);
                            } else {
                                $rootChildren = $rootNode->getChildren();
                                $sibling = $rootChildren[$position - 1];
                                $newChild->insertAsNextSiblingOf($sibling);
                            }
                            $newChild->saveWithDescendants($childNode->getChildren());

                            if ($newChild->getId()) {
                                $output[cre8JsTree::PARAM_ID] = $newChild->getId();
                                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_SUCCESS;
                            } else {
                                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                            }
                            break;
                        case cre8JsTree::VAL_COPY_CUT:
                            if ($position == 0) {
                                $childNode->moveToFirstChildOf($rootNode);
                            } else {
                                $rootChildren = $rootNode->getChildren();
                                $sibling = $rootChildren[$position - 1];
                                $childNode->moveToNextSiblingOf($sibling);
                            }
                            $output[cre8JsTree::PARAM_ID] = $childNode->getId();
                            $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_SUCCESS;
                            break;
                        default:
                            $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                    }
                } catch (Exception $e) {
                    $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                }
            } else {
                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                $output[cre8JsTree::PARAM_STATUS_MSG] = "Object of '{$childNode->getType()}' type cannot be moved/copied under object of '{$rootNode->getType()}' type";
            }
        } else {
            $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
        }

        return $this->returnJson($output);
    }

    /**
     * If success function return JSON string with status (1) otherwise status (0)
     *
     * @param sfWebRequest $request
     * @return string
     */
    private function operationDeleteNode(sfWebRequest $request) {
        $node = cre8CategoryTreeQuery::create()->findOneById($request->getParameter(cre8JsTree::PARAM_ID));
        $output = array();

        if ($node && !is_null($node->getScopeValue())) {
            try {
                $removed = $node->deleteBranch();

                if ($removed) {
                    $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_SUCCESS;
                } else {
                    $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
                }
            } catch (Exception $e) {
                $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
            }
        } else {
            $output[cre8JsTree::PARAM_STATUS] = cre8JsTree::VAL_STATUS_FAIL;
        }

        return $this->returnJson($output);
    }

    /**
     * Function set response type to text/json and return $output encoded to JSON format
     *
     * @param array $output
     * @return string
     */
    private function returnJson($output) {
        $this->getResponse()->setHttpHeader('Content-Type', 'text/json');

        return json_encode($output);
    }

    /**
     * Function check if new children (depending on it type) could be created under given parent type
     *
     * @param string $parentType
     * @param string $childrenType
     * @return boolean
     */
    private function validChildren($parentType, $childrenType) {
        if (empty($parentType) || empty($childrenType)) {
            return false;
        }
        $childrenTypes = cre8JsTree::getValidChildrenTypes($parentType);
        if (count($childrenTypes) > 0) {
            return in_array($childrenType, $childrenTypes) ? true : false;
        } else {
            return false;
        }

        return true;
    }

}
