<?php
/**
 * @copyright 2014 Integ S.A.
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @author Javier Lorenzana <javier.lorenzana@gointegro.com>
 */

namespace HateoasInc\Bundle\ExampleBundle\DataFixtures\ORM;

// ORM.
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager,
    Doctrine\Common\Collections\ArrayCollection;
// Entities.
use HateoasInc\Bundle\ExampleBundle\Entity;
// DI.
use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

class SocialDataFixture
    extends AbstractFixture
    implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $patternsGroup = new Entity\UserGroup;
        $patternsGroup->setName("Design Pattern Abusers Anonymous");
        $manager->persist($patternsGroup);
        $this->addReference('patterns-group', $patternsGroup);

        $coffeeGroup = new Entity\UserGroup;
        $coffeeGroup->setName("Public Coffee Lovers");
        $manager->persist($coffeeGroup);
        $this->addReference('coffee-group', $coffeeGroup);

        $soapGroup = new Entity\UserGroup;
        $soapGroup->setName("The SOAP's Advocates");
        $manager->persist($soapGroup);
        $this->addReference('soap-group', $soapGroup);

        $thisUser = new Entity\User;
        $thisUser->setUsername("this_guy");
        $thisUser->setEmail("this.guy@gmail.com");
        $thisUser->setPassword("cl34rt3xt");
        $thisUser->addUserGroup($patternsGroup);
        $manager->persist($thisUser);
        $this->addReference('player-1', $thisUser);

        $otherUser = new Entity\User;
        $otherUser->setUsername("the_other_guy");
        $otherUser->setEmail("the.other.guy@gmail.com");
        $otherUser->setPassword("b4dp4ssw0rd");
        $otherUser->addUserGroup($coffeeGroup);
        $this->addReference('player-2', $otherUser);
        $manager->persist($otherUser);

        $post = new Entity\Post;
        $post->setContent("Check this bundle out. #RockedMyWorld");
        $post->setOwner($thisUser);
        $manager->persist($post);

        $comment = new Entity\Comment;
        $comment->setContent("Mine too. #RockedMyWorld");
        $comment->setOwner($otherUser);
        $comment->setSubject($post);
        $manager->persist($comment);

        $manager->flush();
    }
}
