<?php

Event::listen(View::loader, function($bundle, $view)
{
	// This event just makes the View class think that ".haml" files are valid...
	$path = Bundle::path($bundle).'views/'.$view.'.haml';

	if (file_exists($path)) return $path;
});


Event::listen(View::engine, function($view)
{
	if ( ! str_contains(File::extension($view->path), 'haml'))
	{
		return $view->get();
	}

	$contents = $view->get();

	// Create the MtHaml environment which will compiled our PHP Haml
	$haml = new MtHaml\Environment('php');

	return $haml->compileString($contents, $view->path);
});