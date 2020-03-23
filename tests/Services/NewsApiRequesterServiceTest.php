<?php


namespace App\Tests\Services;


use App\Services\NewsApiRequesterService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CurlHttpClient;

class NewsApiRequesterServiceTest extends TestCase
{
    public function testRequestReturnsValidJson()
    {

        $newsApiRequester = new NewsApiRequesterService(new CurlHttpClient());
        $responseString = $newsApiRequester->request();
        $responseJson = json_decode($responseString);

        $this->assertEquals('ok', $responseJson->status);
        $this->assertGreaterThan(0, $responseJson->totalResults);
        $this->assertGreaterThan(0, count($responseJson->articles));

        $firstArticle = $responseJson->articles[0];
        $this->assertObjectHasAttribute('source', $firstArticle);
        $this->assertObjectHasAttribute('author', $firstArticle);
        $this->assertObjectHasAttribute('title', $firstArticle);
        $this->assertObjectHasAttribute('description', $firstArticle);
        $this->assertObjectHasAttribute('url', $firstArticle);
        $this->assertObjectHasAttribute('urlToImage', $firstArticle);
        $this->assertObjectHasAttribute('publishedAt', $firstArticle);
        $this->assertObjectHasAttribute('content', $firstArticle);
    }
}