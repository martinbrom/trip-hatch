<?php

namespace Core\Mail;

use Core\Config\Config;
use Core\Mail\Exception\InvalidEmailMessageException;
use Core\Mail\Exception\InvalidEmailRecipientException;
use Core\Mail\Exception\InvalidEmailSubjectException;
use Core\View;

class Mailer
{
    /** @var mixed */
    private $sender;

    /** @var View */
    private $view;

    /**
     * Mailer constructor.
     * @param Config $config
     * @param View $view
     */
    function __construct(Config $config, View $view) {
        $this->sender = $config->get('mail.sender');
        $this->view = $view;
    }

    /**
     * @param $recipient
     * @param $token
     */
    public function forgottenPassword($recipient, $token) {
        $message = $this->view->render('emails/forgottenPassword.html.twig', ['email' => $recipient, 'token' => $token]);
        $this->send($recipient, 'TripHatch password recovery', $message);
        // TODO: Localize
    }

    /**
     * @param $recipient
     * @param $token
     * @param $inviteMessage
     * @param $tripTitle
     */
    public function invite($recipient, $token, $inviteMessage, $tripTitle) {
        $data = [
            'token' => $token,
            'message' => $inviteMessage,
            'title' => $tripTitle
        ];
        $message = $this->view->render('emails/invite.html.twig', $data);
        $this->send($recipient, 'TripHatch trip invite', $message);
        // TODO: Localize
    }

    /**
     * @param $recipient
     * @param $subject
     * @param $message
     * @throws InvalidEmailMessageException
     * @throws InvalidEmailRecipientException
     * @throws InvalidEmailSubjectException
     */
    public function send($recipient, $subject, $message) {
        if ($recipient == NULL || !filter_var($recipient, FILTER_VALIDATE_EMAIL))
            throw new InvalidEmailRecipientException($recipient);

        if ($subject == NULL)
            throw new InvalidEmailSubjectException($subject);

        if ($message == NULL)
            throw new InvalidEmailMessageException($message);

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $this->sender;
        $headers[] = 'To: ' . $recipient;

        mail($recipient, $subject, $message, implode("\r\n", $headers));
    }
}
