<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\AddMessageType;
use App\Repository\MessagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(MessagesRepository $messagesRepository, Request $request)
    {
        $message = new Messages();
        $form = $this->createForm(AddMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager(); // Получаем EntityManager для сохранения данных
            $manager->persist($message); // получаем новую сущность для сохранения в БД
            $manager->flush();  // "Сливаем" изменения в бд


            return $this->redirectToRoute('messages');
        }

        $messages = $messagesRepository->findAll();
        return $this->render('messages/index.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }

}
