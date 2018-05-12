<?php


namespace App\Controller;


use Aws\Credentials\Credentials;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
//use AWS\Translate\TranslateClient;

/**
 * @Route("/translate")
 */
class TranslationController extends Controller
{

    /**
     * @Route("/{term}", name="translate", options={"expose"=true}, methods={"GET"})
     */
    public function translate(string $term)
    {
        $config = [
            'region'  => 'us-east-2',
            'version' => 'latest',
            'credentials' => new Credentials(
                getenv('AWS_TRANSLATE_KEY_ID'),
                getenv('AWS_TRANSLATE_ACCESS_KEY')
            )
        ];

        $translate = new \Aws\Translate\TranslateClient($config);
        $result = $translate->translateText([
            'SourceLanguageCode' => 'fr', // REQUIRED
            'TargetLanguageCode' => 'en', // REQUIRED
            'Text' => $term, // REQUIRED
        ]);

        return new JsonResponse(
            [
                [
                    'translatedText' => $result['TranslatedText'],
                    'language' => 'en',
                    'source' => 'aws'
                ]
            ]
        );
    }
}