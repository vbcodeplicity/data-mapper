<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Profiler\Ticker\ProfilerTickerElasticsearch;
use G4\ValueObject\Url;

class ElasticsearchClient
{
    const DOCUMENT      = 'doc';
    const SEARCH        = '_search';
    const TIMEOUT       = 5;
    const METHOD_GET    = 'GET';
    const UPDATE        = '_update';

    private $index;

    /**
     * @var Url
     */
    private $url;

    private $body;

    private $method;

    private $id;

    private $response;

    /**
     * @var ProfilerTickerElasticsearch
     */
    private $profiler;

    public function __construct(Url $url)
    {
        $this->url = $url;
        $this->profiler = ProfilerTickerElasticsearch::getInstance();
    }

    public function execute()
    {
        $this->url = $this->url->path($this->index, self::DOCUMENT, $this->id);

        $this->executeCurlRequest();
    }

    public function search()
    {
        $this->url = $this->url->path($this->index, self::SEARCH);

        $this->method = self::METHOD_GET;

        $this->executeCurlRequest();

        return $this;
    }

    public function update()
    {
        $this->url = $this->url->path($this->index, self::DOCUMENT, $this->id, self::UPDATE);

        $this->executeCurlRequest();
    }

    public function setIndex($value)
    {
        $this->index = $value;
        return $this;
    }

    public function setMethod($value)
    {
        $this->method = $value;
        return $this;
    }

    public function setBody($value)
    {
        $this->body = $value;
        return $this;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getResponse()
    {
        return $this->getDecodedResponse()['hits'];
    }

    public function getTotalItemsCount()
    {
        return $this->getResponse()['total'];
    }

    private function executeCurlRequest()
    {
        $uniqueId = $this->profiler->start();
        $handle = curl_init((string) $this->url);

        $postBody = json_encode($this->body);

        curl_setopt_array($handle, [
            CURLOPT_POSTFIELDS     => $postBody,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_URL            => (string) $this->url,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST  => $this->method,
        ]);

        $this->response = curl_exec($handle);

        $this->profiler->setInfo(
            $uniqueId,
            curl_getinfo($handle),
            $this->method,
            $postBody
        );

        curl_close($handle);
        $this->profiler->end($uniqueId);

        return $this;
    }

    private function getDecodedResponse()
    {
        return json_decode($this->response, true);
    }
}
