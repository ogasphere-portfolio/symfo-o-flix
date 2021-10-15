<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TvShowControllerTest extends WebTestCase
{
    public function testHomepageNotConnected(): void
    {
        // on crée un client http
        $client = static::createClient();

        // qui fait une requete sur une route de notre application
        // et renvoit le résultat dans un un objet qui permet de parcourir le html recu
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a.btn', 'Se connecter');

        // on peut également cliquer sur des liens 
        // https://symfony.com/doc/current/testing.html#clicking-on-links

        // ou soumettre des formulaires 
        // https://symfony.com/doc/current/testing.html#submitting-forms


        // $crawler = $client->request('GET', '/backoffice/user');
        // tester qu'il y a eu une redirection
        // tester qu'on arrive bien sur la page de login

    }


    public function testHomepageConnectedAdmin(): void
    {
        // on crée un client http
        $client = static::createClient();

        // on a besoin du repository pour chercher l'utilisateur dans la BDD
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        // 
        // $adminUser = $userRepository->findOneByEmail('admin@oflix.com');
        $adminUser = $userRepository->findOneBy(['email' => 'toto@toto.fr']);

        // on simule l'authentification de l'utilisateur
        $client->loginUser($adminUser);

        // on se connecte à la page d'accueil
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('a.btn.btn-primary', 'BackOffice');
    }
}
