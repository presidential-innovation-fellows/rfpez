<?php

class Base_Controller extends Controller {

	public $layout;

	public function __construct() {
		$this->layout = Request::header('x-pjax') ? 'pjaxcontainer' : 'layout';
		parent::__construct();
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}