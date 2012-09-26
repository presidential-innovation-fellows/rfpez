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

	$contents = file_get_contents($view->path);

	// Create the MtHaml environment which will compiled our PHP Haml
	$haml = new MtHaml\Environment('php');

	$compiled = $haml->compileString($contents, $view->path);

	// Once we have compiled the file we will write out the compiled version to disk
	file_put_contents($path = path('storage').'views/'.md5($view->path), $compiled);

	$view->path = $path;

	// We overrode the view path with the compiled path, so we'll just render...
	return $view->get();
});