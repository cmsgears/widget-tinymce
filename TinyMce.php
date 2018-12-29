<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\widgets\tinymce;

// Yii Imports
use yii\web\View;

// CMG Imports
use cmsgears\core\common\base\Widget;

use cmsgears\widgets\tinymce\assets\TinyMceAssets;

class TinyMce extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	/**
	 * The css selector to be passed to the editor js library.
	 */
	public $selector;

	public $config = [];

	// TinyMCE Options

	public $menubar		= false;
	public $statusbar	= false;
	public $schema		= 'html5';
	public $height		= 250;

	public $plugins	= 'code lists';
	public $toolbar	= 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code';

	public $relativeUrl = false;
	public $scriptHost	= false;

	public $htmlInAnchor = true;

	public $validElements = [
		'@[id|class|title|style]',
		'a[name|href|target|title|alt]',
		'#p',
		'-ol', '-ul', '-li',
		'img[src|unselectable]',
		'-sub', '-sup',
		'-b', '-i', '-u', 'br', 'hr',
		'-span[data-mce-type]'
	];

	public $validChildren = [
		'body[p,ol,ul]',
		'p[a|span|b|i|u|sup|sub|img|hr|#text]',
		'span[a|b|i|u|sup|sub|img|#text]',
		'+a[div|p|ul|ol|li|h1|h2|h3|h4|h5|h5|h6]',
		'b[span|a|i|u|sup|sub|img|#text]',
		'i[span|a|b|u|sup|sub|img|#text]',
		'sup[span|a|i|b|u|sub|img|#text]',
		'sub[span|a|i|b|u|sup|img|#text]',
		'li[span|a|b|i|u|sup|sub|img|ol|ul|#text]',
		'ol[li]',
		'ul[li]'
	];

	public $unsafeTargetLink = false;

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

		if( $this->loadAssets ) {

			TinyMceAssets::register( $this->getView() );
		}

		return $this->renderWidget();
    }

	public function renderWidget( $config = [] ) {

		$editorConfig = $this->config;

		// Clear CLEditor Config
		unset( $editorConfig[ 'controls' ] );
		unset( $editorConfig[ 'fonts' ] );

		// Configure TinyMCE
		$editorConfig[ 'selector' ] = 'textarea' . $this->selector;

		$editorConfig[ 'menubar' ]		= isset( $editorConfig[ 'menubar' ] ) ? $editorConfig[ 'menubar' ] : $this->menubar;
		$editorConfig[ 'statusbar' ]	= isset( $editorConfig[ 'statusbar' ] ) ? $editorConfig[ 'statusbar' ] : $this->statusbar;

      	$editorConfig[ 'schema' ] = isset( $editorConfig[ 'schema' ] ) ? $editorConfig[ 'schema' ] : $this->schema;
		$editorConfig[ 'height' ] = isset( $editorConfig[ 'height' ] ) ? $editorConfig[ 'height' ] : $this->height;

      	$editorConfig[ 'plugins' ]	= isset( $editorConfig[ 'plugins' ] ) ? $editorConfig[ 'plugins' ] : $this->plugins;
		$editorConfig[ 'toolbar' ]	= isset( $editorConfig[ 'toolbar' ] ) ? $editorConfig[ 'toolbar' ] : $this->toolbar;

      	$editorConfig[ 'relative_urls' ] 		= isset( $editorConfig[ 'relative_urls' ] ) ? $editorConfig[ 'relative_urls' ] : $this->relativeUrl;
      	$editorConfig[ 'remove_script_host' ] 	= isset( $editorConfig[ 'remove_script_host' ] ) ? $editorConfig[ 'remove_script_host' ] : $this->scriptHost;

      	$editorConfig[ 'allow_html_in_named_anchor' ] = isset( $editorConfig[ 'allow_html_in_named_anchor' ] ) ? $editorConfig[ 'allow_html_in_named_anchor' ] : $this->htmlInAnchor;

		// Fix Elements
		$editorConfig[ 'valid_elements' ] = isset( $editorConfig[ 'valid_elements' ] ) ? $editorConfig[ 'valid_elements' ] : join( ',', $this->validElements );
		$editorConfig[ 'valid_children' ] = isset( $editorConfig[ 'valid_children' ] ) ? $editorConfig[ 'valid_children' ] : join( ',', $this->validChildren );

		// Open Target
		$editorConfig[ 'allow_unsafe_link_target' ]	= isset( $editorConfig[ 'allow_unsafe_link_target' ] ) ? $editorConfig[ 'allow_unsafe_link_target' ] : $this->unsafeTargetLink;

        $editorConfigJson = json_encode( $editorConfig );

		// Add JS
		$editorJs = "tinymce.init( $editorConfigJson );";

		// Call JS at end
		$this->getView()->registerJs( $editorJs, View::POS_READY );
	}

}
