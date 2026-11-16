<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        /* creer un objet vide pour pouvoir y capturer les infos ou en mettre, a partir de l objet DTO qu on a construit */
        $data = new ContactDTO();

       

        $form = $this->createForm(ContactType::class, $data);
        $form -> handleRequest($request);
        if ($form-> isSubmitted() && $form-> isValid()){

            // envoie email
            $mail =(new TemplatedEmail())
                ->to('blanc.julia@gmail.com')
                ->from($data -> email)
                ->subject('')
                ->htmlTemplate('emails/contact.html.twig')
                -> context(['data' => $data]);
            $mailer ->send($mail);
            $this->addFlash('success','Email bien envoyé ');
            $this-> redirect('contact');
            }

        return $this->render('pages/contact.html.twig', [
           
            'form'=> $form
        ]);
    }
}
