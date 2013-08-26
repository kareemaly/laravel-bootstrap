<?php namespace Website\Content;

use \Illuminate\Database\Query\Builder;

class ContentAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Content::query();
    }
}