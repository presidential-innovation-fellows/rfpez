<?php

Event::listen(View::loader, function($bundle, $view)
{
	// This event just makes the View class think that ".jade" files are valid...
	$path = Bundle::path($bundle).'views/'.$view.'.jade';

	if (file_exists($path)) return $path;
});


Event::listen(View::engine, function($view)
{
	if ( ! str_contains(File::extension($view->path), 'jade'))
	{
		return $view->get();
	}

	$contents = $view->get();

	$jade = new Jade\Jade;

	return $jade->render($contents);
});