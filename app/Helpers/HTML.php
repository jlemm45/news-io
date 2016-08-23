<?php

namespace App\Helpers;


class HTML {

    /**
     * Strip out tags from html
     *
     * @param $description
     * @return mixed
     */
    static function stripTags($description) {
        return strip_tags($description,"<p>");
        return preg_replace('/<a\b[^>]*>(.*?)<\/a>/i', '', $description);
    }

}