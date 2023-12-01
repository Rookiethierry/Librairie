<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $book = new Book();
        $book->setTitle('Le seigneur des anneaux');

        $manager->persist($book);

        $book = new Book();
        $book->setTitle('Harry Potter');

        $manager->persist($book);

        $book = new Book();
        $book->setTitle('Le petit prince');

        $manager->persist($book);

        $book = new Book();
        $book->setTitle('Le rouge et le noir');

        $manager->persist($book);

        $book = new Book();
        $book->setTitle('Le père Goriot');

        $manager->persist($book);




        $manager->flush();
    }
}
