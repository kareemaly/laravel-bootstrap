<?php namespace Gallery\Gallery;

use \Illuminate\Database\Query\Builder;

class GalleryAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Gallery::query();
    }
}