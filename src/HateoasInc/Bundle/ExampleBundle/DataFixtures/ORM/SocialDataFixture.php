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

        $thisUser = new Entity\User("this_guy");
        $thisUser->setPassword("cl34rt3xt");
        $thisUser->addUserGroup($patternsGroup);
        $manager->persist($thisUser);
        $this->addReference('player-1', $thisUser);

        $otherUser = new Entity\User("the_other_guy");
        $otherUser->setPassword("b4dp4ssw0rd");
        $otherUser->addUserGroup($coffeeGroup);
        $this->addReference('player-2', $otherUser);
        $manager->persist($otherUser);

        $post = new Entity\Post("Check this bundle out. #RockedMyWorld");
        $post->setOwner($thisUser);
        $manager->persist($post);

        $someArticle = new Entity\Article;
        // Will be used as translation for the default Symfony locale.
        $someArticle->setTitle("This is my standing on stuff");
        $someArticle->setContent(
            "Here's me, standing on stuff. E.g. a carrot."
        );
        // The pun is utterly lost.
        $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation')
            ->translate(
                $someArticle, 'title', 'it', 'Questa è la mia posizione su roba')
            ->translate($someArticle, 'content', 'it', 'Qui sono io, in piedi su roba. E.g. una carota.')
            ->translate($someArticle, 'title', 'fr', 'Ce est ma position sur la substance')
            ->translate($someArticle, 'content', 'fr', 'Ici est moi, debout sur des trucs. Par exemple une carotte.');
        $someArticle->setOwner($thisUser);
        $manager->persist($someArticle);
        $this->addReference('some-article', $someArticle);

        $otherArticle = new Entity\Article;
        // Will be used as translation for the default Symfony locale.
        $otherArticle->setTitle("This is my standing on stuff");
        $otherArticle->setContent(
            "Here's me, standing on stuff. E.g. a carrot."
        );
        // The pun is utterly lost.
        $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation')
            ->translate(
                $otherArticle, 'title', 'it', 'Questa è la mia posizione su roba')
            ->translate($otherArticle, 'content', 'it', 'Qui sono io, in piedi su roba. E.g. una carota.')
            ->translate($otherArticle, 'title', 'fr', 'Ce est ma position sur la substance')
            ->translate($otherArticle, 'content', 'fr', 'Ici est moi, debout sur des trucs. Par exemple une carotte.');
        $otherArticle->setOwner($thisUser);
        $manager->persist($otherArticle);
        $this->addReference('some-other-article', $otherArticle);

        $comment = new Entity\Comment($post, "Mine too. #RockedMyWorld");
        $comment->setOwner($otherUser);
        $manager->persist($comment);

        $manager->flush();
    }
}
