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
        private function getAttributes() {
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
        private function getClient() {
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
        
        public function savePost($post){

    }
}