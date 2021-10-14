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

        // on met en minuscule
        // on remplace les ' ' par des '-'
        $slug = str_replace(" ", "-", strtolower($toBeSlug));


        return $slug;
    }
}