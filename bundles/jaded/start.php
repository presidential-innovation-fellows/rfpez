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

	// First pass through the PHP layer...
	$contents = $view->get();

	$jade = new Jade\Jade;

	// Store the rendered Jade content...
	$path = path('storage').'views/'.md5($view->view);

	file_put_contents($path, $jade->render($contents));

	$view->path = $path;

	// Render the final Jaded output...
	return $view->get();
});