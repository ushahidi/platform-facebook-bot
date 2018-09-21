<?php
namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
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
        $client = new Client(['handler' => $handlerStack, 'base_uri' => $this->base_uri, 'auth' => 'oauth2']);
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
    public function savePost($ongoingReport)
    {
        $platformClient = $this->getClient();
        $client = new Client();
        $data = json_decode($ongoingReport->replies);
        //first save the image
        try {
            // getting the image
            if(isset($data->image)) {
                    $image_response = $client->request('GET', $data->image);   
                    $image = $image_response->getBody()->getContents();
                // saving it to platform
                $media = $platformClient->request('POST','api/v3/media', [
                    'multipart'=> [['name' => 'file', 'contents'=> $image, 'filename'=> 'sample.png']]
                ]);
                // getting image-id
                $mediaId = json_decode($media->getBody()->getContents())->id;
            }
        } catch (ClientException $e) {
            \Log::ERROR('error when uploading file: ' . $e->getMessage());
        }

        // assigning values to attribute-keys
        $data->values = [];
        $attributes = $ongoingReport->attributes;
        foreach(json_decode($attributes) as $attribute) {

            if($attribute->label === 'location') {
                if(!empty($data->location) && !empty($data->location->lat) && !empty($data->location->lon)) {
                    $data->values[$attribute->key] = [$data->location];    
                } else {
                    $data->location = null;
                }
            } else if($attribute->label === 'image' && isset($mediaId)) {
                $data->values[$attribute->key]=[$mediaId];
            } else if(!empty($attribute->default)) {
                $data->values[$attribute->key] = [$attribute->default];
            }
        }

        $data->title = substr($data->content, 0, 15) . '...';
        $data->form = ['id' => env('PLATFORM_FORM_ID')];
        // finally, save the post to the platform-api
        $header = ['Content-Type' => 'text/json'];
        try {
        
            $response = $platformClient->request('POST', '/api/v3/posts', [
                'headers' => $header,
                'json' => $data
                ]);
            // sucessful submission of post    
            $command = 'submit';
        } catch (ClientException $e) {
            // unsuccessful submission of post
            $command = 'platform error';
            \Log::ERROR('could not send message to platform: ' . $e->getMessage());
        }
        // returning command to be used to find answer in database
        return $command;
    }

    /*fetches attributes from platform-api*/
    public function getAttributes($formId)
    {
        $client = $this->getClient();
        try {
            $response = $client->get('/api/v3/forms/' . $formId .'/attributes');
            $contents = $response->getBody();
            $contents = json_decode($contents);
            $attributes = [];
            foreach ($contents->results as $content) {
                
                // here, add default values.
                $attribute = [];
                
                if($content->type === 'media') {
                    $attribute['label'] = 'image';
                    $attribute['key'] = $content->key;
                    
                } else if($content->type === 'point') {
                    $attribute['label'] = 'location';
                    $attribute['key'] = $content->key;
                    $attribute['default'] = $content->default;
                } else if($content->type === 'title' || $content->type === 'description') {
                    $attribute['label'] = $content->label;
                    $attribute['key'] = $content->key;
                } else if($content->default) {
                    $attribute['label'] = $content->label;
                    $attribute['key'] = $content->key;
                }
                
                if(count($attribute) > 0) {
                    $attribute['default'] = $content->default;
                    array_push($attributes, $attribute);
                }
            }
        } catch (ClientException $e) {
            $attributes = [];
            \Log::ERROR('error when fetching attributes:' . $e->getMessage());
        } 
        return $attributes;
    }

}
