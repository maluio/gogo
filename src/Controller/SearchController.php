<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    const MAX_NUMBER_OF_IMAGES = 10;

    /**
     * @Route("/images/{term}", name="search_images", options={"expose"=true}, methods={"GET"})
     */
    public function searchImages(string $term)
    {

        $result = json_decode($this->bingImageSearch($term));

        $images = [];

        foreach ($result->value as $img) {
            if (false !== strpos($img->contentUrl, 'https')) {
                $image['url'] = $img->thumbnailUrl;
                $image['url_original'] = $img->contentUrl;
                $image['url_thumbnail'] = $img->thumbnailUrl;
                $images[] = $image;
            }

            if (count($images) === self::MAX_NUMBER_OF_IMAGES) {
                break;
            }
        }
        return new JsonResponse($images);
    }

    private function bingImageSearch($query)
    {
        // Prepare HTTP request
        // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
        // http://php.net/manual/en/function.stream-context-create.php
        $headers = "Ocp-Apim-Subscription-Key: " . getenv('IMAGE_SEARCH_API_KEY') . "\r\n";
        $options = array(
            'http' => array(
                'header' => $headers,
                'method' => 'GET'
            )
        );

        $context = stream_context_create($options);
        return file_get_contents(getenv('IMAGE_SEARCH_ENDPOINT') . "?q=" . urlencode($query), false, $context);

    }

    /**
     * @Route("/phrases/{term}", name="search_phrases", options={"expose"=true}, methods={"GET"})
     */
    public function searchPhrases($term){

        $phrases = [];

        $news = json_decode($this->bingNewsSearch($term), true);

        foreach ($news['value'] as $n){
            $phrase = [
                'content' => $n['name'],
                'language' => 'fr',
                'url_source' => $n['url']
            ];
            $phrases[] = $phrase;
        }

        return new JsonResponse($phrases);
    }

    function bingNewsSearch ($term) {
        // Prepare HTTP request
        // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
        // http://php.net/manual/en/function.stream-context-create.php
        $headers = "Ocp-Apim-Subscription-Key:" . getenv('NEWS_SEARCH_API_KEY') ."\r\n";
        $options = array ('http' => array (
            'header' => $headers,
            'method' => 'GET' ));

        // Perform the Web request and get the JSON response
        $context = stream_context_create($options);
        $result = file_get_contents(getenv('NEWS_SEARCH_ENDPOINT') . "?cc=fr&q=" . urlencode($term), false, $context);

        return $result;
    }
}