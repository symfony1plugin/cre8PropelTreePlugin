<?php

/**
 * This class is PHP API for jsTree (jQuery plugin)
 * Class dveloped & tested with jsTree v.1.0rc2
 *
 *
 * @see http://www.jstree.com
 * @todo This class isn't full PHP API for jsTree plugin, only base requirements was implemented
 */
class cre8JsTree {
    const PARAM_OPERATION = 'operation';
    const VAL_OPERATION_CREATE_NODE = 'create_node';
    const VAL_OPERATION_RENAME_NODE = 'rename_node';
    const VAL_OPERATION_MOVE_NODE = 'move_node';
    const VAL_OPERATION_DELETE_NODE = 'remove_node';
    const VAL_OPERATION_GET_CHILDREN = 'get_children';

    const PARAM_ID = 'id';
    const PARAM_POSITION = 'position';
    const PARAM_REF = 'ref';
    const PARAM_TITLE = 'title';

    const PARAM_STATUS = 'status';
    const VAL_STATUS_SUCCESS = 1;
    const VAL_STATUS_FAIL = 0;
    const PARAM_STATUS_MSG = 'msg';

    const PARAM_COPY = 'copy';
    const VAL_COPY_COPY = 1;
    const VAL_COPY_CUT = 0;

    const PARAM_NODE_TYPE = 'type';
    const VAL_NODE_TYPE_PARENT = 'folder';
    const VAL_NODE_TYPE_ELEMENT = 'default';
    const VAL_NODE_TYPE_DRIVE = 'drive';
    const VAL_NODE_TYPE_ROOT = '';

    const JAVASCRIPT_MAIN = 'jquery.jstree.js';
    const JAVASCRIPT_HOTKEYS = 'jquery.hotkeys.js';
    const JAVASCRIPT_COOKIE = 'jquery.cookie.js';
    const JAVASCRIPT_ROOT_DIRECTORY = '/cre8PropelTreePlugin/jsTree/';
    const JAVASCRIPT_LIB_DIRECTORY = '/cre8PropelTreePlugin/jsTree/_lib/';

    /**
     * The html_data plugin enables jsTree to convert nested unordered lists to interactive trees.
     * jsTree can also get HTML from the server insert it into the DOM and convert that to a tree.
     * @see http://www.jstree.com/documentation/html_data
     */
    const PLUGIN_HTML_DATA = 'html_data';
    /**
     * The json_data plugin enables jsTree to convert JSON objects to interactive trees.
     * The data (JSON) can be set up in the config or retrieved from a server (also ondemand).
     * Version 1.0 also introduces the experimental progressive render feature, which is suitable for large heavy trees,
     * when the DOM would be too heavy to manipulate.
     * @see http://www.jstree.com/documentation/json_data
     */
    const PLUGIN_JSON_DATA = 'json_data';
    /**
     * The xml_data plugin enables jsTree to convert XML objects to interactive trees (using XSL). The data (XML) can be set up in the config (as a string) or retrieved from a server (also ondemand).
     * @see http://www.jstree.com/documentation/xml_data
     */
    const PLUGIN_XML_DATA = 'xml_data';
    /**
     * The themes plugin controls the looks of jstree - without this plugin you will get a functional tree, but it will look just like an ordinary UL list.
     * @see http://www.jstree.com/documentation/themes
     */
    const PLUGIN_THEMES = 'themes';
    /**
     * The UI plugin handles selecting, deselecting and hovering tree items.
     * @see http://www.jstree.com/documentation/ui
     */
    const PLUGIN_UI = 'ui';
    /**
     * The CRRM plugin handles creating, renaming, removing and moving nodes by the user.
     * @see http://www.jstree.com/documentation/crrm
     */
    const PLUGIN_CRRM = 'crrm';
    /**
     * The hotkeys plugin enables keyboard navigation and shortcuts. Depends on the jquery.hotkeys plugin
     * @see http://www.jstree.com/documentation/hotkeys
     */
    const PLUGIN_HOTKEYS = 'hotkeys';
    /**
     * The languages plugin enables multilanguage trees. This means that each node has a specified number of titles - each in a different "language".
     * Only one language set is visible at any given time.
     * This is useful for maintaining the same structure in many languages (hence the name of the plugin)
     * @see http://www.jstree.com/documentation/languages
     */
    const PLUGIN_LANGUAGES = 'languages';
    /**
     * The cookies enables jstree to save the state of the tree across sessions. What this does is save the opened and selected nodes in a cookie,
     * and reopen & reselect them the next time the user loads the tree. Depends on the jQuery.cookie plugin.
     * @see http://www.jstree.com/documentation/cookies
     */
    const PLUGIN_COOKIES = 'cookies';
    /**
     * The sort enables jstree to automatically sort all nodes using a specified function. This means that when the user creates, renames or moves nodes around - they will automatically sort.
     * @see http://www.jstree.com/documentation/sort
     */
    const PLUGIN_SORT = 'sort';
    /**
     * The dnd plugin enables drag'n'drop support for jstree, also using foreign nodes and drop targets.
     * @see http://www.jstree.com/documentation/dnd
     */
    const PLUGIN_DND = 'dnd';
    /**
     * The checkbox plugin makes multiselection possible using three-state checkboxes.
     * @see http://www.jstree.com/documentation/checkbox
     */
    const PLUGIN_CHECKBOX = 'checkbox';
    /**
     * The search plugin enables searching for nodes whose title contains a given string, works on async trees too.
     * All found nodes get the jstree-search class applied to their contained a nodes - you can use that class to style search results.
     * @see http://www.jstree.com/documentation/search
     */
    const PLUGIN_SEARCH = 'search';
    /**
     * The contextmenu plugin enables a contextual menu to be shown, when the user right-clicks a node (or when triggered programatically by the developer).
     * @see http://www.jstree.com/documentation/contextmenu
     */
    const PLUGIN_CONTEXTMENU = 'contextmenu';
    /**
     * The types enables node types - each node can have a type, and you can define rules on how that type should behave -
     * maximum children count, maximum depth, valid children types, selectable or not, etc.
     * @see http://www.jstree.com/documentation/types
     */
    const PLUGIN_TYPES = 'types';
    /**
     * The themeroller plugin adds support for jQuery UI's themes. Add the plugin as last in your plugins config option.
     * Also make sure that you have included the jquery theme you'd like to use and you should NOT use the native jstree themes plugin.
     * @see http://www.jstree.com/documentation/themeroller
     */
    const PLUGIN_THEMEROLLER = 'themeroller';
    /**
     * The unique plugin prevents from nodes with same titles coexisting (create/move/rename) in the same parent.
     * @see http://www.jstree.com/documentation/unique
     */
    const PLUGIN_UNIQUE = 'unique';

