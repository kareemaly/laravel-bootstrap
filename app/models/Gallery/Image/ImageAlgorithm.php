<?php namespace Gallery\Image;

use \Illuminate\Database\Query\Builder;

class ImageAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Image::query();
    }
}