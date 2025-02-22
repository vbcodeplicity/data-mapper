<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchGeodistFormatter;

class ElasticsearchGeodistFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchIdentity
     */
    private $identity;

    protected function setUp()
    {
        $this->identity = new ElasticsearchIdentity();
    }

    public function testFormatWithGeodistParameters()
    {
        $this->identity->geodist(5, 10, 100);

        $geodistFormatter = new ElasticsearchGeodistFormatter($this->identity);

        $expectedArray = [
            'geo_distance' => [
                'distance'     => '100km',
                'location' => [
                    'lon' => '10',
                    'lat' => '5',
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $geodistFormatter->format());
    }

    public function testFormatWithEmptyGeodistParameters()
    {
        $geodistFormatter = new ElasticsearchGeodistFormatter($this->identity);

        $this->assertEquals([], $geodistFormatter->format());
    }

    public function testFormatWithGeodistMinParameters()
    {
        $this->identity->geodist(5, 10, 100, 20);

        $geodistFormatter = new ElasticsearchGeodistFormatter($this->identity);

        $expectedArray = [
            'geo_distance' => [
                'distance'     => '100km',
                'location' => [
                    'lon' => '10',
                    'lat' => '5',
                ],
            ],
        ];

        $expectedArrayMin = [
            'geo_distance' => [
                'distance'     => '20km',
                'location' => [
                    'lon' => '10',
                    'lat' => '5',
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $geodistFormatter->format());
        $this->assertEquals($expectedArrayMin, $geodistFormatter->formatMin());
    }

    public function testFormatWithEmptyGeodistMinParameters()
    {
        $geodistFormatter = new ElasticsearchGeodistFormatter($this->identity);

        $this->assertEquals([], $geodistFormatter->formatMin());
    }
}
