<?php

namespace JiraClient\Client;

use GuzzleHttp\Client,
    JiraClient\Credential,
    JiraClient\Response,
    JiraClient\Exception\JiraException;

/**
 * Description of GuzzleClient
 *
 * @author rastor
 */
class GuzzleClient
{

    /**
     *
     * @var Client
     */
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client();
    }

    public function sendRequest($method, $url, $data, Credential $credential)
    {
        $request = $this->guzzle->createRequest($method, $url, array(
            'auth' => [$credential->getLogin(), $credential->getPassword()],
            'json' => $data
        ));

        try {
            $response = $this->guzzle->send($request);
        } catch (\Exception $e) {
            throw new JiraException("Request failed!", $e);
        }

        //file_put_contents('a', $response->getBody()->getContents());
        
        $responseData = json_decode($response->getBody()->getContents(), true);

        return new Response($responseData, $response->getStatusCode());
    }

}
