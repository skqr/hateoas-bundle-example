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
    public function testPuttingOne200(\stdClass $doc)
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
            'username' => "red_nose",
            'email' => "reindeer_samurai84@christmastown.org",
            'name' => "Rudolph",
            'surname' => "Reindeer",
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
        $expected = [
          'links' => [
            'users.user-groups' => [
              'href' => '/api/v1/users/{users.id}/links/user-groups',
              'type' => 'user-groups',
            ],
          ],
          'users' => [
            'id' => $doc->users->id,
            'type' => 'users',
            'roles' => ['ROLE_USER'],
            'username' => 'red_nose',
            'email' => 'reindeer_samurai84@christmastown.org',
            'name' => 'Rudolph',
            'surname' => 'Reindeer',
            'links' => ['user-groups' => [(string) $coffeeGroup->getId()]]
          ]
        ];
        $data = json_decode($transfer, TRUE);
        $this->assertEquals($expected, $data);

        return json_decode($transfer);
    }

    /**
     * @param \stdClass $doc
     * @depends testPuttingOne200
     */
    public function testDeletingGroupRelation204(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $patternsGroup
            = self::$fixtures['social']->getReference('patterns-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/user-groups';
        $client = $this->buildHttpClient($url, 'red_nose', 'cl34rt3xt')
            ->setMethod('DELETE');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNoContent($client, $message);

        return $doc;
    }

    /**
     * @param \stdClass $doc
     * @depends testDeletingGroupRelation204
     */
    public function testUpdatingGroupRelation204(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $patternsGroup
            = self::$fixtures['social']->getReference('patterns-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/user-groups';
        $body = ['user-groups' => [(string) $patternsGroup->getId()]];
        $client = $this->buildHttpClient($url, 'red_nose', 'cl34rt3xt')
            ->setMethod('PUT')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNoContent($client, $message);

        return $doc;
    }

    /**
     * @param \stdClass $doc
     * @depends testUpdatingGroupRelation204
     */
    public function testAddingGroupRelation204(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $coffeeGroup
            = self::$fixtures['social']->getReference('coffee-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/user-groups';
        $body = ['user-groups' => [(string) $coffeeGroup->getId()]];
        $client = $this->buildHttpClient($url, 'red_nose', 'cl34rt3xt')
            ->setMethod('POST')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNoContent($client, $message);

        return $doc;
    }

    /**
     * @param \stdClass $doc
     * @depends testAddingGroupRelation204
     */
    public function testAddingGroupRelation409(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $coffeeGroup
            = self::$fixtures['social']->getReference('coffee-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/user-groups';
        $body = ['user-groups' => [(string) $coffeeGroup->getId()]];
        $client = $this->buildHttpClient($url, 'red_nose', 'cl34rt3xt')
            ->setMethod('POST')
            ->setBody($body);
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseConflict($client, $message);
    }

    /**
     * @param \stdClass $doc
     * @depends testAddingGroupRelation204
     */
    public function testDeletingFromGroupRelation204(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $patternsGroup
            = self::$fixtures['social']->getReference('patterns-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/user-groups'
            . '/' . $patternsGroup->getId();
        $client = $this->buildHttpClient($url, 'red_nose', 'cl34rt3xt')
            ->setMethod('DELETE');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNoContent($client, $message);

        return $doc;
    }

    /**
     * @param \stdClass $doc
     * @depends testDeletingFromGroupRelation204
     */
    public function testDeletingGroupRelation404(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $patternsGroup
            = self::$fixtures['social']->getReference('patterns-group');
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $doc->users->id
            . '/links/user-groups'
            . '/' . $patternsGroup->getId();
        $client = $this->buildHttpClient($url, 'red_nose', 'cl34rt3xt')
            ->setMethod('DELETE');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseNotFound($client, $message);
    }
}
