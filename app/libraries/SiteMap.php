<?php

use Blog\Post\Post;
use Blog\Post\PostAlgorithm;

class SiteMap {

    /**
     * @var SiteMap
     */
    protected static $instance;

    private function __construct(){}

    /**
     * Singleton instance
     *
     * @return SiteMap
     */
    public static function instance()
    {
        if(! static::$instance)

            static::$instance = new static;

        return static::$instance;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $string = '<h3>Main Pages</h3>';

        $string .= $this->generateUlTag($this->getMenuLinks());

        return $string;
    }

    /**
     * @param array $array
     * @return string
     */
    protected function generateUlTag( array $array )
    {
        $string = '<ul>';

        foreach($array as $link => $title)
        {
            $string .= '<li><a href="'.$link.'">'.$title.'</a></li>';
        }

        $string .= '</ul>';

        return $string;
    }

    /**
     * @return array
     */
    public function getMenuLinks()
    {
        return array(
            URL::route('home') => 'Home page',
        );
    }

}