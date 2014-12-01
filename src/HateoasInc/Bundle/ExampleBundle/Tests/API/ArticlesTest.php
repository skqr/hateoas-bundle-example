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
class ArticlesTest extends ApiTestCase
{
    const RESOURCE_PATH = '/api/v1/articles';

    /**
     * @var array
     */
    private static $fixtures;

    /**
     * Obtiene los fixtures de este test case.
     * @return array <FixtureInterface>
     */
    protected static function getFixtures()
    {
        self::$fixtures = ['social' => new SocialDataFixture];

        return self::$fixtures;
    }

    public function testGettingOneInEnglish200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'en'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "This is my standing on stuff",
            $data->articles->title
        );

        return $data;
    }

    public function testGettingOneInFrench200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'fr'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "Ce est ma position sur la substance",
            $data->articles->title
        );
    }

    public function testGettingOneInItalian200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'it'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "Questa è la mia posizione su roba",
            $data->articles->title
        );
    }

    public function testGettingOneInUnknownLang200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'es'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "This is my standing on stuff",
            $data->articles->title
        );
    }

    /**
     * @param \stdClass $doc
     * @depends testGettingOneInEnglish200
     */
    public function testPuttingOneInEnglish200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->articles->id;
        $body = ['articles' => [
            'id' => $doc->articles->id, 'title' => "No it's not"
        ]];
        $client = $this->buildHttpClient(
                $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'en'
            )
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

    public function testGettingOneInEnglishAgain200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'en'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "No it's not",
            $data->articles->title
        );

        return $data;
    }

    public function testGettingOneInFrenchAgain200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'fr'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "Ce est ma position sur la substance",
            $data->articles->title
        );
    }

    public function testGettingOneInItalianAgain200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'it'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "Questa è la mia posizione su roba",
            $data->articles->title
        );
    }

    public function testGettingOneInUnknownLangAgain200()
    {
        /* Given... (Fixture) */
        $someArticle
            = self::$fixtures['social']->getReference('some-article');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $someArticle->getId();
        $client = $this->buildHttpClient(
            $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'es'
        );
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "No it's not",
            $data->articles->title
        );

        return $data;
    }

    /**
     * @param \stdClass $doc
     * @depends testGettingOneInUnknownLangAgain200
     */
    public function testPuttingOneInItalian200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->articles->id;
        $body = ['articles' => [
            'id' => $doc->articles->id, 'title' => "Pipiripupiri"
        ]];
        $client = $this->buildHttpClient(
                $url, 'this_guy', 'cl34rt3xt', static::CONTENT_JSON_API, 'it'
            )
            ->setMethod('PUT')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer);
        $this->assertEquals(
            "Pipiripupiri",
            $data->articles->title
        );

        return json_decode($transfer);
    }
}
