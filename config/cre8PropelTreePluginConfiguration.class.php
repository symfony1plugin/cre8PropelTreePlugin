<?php

class cre8PropelTreePluginConfiguration extends sfPluginConfiguration {
    const VERSION = '1.0.0-DEV';

    public function initialize() {
        if (in_array('cre8_tree', sfConfig::get('sf_enabled_modules', array()))) {
            if (sfConfig::get('app_cre8_propel_tree_plugin_routes_register', true)) {
                $this->dispatcher->connect('routing.load_configuration', array('cre8TreeRouting', 'listenToRoutingLoadConfigurationTree'));
            }

            $this->dispatcher->connect('cre8_tree.tree_types_setup', array('cre8JsTreeEventListener', 'treeTypesSetup'));
            $this->dispatcher->connect('cre8_tree.tree_contextmenu_setup', array('cre8JsTreeEventListener', 'treeContextmenuSetup'));
        }
    }

}
