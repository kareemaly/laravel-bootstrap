<?php

class SiteMapController extends BaseController {

    /**
     * Printing xml sitemap
     */
    public function xml()
    {
        header("Content-type: text/xml");

        echo'<?xml version=\'1.0\' encoding=\'UTF-8\'?>';
        echo'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        echo '<url>';
        echo '<loc>'. URL::route('home') .'</loc>';
        echo '<changefreq>always</changefreq>';
        echo '</url>';

        echo '</urlset>';
    }

}