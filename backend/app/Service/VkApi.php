<?php

namespace VkMusic\Service;
use Curl\Curl;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class VkApi
{
    private $url;

    private $apiId;

    private $viewerId;

    private $version = '5.8';

    private $format = 'json';

    private $accessToken;

    public function send(string $method, array $data) {
        $curl = new Curl();

        $data = array_merge([
            'api_id' => $this->apiId,
            'method' => $method,
//            'v' => $this->version,
            'format' => $this->format,
        ], $data);

//        $data['sig'] = $this->generateSig($data);

        $curl->post($this->url, $data);

        return json_decode($curl->response, true);
    }

    public function sendOpen(string $method, array $data) {
        $curl = new Curl();

        if (isset($this->accessToken)) {
            $data['access_token'] = $this->accessToken;
        }

        $curl->post("https://api.vk.com/method/{$method}", $data);

        return json_decode($curl->response, true);
    }

    public function generateSig(array $data) {
        ksort($data);

        $items = array_map(function ($value, $key) {
            return "{$key}={$value}";
        }, $data, array_keys($data));

        array_unshift($items, $this->viewerId);
        array_push($items, $this->secret);

        $message = implode('', $items);

        return md5($message);
    }

    /**
     * @return mixed
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * @param mixed $apiId
     * @return $this
     */
    public function setApiId($apiId)
    {
        $this->apiId = $apiId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViewerId()
    {
        return $this->viewerId;
    }

    /**
     * @param mixed $viewerId
     * @return $this
     */
    public function setViewerId($viewerId)
    {
        $this->viewerId = $viewerId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}