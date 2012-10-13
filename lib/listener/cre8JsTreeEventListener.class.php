<?php

/**
 * Sample implementation
 *
 */
class cre8JsTreeEventListener {

    /**
     *
     * @param sfEvent $event
     * @return string
     *
     * @see http://www.jstree.com/documentation/types
     */
    public static function treeTypesSetup(sfEvent $event) {
        $output = <<<EOF
    					/*"default" : {
						// I want this type to have no children (so only leaf nodes)
						// In my case - those are files
						"valid_children" : "none",
						// If we specify an icon for the default type it WILL OVERRIDE the theme icons
						"icon" : {
							"image" : "/cre8LanguageSwitcherPlugin/images/flags/20x12/de.png"
						}
					},*/
					"folder" : {
						// can have files and other folders inside of it, but NOT `drive` nodes
						"valid_children" : [ "default", "folder" ],
						"icon" : {
							"image" : "/cre8LanguageSwitcherPlugin/images/flags/20x12/pl.png"
						}
					},
					"drive" : {
						// can have files and folders inside, but NOT other `drive` nodes
						"valid_children" : [ "default", "folder" ],
						"icon" : {
							"image" : "/cre8LanguageSwitcherPlugin/images/flags/20x12/en.png"
						},
						// those options prevent the functions with the same name to be used on the `drive` type nodes
						// internally the `before` event is used
						"start_drag" : false,
						"move_node" : false,
						"delete_node" : false,
						"remove" : false
					}
EOF;
        $event->setReturnValue($output);
        return $output;
    }

    /**
     *
     * @param sfEvent $event
     * @return string
     *
     * @see http://www.jstree.com/documentation/contextmenu
     */
    public static function treeContextmenuSetup(sfEvent $event) {
        $output = <<<EOF
        "zzz" : {
	// The item label
	"label"				: "RenameX",
	// The function to execute upon a click
	"action"			: function (obj) { this.rename(obj); },
	// All below are optional
	"_disabled"			: false,		// clicking the item won't do a thing
	"_class"			: "class",	// class is applied to the item LI node
	"separator_before"	: false,	// Insert a separator before the item
	"separator_after"	: true,		// Insert a separator after the item
	// false or string - if does not contain `/` - used as classname
	"icon"				: false,
	"submenu"			: {
		/* Collection of objects (the same structure) */
	}
}
EOF;
        $event->setReturnValue($output);
        return $output;
    }

}
