<?php
/**
 * @copyright 2014 Integ S.A.
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @author Javier Lorenzana <javier.lorenzana@gointegro.com>
 */

namespace HateoasInc\Bundle\ExampleBundle\Tests\API;

// Testing.
use GoIntegro\Bundle\HateoasBundle\Test\PHPUnit\ApiTestCase;
// Fixtures.
use HateoasInc\Bundle\ExampleBundle\DataFixtures\ORM\SocialDataFixture;

/**
 * Tests the functionality implemented in the class.
 *
 */
class PostsTest extends ApiTestCase
{
    const RESOURCE_PATH = '/api/v1/posts';

    /**
     * Obtiene los fixtures de este test case.
     * @return array <FixtureInterface>
     */
    protected static function getFixtures()
    {
        return [new SocialDataFixture];
    }

    public function testPosting201()
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH;
        $body = ['posts' => ['content' => "This is quite a post."]];
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt')
            ->setMethod('POST')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseCreated($client, $message);
        $this->assertJsonApiSchema($transfer, $message);

        return json_decode($transfer);
    }

    public function testPostingMany201()
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH;
        $body = [
            'posts' => [
                ['content' => "A post."],
                ['content' => "Another post."],
                ['content' => "Yet another post."],
                ['content' => "OK, stop."],
                ['content' => "Hammer time."]
            ]
        ];
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt')
            ->setMethod('POST')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseCreated($client, $message);
        $this->assertJsonApiSchema($transfer, $message);

        return json_decode($transfer);
    }

    public function testGettingMany200()
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH;
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testPosting201
     */
    public function testGettingOne200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id;
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testPosting201
     */
    public function testGettingContentField200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id
            . '/content';
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertEquals(
            "\"{$doc->posts->content}\"", $transfer, $message
        );
    }

    /**
     * @param \stdClass $doc
     * @depends testPosting201
     */
    public function testGettingUnknownField404(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id
            . '/unknown';
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNotFound($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testPosting201
     */
    public function testGettingOwnerRelation200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id
            . '/links/owner';
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testPosting201
     */
    public function testGettingUnknownRelation404(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id
            . '/links/unknown';
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNotFound($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testPosting201
     */
    public function testPutting200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id;
        $body = ['posts' => [
            'id' => $doc->posts->id, 'content' => "No it's not."
        ]];
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt')
            ->setMethod('PUT')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);

        return json_decode($transfer);
    }

    /**
     * @param \stdClass $doc
     * @depends testPostingMany201
     */
    public function testPuttingMany200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $ids = [];
        $body = ['posts' => []];

        foreach ($doc->posts as $post) {
            $ids[] = $post->id;
            $body['posts'][] = [
                'id' => $post->id,
                'content' => $post->content . " This wasn't here before."
            ];
        }

        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . implode(',', $ids);
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt')
            ->setMethod('PUT')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);

        return json_decode($transfer);
    }

    /**
     * @param \stdClass $doc
     * @depends testPutting200
     */
    public function testDeleting204(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->posts->id;
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt')
            ->setMethod('DELETE');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNoContent($client, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testPuttingMany200
     */
    public function testDeletingMany204(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $ids = array_map(function($post) { return $post->id; }, $doc->posts);
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . implode(',', $ids);
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt')
            ->setMethod('DELETE');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNoContent($client, $message);
    }
}
