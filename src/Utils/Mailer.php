<?php


namespace App\Utils;

use App\Entity\TempUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function registerMail(UserInterface $tempUser)
    {
        $url_confirm = 'account/mail_confirm.html.twig';

        $message = (new \Swift_Message('Win-Win : Email de confirmation!'))
            ->setFrom('eric.jamar@outlook.be')
            ->setTo($tempUser->getEmail())
            ->setBody($this->renderer->render($url_confirm, array(
                'tempUser' => $tempUser,
            )),'text/html');
        $this->mailer->send($message);

    }
}