    private $rootId;
    private $containerId;
    private $enabledPlugins;
    private $operationsUrl;

    /**
     *
     * @param integer $rootId
     */
    public function __construct($rootId) {
        $this->rootId = $rootId;
    }

    /**
     *
     *
     * @param string $containerId html id
     * @return string
     */
    public function render($containerId) {
        $this->containerId = $containerId;
        $core = $this->configCore();
        $plugins = $this->configPlugins();

        $bindCrrm = ($this->isPluginEnabled(self::PLUGIN_CRRM)) ? $this->bindCrrmPlugin() : null;

        $output = <<<EOF
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#{$containerId}")
                .jstree({
                {$core},
                {$plugins}

            }){$bindCrrm};
        });
        </script>
EOF;

        return $output;
    }

    /**
     *
     * @param string $pluginName As param use class constants, named with prefix PLUGIN_
     * @param array $pluginParams
     */
    public function enablePlugin($pluginName, $pluginParams = array()) {
        $this->enabledPlugins[$pluginName] = $pluginParams;
    }

    /**
     * Function enable plugins required to achieve base functionality:
     * themes, xml_data, ui, crrm, cookies, dnd, hotkeys, contextmenu
     */
    public function enableBasePlugins() {
        $this->enablePlugin(self::PLUGIN_XML_DATA);
        $this->enablePlugin(self::PLUGIN_UI);
        $this->enablePlugin(self::PLUGIN_CRRM);
        $this->enablePlugin(self::PLUGIN_DND);
        $this->enablePlugin(self::PLUGIN_COOKIES);
        $this->enablePlugin(self::PLUGIN_HOTKEYS);
        $this->enablePlugin(self::PLUGIN_CONTEXTMENU);
        $this->enablePlugin(self::PLUGIN_TYPES);
        $this->enablePlugin(self::PLUGIN_THEMES);
    }

    /**
     *
     * @param string $pluginName
     * @return boolean
     */
    public function isPluginEnabled($pluginName) {
        return array_key_exists($pluginName, $this->enabledPlugins);
    }

    /**
     *
     * @return integer 
     */
    public function getRootId() {
        return $this->rootId;
    }

    /**
     *
     * @param integer $rootId
     */
    public function setRootId($rootId) {
        $this->rootId = $rootId;
    }

    /**
     *
     * @return string 
     */
    public function getOperationsUrl() {
        return $this->operationsUrl;
    }

    /**
     *
     * @param string $operationsUrl
     */
    public function setOperationsUrl($operationsUrl) {
        $this->operationsUrl = $operationsUrl;
    }

    /**
     * Generate jQuery function to create node of given type
     *
     * @param string $nodeType
     * @param string $position
     * @return string
     */
    public function generateCreateTreeNodeFunction($nodeType, $position = 'last') {
        return <<<EOF
        jQuery("#{$this->getContainerId()}").jstree("create", null, "{$position}", { "attr" : { "rel" : "{$nodeType}" } });
EOF;
    }

    /**
     *
     * @return string
     */
    public function getContainerId() {
        return $this->containerId;
    }

    /**
     *
     * @param string $containerId
     */
    public function setContainerId($containerId) {
        $this->containerId = $containerId;
    }

    /**
     * Return array of available tree types
     *
     * @return array
     */
    public static function getAvailableTypes() {
        return sfConfig::get('cre8_propel_tree_plugin_tree_types_available', array());
    }

    /**
     * Return valid children types for given type. If there's no types, return emtpy array
     *
     * @param string $parentType
     * @return array
     */
    public static function getValidChildrenTypes($parentType) {
        return sfConfig::get('cre8_propel_tree_plugin_tree_types_' . $parentType, array());
    }

    /**
     *
     * @return string
     */
    private function configCore() {
        $output = <<<EOF
			"core" : {
				// just open those two nodes up
				"initially_open" : []
			}
EOF;
        return $output;
    }

    /**
     *
     * @return string
     */
    private function configPlugins() {
        $output = null;

        if (count($this->enabledPlugins) > 0) {
            $config = null;
            $plugins = null;
            $pluginsConfig = null;

            foreach ($this->enabledPlugins as $pluginName => $pluginParams) {
                $plugins .= '"' . $pluginName . '", ';
                $pluginsConfig .= $this->configPlugin($pluginName, $pluginParams);
            }

            $output = <<<EOF
            "plugins" : [ {$plugins} ],
            {$config}
            {$pluginsConfig}
EOF;
        }

        return $output;
    }

    /**
     *
     * @param string $pluginName
     * @param array $pluginParams
     * @return string
     */
    private function configPlugin($pluginName, $pluginParams = array()) {
        $output = null;
        switch ($pluginName) {
            case self::PLUGIN_XML_DATA:
                $output = <<<EOF
			"{$pluginName}" : {
				"ajax" : {
					"url" : "{$this->getOperationsUrl()}",
					"data" : function (n) {
						return {
							"operation" : "get_children",
							"id" : n.attr ? n.attr("id").replace("node_","") : {$this->getRootId()}
						};
					}
				},
                                "xsl" : "nest"
			},
EOF;
                break;

            case self::PLUGIN_TYPES:
                $defaultParams = array('max_depth' => -2,
                    'max_children' => -2,
                    'valid_children' => '',
                    'types' => '');

                $defaultParams = array_merge($defaultParams, $pluginParams);

                $output = <<<EOF
			"{$pluginName}" : {
				// I set both options to -2, as I do not need depth and children count checking
				// Those two checks may slow jstree a lot, so use only when needed
				"max_depth" : {$defaultParams['max_depth']},
				"max_children" : {$defaultParams['max_children']},
				// I want only `drive` nodes to be root nodes
				// This will prevent moving or creating any other type as a root node
				"valid_children" : [ {$defaultParams['valid_children']} ],
				"types" : { {$defaultParams['types']} }
			},
EOF;
                break;

            case self::PLUGIN_UI:
                $output = <<<EOF
			// the UI plugin - it handles selecting/deselecting/hovering nodes
			"{$pluginName}" : {
				"initially_select" : [ "node_1" ]
			},
EOF;

            case self::PLUGIN_THEMES:
                $defaultParams = array('theme' => '"default"',
                    'dots' => 'true',
                    'icons' => 'true',
                    'url' => 'false');

                $defaultParams = array_merge($defaultParams, $pluginParams);

                $output = <<<EOF
			"{$pluginName}" : {
                            "theme":  {$defaultParams['theme']},
                            "dots":   {$defaultParams['dots']},
                            "icons":  {$defaultParams['icons']},
                            "url":    {$defaultParams['url']}
			},
EOF;
                break;

            case self::PLUGIN_CRRM:
                $defaultParams = array('input_width_limit' => 200,
                );

                $defaultParams = array_merge($defaultParams, $pluginParams);

                $output = <<<EOF
			"{$pluginName}" : {
                            "input_width_limit":  {$defaultParams['input_width_limit']}
			},
EOF;
                break;

            case self::PLUGIN_LANGUAGES:
                $languages = '"' . implode('", "', $pluginParams['languages']) . '"';
                $output = <<<EOF
			"{$pluginName}" : {
                            "languages" : [ {$languages} ]
			},
EOF;
                break;

            case self::PLUGIN_COOKIES:
                $defaultParams = array('save_opened' => '"jstree_open"',
                    'save_selected' => 'false'/* '"jstree_select"' */,
                    'auto_save' => 'true');

                $defaultParams = array_merge($defaultParams, $pluginParams);

                $output = <<<EOF
			"{$pluginName}" : {
                            "save_opened":      {$defaultParams['save_opened']},
                            "save_selected":    {$defaultParams['save_selected']},
                            "auto_save":        {$defaultParams['auto_save']}
			},
EOF;
                break;

            case self::PLUGIN_THEMEROLLER:
                $defaultParams = array('opened' => '"ui-icon-triangle-1-se',
                    'closed' => '"ui-icon-triangle-1-e"',
                    'item' => '"ui-state-default"',
                    'item_h' => '"ui-state-hover"',
                    'item_a' => '"ui-state-active"',
                    'item_icon' => '"ui-icon-folder-collapsed"');

                $defaultParams = array_merge($defaultParams, $pluginParams);

                $output = <<<EOF
			"{$pluginName}" : {
                            "opened":       {$defaultParams['opened']},
                            "closed":       {$defaultParams['closed']},
                            "item":         {$defaultParams['item']},
                            "item_h":       {$defaultParams['item_h']},
                            "item_a":       {$defaultParams['item_a']},
                            "item_icon":    {$defaultParams['item_icon']},

			},
EOF;
                break;

            case self::PLUGIN_CONTEXTMENU:
                $defaultParams = array('select_node' => 'false',
                    'show_at_node' => 'true',
                    'items' => '');

                $defaultParams = array_merge($defaultParams, $pluginParams);

                $output = <<<EOF
			"{$pluginName}" : {
                            "select_node": {$defaultParams['select_node']},
                            "show_at_node":{$defaultParams['show_at_node']},
                            "items":       {{$defaultParams['items']}}
			},
EOF;
                break;
        }

        return $output;
    }

    /**
     * Binds actions (create, rename, remove, move) to tree list
     * @return string
     */
    private function bindCrrmPlugin() {
        $output = <<<EOF
.bind("create.jstree", function (e, data) {
			jQuery.post(
				"{$this->getOperationsUrl()}",
				{
					"operation" : "create_node",
					"id" : data.rslt.parent.attr("id").replace("node_",""),
					"position" : data.rslt.position,
					"title" : data.rslt.name,
					"type" : data.rslt.obj.attr("rel")
				},
				function (r) {
					if(r.status) {
						jQuery(data.rslt.obj).attr("id", "node_" + r.id);
					}
					else {
						jQuery.jstree.rollback(data.rlbk);
					}
				}
			);
		})
		.bind("remove.jstree", function (e, data) {
			data.rslt.obj.each(function () {
				jQuery.ajax({
					async : false,
					type: 'POST',
					url: "{$this->getOperationsUrl()}",
					data : {
						"operation" : "remove_node",
						"id" : this.id.replace("node_","")
					},
					success : function (r) {
						if(!r.status) {
							data.inst.refresh();
						}
					}
				});
			});
		})
		.bind("rename.jstree", function (e, data) {
			jQuery.post(
				"{$this->getOperationsUrl()}",
				{
					"operation" : "rename_node",
					"id" : data.rslt.obj.attr("id").replace("node_",""),
					"title" : data.rslt.new_name
				},
				function (r) {
					if(!r.status) {
						jQuery.jstree.rollback(data.rlbk);
					}
				}
			);
		})
		.bind("move_node.jstree", function (e, data) {
			data.rslt.o.each(function (i) {
				jQuery.ajax({
					async : false,
					type: 'POST',
					url: "{$this->getOperationsUrl()}",
					data : {
						"operation" : "move_node",
						"id" : jQuery(this).attr("id").replace("node_",""),
						"ref" : data.rslt.np.attr("id").replace("node_",""),
						"position" : data.rslt.cp + i,
						"title" : data.rslt.name,
						"copy" : data.rslt.cy ? 1 : 0
					},
					success : function (r) {
						if(!r.status) {
							jQuery.jstree.rollback(data.rlbk);
						}
						else {
							jQuery(data.rslt.oc).attr("id", "node_" + r.id);
							if(data.rslt.cy && jQuery(data.rslt.oc).children("UL").length) {
								data.inst.refresh(data.inst._get_parent(data.rslt.oc));
							}
						}
						jQuery("#analyze").click();
					}
				});
			});
		});
EOF;

        return $output;
    }

}
