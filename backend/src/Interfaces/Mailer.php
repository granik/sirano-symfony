<?php

namespace App\Interfaces;


use App\Domain\Entity\ClinicalAnalysis\Frontend\DTO\ClinicalAnalysisMessage;
use App\Domain\Interactor\User\User;
use App\Interactors\MailerInterface;
use App\Webinar\DTO\WebinarMessage;
use App\Webinar\DTO\WebinarRegistrationMessage;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class Mailer implements MailerInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $fromAddress;
    /**
     * @var string
     */
    private $from;
    
    /**
     * Mailer constructor.
     *
     * @param Swift_Mailer          $mailer
     * @param UrlGeneratorInterface $router
     * @param Environment           $twig
     * @param string                $fromAddress
     * @param string                $from
     */
    public function __construct(
        Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        Environment $twig,
        string $fromAddress,
        string $from
    ) {
        $this->mailer      = $mailer;
        $this->router      = $router;
        $this->twig        = $twig;
        $this->fromAddress = $fromAddress;
        $this->from        = $from;
    }
    
    public function sendConfirmationEmail(User $user)
    {
        $url = $this->router->generate('confirm', ['code' => $user->getActivationCode()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        $message = (new Swift_Message('Подтверждение регистрации'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($user->getLogin())
            ->setBody(<<<HTML
Здравствуйте!<br>
Для подтверждения регистрации на сайте Ассоциация врачей первичного звена пройдите по <a href="{$url}">ссылке</a>. <br>
Если Вы не выполняли никаких действий на указанном сайте, проигнорируйте данное письмо.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    public function sendRestoreUserPasswordEmail(User $user, string $password)
    {
        $message = (new Swift_Message('Восстановление пароля на портале Ассоциации СИРАНО'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($user->getLogin())
            ->setBody(<<<HTML
Ваш логин: {$user->getLogin()}<br>
Ваш новый пароль: $password<br>

Пожалуйста, не забудьте изменить пароль в личном кабинете на портале Ассоциации.<br>
Это письмо было сформировано автоматически, отвечать на него не нужно.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    public function sendWebinarMessage(WebinarMessage $webinarMessage)
    {
        $message = (new Swift_Message('Вопрос лектору по вебинару Сирано'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($webinarMessage->to)
            ->setBody(<<<HTML
Дата: {$webinarMessage->date}.
Название: {$webinarMessage->name}.
Тема: {$webinarMessage->subject}.
Направление: {$webinarMessage->direction}.
ФИО слушателя: {$webinarMessage->subscriberName}.
E-mail слушателя: {$webinarMessage->subscriberEmail}.
Сообщение: {$webinarMessage->text}
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    public function sendClinicalAnalysisMessage(ClinicalAnalysisMessage $clinicalAnalysisMessage)
    {
        $message = (new Swift_Message('Вопрос лектору по клиническому разбору Сирано'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($clinicalAnalysisMessage->to)
            ->setBody(<<<HTML
Название: {$clinicalAnalysisMessage->name}.
Направление: {$clinicalAnalysisMessage->direction}.
ФИО слушателя: {$clinicalAnalysisMessage->subscriberName}.
E-mail слушателя: {$clinicalAnalysisMessage->subscriberEmail}.
Сообщение: {$clinicalAnalysisMessage->text}.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    public function sendClinicalAnalysisCompanyMessage(ClinicalAnalysisMessage $clinicalAnalysisMessage)
    {
        $message = (new Swift_Message('Вопрос сотруднику компании по клиническому разбору Сирано'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($clinicalAnalysisMessage->to)
            ->setBody(<<<HTML
Название: {$clinicalAnalysisMessage->name}.
Направление: {$clinicalAnalysisMessage->direction}.
ФИО слушателя: {$clinicalAnalysisMessage->subscriberName}.
E-mail слушателя: {$clinicalAnalysisMessage->subscriberEmail}.
Сообщение: {$clinicalAnalysisMessage->text}.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    /**
     * @param string $email
     * @param string $code
     * @param int    $password
     */
    public function sendConfirmationFromFileEmail(string $email, string $code, int $password)
    {
        $url     = $this->router->generate('confirm', ['code' => $code], UrlGeneratorInterface::ABSOLUTE_URL);
        $message = (new Swift_Message('Подтверждение регистрации на портале Ассоциации СИРАНО'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($email)
            ->setBody(<<<HTML
Уважаемый коллега!<br>
Поскольку Вы были слушателем Образовательной программы для врачей первичного звена,
мы приглашаем Вас на Образовательный портал, созданный специально для врачей России.
Если Вам интересна работа с Образовательным порталом, пожалуйста,
перейдите по следующей <a href="{$url}">ссылке</a> и активируйте свой профиль с помощью логина $email и временного пароля $password,
который Вы сможете изменить в своем Личном кабинете.<br>
С уважением, Власова Надежда Леонидовна.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    /**
     * @param string $email
     * @param string $code
     * @param string $password
     */
    public function sendRepeatedConfirmationEmailFromFile(string $email, string $code, string $password)
    {
        $url     = $this->router->generate('confirm', ['code' => $code], UrlGeneratorInterface::ABSOLUTE_URL);
        $message = (new Swift_Message('Регистрация на Образовательном портале СИРАНО'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($email)
            ->setBody(<<<HTML
Уважаемый коллега!<br>
<p>Поскольку Вы были слушателем Образовательной программы Ассоциации СИРАНО мы приглашаем Вас на Образовательный портал,
созданный специально для врачей первичного звена.</p>
<p>Если Вам интересна работа с Образовательным порталом, пожалуйста, перейдите по следующей <a href="{$url}">ссылке</a>
и активируйте свой профиль с помощью логина "$email" и временного пароля "$password", который Вы сможете изменить в
своем Личном кабинете.</p>
С уважением, Власова Надежда Леонидовна.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    public function sendRepeatedConfirmationEmailFromSite(string $email, string $code, string $password)
    {
        $url     = $this->router->generate('confirm', ['code' => $code], UrlGeneratorInterface::ABSOLUTE_URL);
        $message = (new Swift_Message('Завершение регистрации на Образовательном портале СИРАНО'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($email)
            ->setBody(<<<HTML
Уважаемый коллега!<br>
<p>Вы зарегистрировались на Образовательном портале Ассоциации СИРАНО, созданном специально для врачей первичного звена,
но не активировали свой профиль.</p>
<p>Если Вам интересна работа с Образовательным порталом, пожалуйста, перейдите по следующей <a href="{$url}">ссылке</a>
и активируйте свой профиль с помощью логина "$email" и временного пароля "$password", который Вы сможете изменить в
своем Личном кабинете.</p>
С уважением, Власова Надежда Леонидовна.
HTML
                ,
                'text/html'
            );
        
        $this->mailer->send($message);
    }
    
    public function sendRegistrationForWebinarEmail(
        string $email,
        WebinarRegistrationMessage $webinarRegistrationMessage
    ) {
        $message = (new Swift_Message('Вы зарегистрированы на онлайн-трансляцию вебинара на Образовательном портале СИРАНО'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($email)
            ->setBody(
                $this->twig->render('emails/webinar.html.twig', ['webinarRegistrationMessage' => $webinarRegistrationMessage]),
                'text/html'
            );
        
        $ical = <<<VCAL
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:$webinarRegistrationMessage->link
DTSTAMP:$webinarRegistrationMessage->dtstamp
DTSTART:$webinarRegistrationMessage->dtstart
DTEND:$webinarRegistrationMessage->dtend
SUMMARY:$webinarRegistrationMessage->name
ORGANIZER;CN=Ассоциация врачей первичного звена СИРАНО:MAILTO:{$this->fromAddress}
END:VEVENT
END:VCALENDAR
VCAL;
        
        $attachment = new Swift_Attachment($ical, 'webinar.ics', 'text/calendar');
        
        $message->attach($attachment);
        
        $this->mailer->send($message);
    }
    
    public function sendRemindAboutWebinarEmail(string $email, WebinarRegistrationMessage $webinarRegistrationMessage)
    {
        $message = (new Swift_Message('Вы зарегистрированы на онлайн-трансляцию вебинара на Образовательном портале СИРАНО'))
            ->setFrom([$this->fromAddress => $this->from])
            ->setTo($email)
            ->setBody(
                $this->twig->render('emails/webinar_reminder.html.twig', ['webinarRegistrationMessage' => $webinarRegistrationMessage]),
                'text/html'
            );
        
        $ical = <<<VCAL
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:$webinarRegistrationMessage->link
DTSTAMP:$webinarRegistrationMessage->dtstamp
DTSTART:$webinarRegistrationMessage->dtstart
DTEND:$webinarRegistrationMessage->dtend
SUMMARY:$webinarRegistrationMessage->name
ORGANIZER;CN=Ассоциация врачей первичного звена СИРАНО:MAILTO:{$this->fromAddress}
END:VEVENT
END:VCALENDAR
VCAL;
        
        $attachment = new Swift_Attachment($ical, 'webinar.ics', 'text/calendar');
        
        $message->attach($attachment);
        
        $this->mailer->send($message);
    }
}