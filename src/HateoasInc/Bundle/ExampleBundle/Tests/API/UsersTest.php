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
class UsersTest extends ApiTestCase
{
    const RESOURCE_PATH = '/api/v1/users';

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

    /**
     * @return \stdClass
     */
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

        return json_decode($transfer);
    }

    /**
     * @param \stdClass $doc
     * @depends testGettingMany200
     */
    public function testGettingOne200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $user = reset($doc->users);
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $user->id;
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
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
     * @depends testGettingOne200
     */
    public function testGettingBlacklistedRelation404(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/followers';
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
     * @depends testGettingOne200
     */
    public function testPutting200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $patternsGroup
            = self::$fixtures['social']->getReference('patterns-group');
        $coffeeGroup
            = self::$fixtures['social']->getReference('coffee-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id;
        $body = ['users' => [
            'id' => $doc->users->id,
            'name' => "Rudolph",
            'links' => [
                'user-groups' => [$coffeeGroup->getId()]
            ]
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
}
