<?php

use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;

require_once __DIR__ . "/../../vendor/Swift/swift_required.php";

/**
 *
 * Send email based on pre-defined templates
 */
class Mail extends Component
{

    protected $_transport;

    /**
     * Applies a template to be used in the email
     *
     * @param string $name
     * @param array $params
     */
    public function getTemplate($name, $params)
    {
        $parameters = array_merge(array(
            'publicUrl' => $this->config->application->publicUrl,
            'imageUrl' => $this->config->application->publicUrl . '/img/email/'
        ), $params);

        return $this->view->getRender('emailTemplates', $name, $parameters, function($view){
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }

    /**
     * Send email via gmail based on predefined templates
     *
     * @param array $to
     * @param string $subject
     * @param string $name
     * @param array $params
     */
    public function send($to, $subject, $name, $params)
    {
        # Settings
        $mailSettings = $this->config->mail;

        $template = $this->getTemplate($name, $params);

        # Create the message
        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom(array(
                $mailSettings->fromEmail => $mailSettings->fromName
            ))
            ->setBody($template, 'text/html');

        if (!$this->_transport) {
            $this->_transport = Swift_SmtpTransport::newInstance(
                $mailSettings->smtp->server,
                $mailSettings->smtp->port,
                $mailSettings->smtp->security
            )
            ->setUsername($mailSettings->smtp->username)
            ->setPassword($mailSettings->smtp->password);
        }

        # Create the Mailer using created Transport
        $mailer = Swift_Mailer::newInstance($this->_transport);

        return $mailer->send($message);
    }

}
