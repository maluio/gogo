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
     * @Route("/{term}", name="search", options={"expose"=true}, methods={"GET"})
     */
    public function search(string $term)
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

    public function bingImageSearch($query)
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
}