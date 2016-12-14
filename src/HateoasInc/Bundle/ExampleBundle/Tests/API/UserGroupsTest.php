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
class UserGroupsTest extends ApiTestCase
{
    const RESOURCE_PATH = '/api/v1/user-groups';

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
        // $this->assertJsonApiSchema($transfer, $message);

        return json_decode($transfer);
    }

    /**
     * @param \stdClass $doc
     * @depends testGettingMany200
     */
    public function testGettingOne200(\stdClass $doc)
    {
        /* Given... (Fixture) */
        $user = reset($doc->{'user-groups'});
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '/' . $user->id;
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        // $this->assertJsonApiSchema($transfer, $message);

        return json_decode($transfer);
    }

    /**
     * @return \stdClass
     */
    public function testGettingFirstPage200()
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '?page=1'
            . '&size=2';
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        // $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer, TRUE);
        $this->assertSame(
            [
                'page' => 1,
                'size' => 2,
                'total' => 3
            ],
            $data['meta']['user-groups']['pagination']
        );
    }

    /**
     * @return \stdClass
     */
    public function testGettingSecondPage200()
    {
        /* Given... (Fixture) */
        $url = $this->getRootUrl() . self::RESOURCE_PATH
            . '?page=2'
            . '&size=2';
        $client = $this->buildHttpClient($url, 'this_guy', 'cl34rt3xt');
        /* When... (Action) */
        $transfer = $client->exec();
        /* Then... (Assertions) */
        $message = $transfer . "\n";
        $this->assertResponseOK($client, $message);
        // $this->assertJsonApiSchema($transfer, $message);
        $data = json_decode($transfer, TRUE);
        $this->assertSame(
            [
                'page' => 2,
                'size' => 2,
                'total' => 3
            ],
            $data['meta']['user-groups']['pagination']
        );
    }
}
