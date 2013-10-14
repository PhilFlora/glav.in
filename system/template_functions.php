<?php

if ( !function_exists( 'pages_list' ) ) {

	/**
	 * Get unordered list nav list
	 *
	 * @param string optional id
         * @param string optional class_ul adds class name to <ul>
         * @param string optional class_li adds class name to <li>
         * @param string optional class_li_active adds class name to the current page <li>
	 * @return html list of all pages
	 */

	function pages_list( $id='', $class_ul='', $class_li='', $class_li_active='active' ) {
		global $page;
		
		// This function is here to provide a more
		// user-friendly way to get an unordered list
		// of the site's navigation.
		return $page->pages_list( $id, $class_ul, $class_li, $class_li_active );
	}
}