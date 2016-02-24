<?php

use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;

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
    public function send($to, $subject, $name, $params, $cc = array())
    {
        # Settings
        $mailSettings = $this->config->mail;

        $template = $this->getTemplate($name, $params);

        # Create the message
        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setCc($cc)
            ->setFrom(array(
                $mailSettings->fromEmail => $mailSettings->fromName
            ))
            ->setBody($template, 'text/html');

        if (isset($params['attachment']))
        {
            $message->attach(Swift_Attachment::fromPath($params['attachment']));
        }

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

    public function valid_email($address)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
    }
}
