<?php

namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\UserAddSuccessEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class userSubscriber implements EventSubscriberInterface
{

   private MailerInterface $mailer; // Déclare une propriété pour le service MailerInterface

   public function __construct(MailerInterface $mailer) // Injecte le service MailerInterface dans le constructeur de la classe
   {
      $this->mailer = $mailer;
   }



   // Implémente la méthode statique getSubscribedEvents pour définir les événements auxquels ce subscriber s'abonne
   public static function getSubscribedEvents(): array
   {
      return [
         // Abonnez-vous et écoutez l'événement "UserAddSuccessEvent" et associez-le à la méthode "onUserAddSuccess" 
         "userAdd.success" => 'sendAddUserMail', //userAdd.success est le nom de l'événement que nous avons défini dans UserAddSuccessEvent, et sendAddUserMail est la méthode qui sera appelée lorsque cet événement sera déclenché
      ];
   }





   // Méthode qui sera appelée lorsque l'événement "UserAddSuccessEvent" est déclenché, elle reçoit une instance de UserAddSuccessEvent en paramètre.
   public function sendAddUserMail(UserAddSuccessEvent $event)
   {
      $user = $event->getUser(); // Récupère l'utilisateur à partir de l'événement

      // Utilisez le service Mailer pour envoyer un email de bienvenue à l'utilisateur
      $email = (new Email())
         ->from('no-reply@monsite.com')
         ->to($user->getEmail())
         ->subject('Nouvel utilisateur ajouté!')
         ->text('Bienvenue: ' . $user->getNom() . ' ' . $user->getPrenom() . '! Votre compte a été créé avec succès.');
      $this->mailer->send($email);
   }
}
