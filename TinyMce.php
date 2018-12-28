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

		$editorConfig[ 'menubar' ]		= isset( $editorConfig[ 'menubar' ] ) ? $editorConfig[ 'menubar' ] : false;
		$editorConfig[ 'statusbar' ]	= isset( $editorConfig[ 'statusbar' ] ) ? $editorConfig[ 'statusbar' ] : false;

      	$editorConfig[ 'schema' ] = isset( $editorConfig[ 'schema' ] ) ? $editorConfig[ 'schema' ] : 'html5';
		$editorConfig[ 'height' ] = isset( $editorConfig[ 'height' ] ) ? $editorConfig[ 'height' ] : 220;

      	$editorConfig[ 'plugins' ]	= isset( $editorConfig[ 'plugins' ] ) ? $editorConfig[ 'plugins' ] : 'code lists';
		$editorConfig[ 'toolbar' ]	= isset( $editorConfig[ 'toolbar' ] ) ? $editorConfig[ 'toolbar' ] : 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code';

      	$editorConfig[ 'relative_urls' ] 		= isset( $editorConfig[ 'relative_urls' ] ) ? $editorConfig[ 'relative_urls' ] : false;
      	$editorConfig[ 'remove_script_host' ] 	= isset( $editorConfig[ 'remove_script_host' ] ) ? $editorConfig[ 'remove_script_host' ] : false;

      	$editorConfig[ 'allow_html_in_named_anchor' ] = isset( $editorConfig[ 'allow_html_in_named_anchor' ] ) ? $editorConfig[ 'allow_html_in_named_anchor' ] : true;

        $editorConfigJson = json_encode( $editorConfig );

		// Add JS
		$editorJs = "tinymce.init( $editorConfigJson );";

		// Call JS at end
		$this->getView()->registerJs( $editorJs, View::POS_READY );
	}

}
