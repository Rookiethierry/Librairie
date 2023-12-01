<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Note;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\BookRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(BookRepository $bookRepository): Response
    {

        $books = $bookRepository->findAll();
        return $this->render('front/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/{id}', name: 'app_book')]
    public function showBook(Book $book, Request $request, EntityManagerInterface $em, NoteRepository $noteRepository){

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request); // On récupère les données du formulaire

        $oldNote = $noteRepository->findOneBy(['book' => $book, 'user' => $this->getUser()]);


        $notes = $noteRepository->findBy(['book' => $book]);
        $noteMoyenne = 0;

        foreach ($notes as $key => $note) {
            $noteMoyenne += $note->getStarNumber();
        }
        $noteMoyenne = $noteMoyenne / count($notes);


        if($form->isSubmitted() && $form->isValid()){
            //
            if(!$this->getUser()){
                $this->addFlash('danger', 'Vous devez être connecté pour ajouter un commentaire');
                return $this->redirectToRoute('app_book', ['id' => $book->getId()]);
            }
            //
            $comment->setUser($this->getUser());
            $comment->setBook($book);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');
            return $this->redirectToRoute('app_book', ['id' => $book->getId()]);
        }


        return $this->render('front/book.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
            'note' => $oldNote ? $oldNote->getStarNumber() : 0,
            'noteMoyenne' => $noteMoyenne
        ]);
    }

    #[Route('/rank/book', name: 'app_rank')]
    public function rank(Request $request, EntityManagerInterface $em, BookRepository $bookRepository, NoteRepository $noteRepository){

        $newNote = $request->query->get('note');
        $bookId = $request->query->get('bookId');
        $book = $bookRepository->find($bookId);

        $note = $noteRepository->findOneBy(['book' => $book, 'user' => $this->getUser()]);
        if(!$note){
            $note = new Note();
        }

        $note->setStarNumber($newNote);
        $note->setBook($book);
        $note->setUser($this->getUser());



        
        $em->persist($note);
        $em->flush();

        $notes = $noteRepository->findBy(['book' => $book]);
        $noteMoyenne = 0;

        foreach ($notes as $key => $note) {
            $noteMoyenne += $note->getStarNumber();
        }
        $noteMoyenne = $noteMoyenne / count($notes);

        return new JsonResponse(['success' => true, 'note' => $noteMoyenne]);

    }
}
