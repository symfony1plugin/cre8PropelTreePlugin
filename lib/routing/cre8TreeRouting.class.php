<?php

class cre8TreeRouting {

    /**
     *
     * @param sfEvent $event 
     */
    static public function listenToRoutingLoadConfigurationTree(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('cre8_tree_operation', new sfRoute('/tree', array('module' => 'cre8_tree', 'action' => 'operation')));
    }

}