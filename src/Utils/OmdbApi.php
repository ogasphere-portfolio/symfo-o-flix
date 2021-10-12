<?php
namespace App\Utils;

use Psr\Log\LoggerInterface;

/**
 * Sert à communiquer avec l'API OMDB Api
 * 
 */
class OmdbApi {

    const IMAGE_NOT_FOUND = 'N/A';

    private $omdbApiKey;

    public function __construct($omdbApiKey) 
    {
        $this->omdbApiKey = $omdbApiKey;
    }

    /**
     * @param string $tvShowTitle
     * @return string
     */
    public function getImageUrlFromApi(string $tvShowTitle) :string {

        // se connecter à OMDBApi
        // il suffit de fournir une valeur d'apikey dans la requete

        // faire une recherche par titre
        $tvShowEncoded = urlencode($tvShowTitle);
        $requestUri = 'http://www.omdbapi.com/?apikey=' . $this->omdbApiKey . '&t=' . $tvShowEncoded;

        // TODO mettre l'api key en paramètre d'application
        $jsonInfo = file_get_contents($requestUri);
        // $jsonInfo = '{"Title":"Mr. Robot","Year":"2015–2019","Rated":"TV-MA","Released":"24 Jun 2015","Runtime":"49 min","Genre":"Crime, Drama, Thriller","Director":"N/A","Writer":"Sam Esmail","Actors":"Rami Malek, Christian Slater, Carly Chaikin","Plot":"Elliot, a brilliant but highly unstable young cyber-security engineer and vigilante hacker, becomes a key figure in a complex game of global dominance when he and his shadowy allies try to take down the corrupt corporation he works f","Language":"English, Swedish, Danish, Chinese, Persian, Spanish, Samoan, Arabic, German","Country":"United States","Awards":"Won 3 Primetime Emmys. 21 wins & 84 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BMzgxMmQxZjQtNDdmMC00MjRlLTk1MDEtZDcwNTdmOTg0YzA2XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"8.5/10"}],"Metascore":"N/A","imdbRating":"8.5","imdbVotes":"349,464","imdbID":"tt4158110","Type":"series","totalSeasons":"4","Response":"True"}';
        // attention json_decode créé un objet de la class StdObj
        $filmInfos = json_decode($jsonInfo);

        $imageUrl = self::IMAGE_NOT_FOUND;
        // si le film a été trouvé dans l'api
        if ($filmInfos->Response === "True") {
            // récupérer l'url de l'image
            $imageUrl = $filmInfos->Poster;
        }

        // renvoyer l'url
        return $imageUrl;
    }
}