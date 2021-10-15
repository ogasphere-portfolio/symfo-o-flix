<?php 

namespace App\Utils;

class Slugger {

    /**
     * slugify the given string 
     *
     * @param string $toBeSlug
     * @return string
     */
    public function makeSlug(string $toBeSlug){

        $slug = strtolower($toBeSlug);
        // dump($slug);
        // h (saison 1)
        $slug = preg_replace('/[^a-z0-9- ]/', '', $slug);
        // dump($slug);

        // h saison 1
        $slug = str_replace(" ", "-", $slug);
        

        return $slug;
    }
}