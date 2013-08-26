<?php namespace Gallery\Version;

use Gallery\Image\Image;
use \Illuminate\Database\Query\Builder;
use DB;

class VersionAlgorithm extends \BaseAlgorithm {

    /**
     * @param Image $image
     * @return $this
     */
    public function byImage( Image $image )
    {
        $this->getQuery()->where('image_id', $image->id);

        return $this;
    }

    /**
     * @param  int $width
     * @param  int $height
     * @return $this
     */
    public function nearestDim( $width, $height )
    {
        $this->getQuery()->orderBy(DB::raw('ABS(width - ' .$width. ')'), 'ASC')
                         ->orderBy(DB::raw('ABS(height - '.$height.')'), 'ASC');
        return $this;
    }

    /**
     * @return $this
     */
    public function smallestDim()
    {
        $this->getQuery()->orderBy('width', 'ASC')->orderBy('height', 'ASC');

        return $this;
    }

    /**
     * @return $this
     */
    public function largestDim()
    {
        $this->getQuery()->orderBy('width', 'DESC')->orderBy('height', 'DESC');

        return $this;
    }

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Version::query();
    }
}