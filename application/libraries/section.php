<?php

class Section extends Laravel\Section {

	/**
	 * Get the string contents of a section.
	 *
	 * @param  string  $section
	 * @return string
	 */
	public static function yield_jade($section)
	{
		return with(new Jade\Jade)->render(static::yield($section));
	}

}