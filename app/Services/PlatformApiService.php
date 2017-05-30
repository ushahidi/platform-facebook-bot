<?php
namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Sainsburys\Guzzle\Oauth2\GrantType\RefreshToken;
use Sainsburys\Guzzle\Oauth2\GrantType\PasswordCredentials;
use Sainsburys\Guzzle\Oauth2\Middleware\OAuthMiddleware;

class PlatformApiService
{
    public function __construct($config)
    {
        $this->config = $config;
    }
  
    /* creates a guzzler-client to use when communicating with the platform-api */
    private function getClient()
    {
        $this->base_uri = $this->config['platform_base_uri'];
        $handlerStack = HandlerStack::create();
        $client = new Client(['handler'=> $handlerStack, 'base_uri' => $this->base_uri, 'auth' => 'oauth2']);
        $credentials = [
            PasswordCredentials::CONFIG_USERNAME => $this->config['platform_username'],
            PasswordCredentials::CONFIG_PASSWORD => $this->config['platform_password'],
            PasswordCredentials::CONFIG_CLIENT_ID => $this->config['platform_client_id'],
            PasswordCredentials::CONFIG_TOKEN_URL => $this->config['platform_token_url'],
            PasswordCredentials::CONFIG_CLIENT_SECRET => $this->config['platform_client_secret'],
            'scope' => 'posts media forms'
            ];
        $token = new PasswordCredentials($client, $credentials);
        $refreshToken = new RefreshToken($client, $credentials);
        $middleware = new OAuthMiddleware($client, $token, $refreshToken);
        $handlerStack->push($middleware->onBefore());
        $handlerStack->push($middleware->onFailure(5));
        return $client;
    }

    /* saves the post to the platform */
    public function savePost($post)
    {
        $platformClient = $this->getClient();
        $client = new Client();
        $data = json_decode($post);
        //first save the image
        try {
            // getting the image
            $image_response = $client->request('GET', $data->image);
            $image = $image_response->getBody()->getContents();
            // saving it to platform
            $media = $platformClient->request('POST','api/v3/media', [
                'multipart'=> [['name' => 'file', 'contents'=> $image, 'filename'=> 'sample.jpg']]
            ]);
            // getting image-id
            $mediaId = json_decode($media->getBody()->getContents())->id;
        }
        catch (ClientException $e) {
            // TODO: Send reply back to fb
            \Log::info('error when uploading file');
        }
        // then add the values to correct attribute-key
        // TODO: Fetch key-values from api instead of hardcoding them :D
        $data->values = [
            '2a83c84a-6e86-49c8-ae63-bf8c68878fac' => [
                $data->location],
            'dcdf4bc5-bfc0-432e-8525-750d6416fd8e'=>[$mediaId]
        ];
        $data->sources = 'Facebook';
        $data->form = ['id' => 26];

        // finally, save the post to the platform-api
        $header = ['Content-Type' => 'text/json'];
        try {
            $response = $platformClient->request('POST', '/api/v3/posts', [
                'headers' => $header,
                'json' => $data
                ]);
        } 
        catch (ClientException $e) {
            // TODO: Send reply back to fb
            \Log::info(print_r($e->getMessage(), true));
        }
    }

      /*fetches attributes from platform-api*/
    private function getAttributes()
    {
        $client = $this->getClient();
        $response = $client->get('/api/v3/forms/26/attributes');
        $contents = $response->getBody();
        $contents = json_decode($contents);
        $attributes = [];
        foreach ($contents->results as $content) {
            $attribute = [];
            $attribute[$content->label] = $content->label;
            $attribute['key'] = $content->key;
            array_push($attributes, $attribute);
        }
        return $attributes;
    }

}
