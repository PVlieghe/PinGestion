<?php

namespace App\Test\Controller;

use App\Entity\Piece;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PieceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/piece/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Piece::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Piece index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'piece[name]' => 'Testing',
            'piece[libelle]' => 'Testing',
            'piece[prix_vente]' => 'Testing',
            'piece[prix_achat]' => 'Testing',
            'piece[intermediaire]' => 'Testing',
            'piece[fabrique]' => 'Testing',
            'piece[stock]' => 'Testing',
            'piece[gamme]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Piece();
        $fixture->setName('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setPrix_vente('My Title');
        $fixture->setPrix_achat('My Title');
        $fixture->setIntermediaire('My Title');
        $fixture->setFabrique('My Title');
        $fixture->setStock('My Title');
        $fixture->setGamme('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Piece');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Piece();
        $fixture->setName('Value');
        $fixture->setLibelle('Value');
        $fixture->setPrix_vente('Value');
        $fixture->setPrix_achat('Value');
        $fixture->setIntermediaire('Value');
        $fixture->setFabrique('Value');
        $fixture->setStock('Value');
        $fixture->setGamme('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'piece[name]' => 'Something New',
            'piece[libelle]' => 'Something New',
            'piece[prix_vente]' => 'Something New',
            'piece[prix_achat]' => 'Something New',
            'piece[intermediaire]' => 'Something New',
            'piece[fabrique]' => 'Something New',
            'piece[stock]' => 'Something New',
            'piece[gamme]' => 'Something New',
        ]);

        self::assertResponseRedirects('/piece/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getPrix_vente());
        self::assertSame('Something New', $fixture[0]->getPrix_achat());
        self::assertSame('Something New', $fixture[0]->getIntermediaire());
        self::assertSame('Something New', $fixture[0]->getFabrique());
        self::assertSame('Something New', $fixture[0]->getStock());
        self::assertSame('Something New', $fixture[0]->getGamme());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Piece();
        $fixture->setName('Value');
        $fixture->setLibelle('Value');
        $fixture->setPrix_vente('Value');
        $fixture->setPrix_achat('Value');
        $fixture->setIntermediaire('Value');
        $fixture->setFabrique('Value');
        $fixture->setStock('Value');
        $fixture->setGamme('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/piece/');
        self::assertSame(0, $this->repository->count([]));
    }
}
