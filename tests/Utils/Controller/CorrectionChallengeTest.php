<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CorrectionChallengeTest extends WebTestCase
{
    public function testPublicAccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/tvshow/');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/tvshow/mr-robot');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testRestrictedAccessAsUser(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'user@oflix.com']);

        $client->loginUser($testUser);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        // on vérifie que le span qui est dans la nav (sélecteur nav span) contient le pseudo du user
        $this->assertSelectorTextContains('nav span.text-white', '( ' . $testUser->getPseudo() . ' )');

    }

    public function testBackofficeAccess(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'user@oflix.com']);
        $adminUser = $userRepository->findOneBy(['email' => 'admin@oflix.com']);

        $client->request('GET', '/backoffice/character/');
        $this->assertResponseRedirects();
        $this->assertResponseStatusCodeSame(302);

        $client->loginUser($testUser);

        $client->request('GET', '/backoffice/character/');
        $this->assertResponseStatusCodeSame(403);

        $client->loginUser($adminUser);

        $client->request('GET', '/backoffice/character/');
        $this->assertResponseIsSuccessful();

    }

    public function testCategoryForm(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['email' => 'admin@oflix.com']);

        $client->loginUser($adminUser);

        $client->request('GET', '/backoffice/category/add');

        $client->submitForm('Create', [
            'category[name]' => 'Test Category'
        ]);
        $this->assertResponseRedirects();
    }
}
