<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('show_400')) {
	function show_400() {
        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_400();
        exit;
    }
}

if ( ! function_exists('show_401')) {
	function show_401() {
        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_401();
        exit;
    }
}

if ( ! function_exists('show_403')) {
	function show_403() {
        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_403();
        exit;
    }
}

if ( ! function_exists('show_404_page')) {
	function show_404_page() {
        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_404_page();
        exit;
    }
}
	
if ( ! function_exists('show_410')) {	
    function show_410($heading = '' , $message = '', $reason = '', $action = '', $status_code = 410) {
        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_410($heading, $message, $reason, $action, $status_code = 410);
        exit;
    }
}
	
/* end of exception_helper */