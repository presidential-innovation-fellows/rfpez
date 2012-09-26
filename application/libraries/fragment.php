<?php

class Fragment {

	/**
	 * Get the contents of a fragment cache.
	 *
	 * @param  string   $key
	 * @param  int      $minutes
	 * @param  Closure  $callback
	 */
	public static function cache($key, $minutes, Closure $callback)
	{
		return Cache::remember($key, function() use ($callback)
		{
			return Fragment::execute($callback);

		}, $minutes);
	}

	/**
	 * Get the contents of a fragment cache.
	 *
	 * @param  string   $key
	 * @param  Closure  $callback
	 */
	public static function sear($key, Closure $callback)
	{
		return Cache::sear($key, function() use ($callback)
		{
			return Fragment::execute($callback);
		});
	}

	/**
	 * Execute a fragment Closure.
	 *
	 * @param  Closure  $callback
	 * @return string
	 */
	public static function execute(Closure $callback)
	{
		ob_start();

		call_user_func($callback);

		return ob_get_clean();
	}

}