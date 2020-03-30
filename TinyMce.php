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
use cmsgears\widgets\tinymce\assets\TinyMceAssets;

class TinyMce extends \cmsgears\core\common\base\Widget {

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

	//public $plugins	= 'image link code lists codesample fullscreen';
	//public $toolbar	= 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | image link code | codesample | fullscreen';
	public $plugins	= 'image link code lists codesample';
	public $toolbar	= 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | image link code | codesample';

	public $relativeUrl = false;
	public $scriptHost	= false;

	public $htmlInAnchor = true;

	public $verifiyHtml = false;

	public $validElements = [
		'*[*]'
	];

	public $validChildren = [
		'*[*]'
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
		$editorConfig[ 'verifiyHtml' ]		= isset( $editorConfig[ 'verifiyHtml' ] ) ? $editorConfig[ 'verifiyHtml' ] : $this->verifiyHtml;
		$editorConfig[ 'valid_elements' ]	= isset( $editorConfig[ 'valid_elements' ] ) ? $editorConfig[ 'valid_elements' ] : join( ',', $this->validElements );
		$editorConfig[ 'valid_children' ]	= isset( $editorConfig[ 'valid_children' ] ) ? $editorConfig[ 'valid_children' ] : join( ',', $this->validChildren );

		// Open Target
		$editorConfig[ 'allow_unsafe_link_target' ]	= isset( $editorConfig[ 'allow_unsafe_link_target' ] ) ? $editorConfig[ 'allow_unsafe_link_target' ] : $this->unsafeTargetLink;

        $editorConfigJson = json_encode( $editorConfig );

		// Add JS
		$editorJs = "jQuery( document ).ready( function() { tinymce.init( $editorConfigJson ); });
			//function tinymcesetup( editor ) { editor.on( 'change', function () { editor.save(); } ); } config.setup = tinymcesetup;
			function initCmtEditorBySelector( selector ) { var config = $editorConfigJson; config.selector = selector; tinymce.init( config ); }
			function initCmtEditorByElement( element ) { var config = $editorConfigJson; delete config.selector; jQuery( element ).tinymce( config ); }";

		// Call JS at end
		$this->getView()->registerJs( $editorJs, View::POS_END );
	}

}
