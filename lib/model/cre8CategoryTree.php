<?php

class cre8CategoryTree extends Basecre8CategoryTree {

    /**
     *
     * @param boolean $deepCopy
     * @return cre8CategoryTree
     */
    public function copy($deepCopy = false) {
        $copyObj = parent::copy($deepCopy);
        $copyObj->setScopeValue(0);
        $copyObj->setLeftValue(0);
        $copyObj->setRightValue(0);
        $copyObj->setLeftValue(0);
        $copyObj->setParent();

        return $copyObj;
    }

    /**
     *
     * @param PropelPDO $con
     * @return boolean
     */
    public function deleteBranch(PropelPDO $con = null) {
        if ($con === null) {
            $con = Propel::getConnection(cre8CategoryTreePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $con->beginTransaction();
        try {
            $root = cre8CategoryTreePeer::retrieveRoot($this->getScopeValue());

            $this->deleteDescendants($con);
            $this->delete($con);
            $con->commit();

            if ($root) {
                cre8PropelTreeCache::set($root->getId(), cre8PropelTreeUtils::transformTreeToXml($root));
            }

            return true;
        } catch (Exception $e) {
            $con->rollBack();

            return false;
        }
    }

    /**
     *
     * @param PropelPDO $con
     * @param boolean $rebuildCache
     */
    public function save(PropelPDO $con = null, $rebuildCache = true) {
        parent::save($con);

        if ($rebuildCache) {
            $this->rebuildCache();
        }
    }

    /**
     * 
     * @param array $children
     * @param PropelPDO $con
     * @param boolean $rebuildCache
     */
    public function copyDescendants($children, PropelPDO $con = null, $rebuildCache = true) {
        if ($con === null) {
            $con = Propel::getConnection(cre8CategoryTreePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        foreach ($children as $child) {
            if ($child->hasChildren()) {
                $this->copyDescendants($child->getChildren(), $con);
            } else {
                $newChild = $child->copy();
                $this->addChild($newChild);
                $newChild->save($con, false);
            }
        }

        if ($rebuildCache) {
            $this->rebuildCache();
        }
    }

    /**
     *
     * @param array $children
     * @param PropelPDO $con
     */
    public function saveWithDescendants($children, PropelPDO $con = null) {
        if ($con === null) {
            $con = Propel::getConnection(cre8CategoryTreePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $con->beginTransaction();
        try {
            $this->save($con, false);
            $this->copyDescendants($children, $con);

            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
        }
    }

    /**
     * Function rebuild cache for root of current object
     */
    private function rebuildCache() {
        if ($this->isRoot()) {
            cre8PropelTreeCache::set($this->getId(), cre8PropelTreeUtils::transformTreeToXml($this));
        } else {
            $root = cre8CategoryTreePeer::retrieveRoot($this->getScopeValue());
            if ($root) {
                cre8PropelTreeCache::set($root->getId(), cre8PropelTreeUtils::transformTreeToXml($root));
            }
        }
    }

}

// cre8CategoryTree

