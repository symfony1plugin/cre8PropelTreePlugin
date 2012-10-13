<?php

class cre8_treeComponents extends sfComponents {

    /**
     * Display dynamic tree
     */
    public function executeTree() {
        $this->jsTree = new cre8JsTree($this->rootId);
        $this->jsTree->setOperationsUrl($this->generateUrl(sfConfig::get('all_cre8_propel_tree_plugin_opeation_route', 'cre8_tree_operation')));
        $this->jsTree->enableBasePlugins();

        $dispatcher = ProjectConfiguration::getActive()->getEventDispatcher();

        $event = $dispatcher->notify(new sfEvent('cre8JsTreeEventListener', 'cre8_tree.tree_types_setup'));
        $this->jsTree->enablePlugin(cre8JsTree::PLUGIN_TYPES, array('types' => $event->getReturnValue()));

        $event = $dispatcher->notify(new sfEvent('cre8JsTreeEventListener', 'cre8_tree.tree_contextmenu_setup'));
        $this->jsTree->enablePlugin(cre8JsTree::PLUGIN_CONTEXTMENU, array('items' => $event->getReturnValue()));
    }

}
