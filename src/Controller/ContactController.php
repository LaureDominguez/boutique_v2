<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use App\Form\ContactType;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    private ?CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    private function checkCart()
    {
        $user = $this->getUser();
        if ($user !== null) {
            $checkCart = $this->cartRepository->findBy(['user' => $this->getUser()]);
            if (!empty($checkCart)) {
                return true;
            }
        }
        return false;
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        MailerInterface $mailer
    ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /// Saving in the Database
            $contact = $form->getData();
            $manager->persist($contact);
            $manager->flush();
            /// Sending an email
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('dominguezlaure@gmail.com')
                ->subject($contact->getSubject())
                ->text($contact->getMessage())
                ->htmlTemplate("email/contact.html.twig")
                /// Pass variable to the twig template
                ->Context([
                    "contact" => $contact
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a  bien été envoyé merci !');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'display_cart' => $this->checkCart(),
        ]);
    }
}
