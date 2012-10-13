<?php if ($jsTree->isPluginEnabled(cre8JsTree::PLUGIN_HOTKEYS) && sfConfig::get('all_cre8_propel_tree_plugin_jquery_hotkeys_enable', true))
    $sf_response->addJavascript(cre8JsTree::JAVASCRIPT_LIB_DIRECTORY . cre8JsTree::JAVASCRIPT_HOTKEYS) ?>
<?php if ($jsTree->isPluginEnabled(cre8JsTree::PLUGIN_COOKIES) && sfConfig::get('all_cre8_propel_tree_plugin_jquery_cookies_enable', true))
        $sf_response->addJavascript(cre8JsTree::JAVASCRIPT_LIB_DIRECTORY . cre8JsTree::JAVASCRIPT_COOKIE) ?>
<?php $sf_response->addJavascript(cre8JsTree::JAVASCRIPT_ROOT_DIRECTORY . cre8JsTree::JAVASCRIPT_MAIN) ?>

<div id="cre8_tree"></div>

<?php echo $sf_data->getRaw('jsTree')->render('cre8_tree'); ?>