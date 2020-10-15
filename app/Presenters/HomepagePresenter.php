<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Tracy\Debugger;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    public function createComponentForm()
    {
        $form = new Nette\Application\UI\Form();
        $form->addText('name')
            ->setHtmlAttribute('class', 'col-sm-12')
            ->setHtmlAttribute('placeholder', 'Jméno');
        $form->addEmail('email')
            ->setHtmlAttribute('class', 'col-sm-12 noMarr')
            ->setHtmlAttribute('placeholder', 'E-mail');
        $form->addTextArea('message')
            ->setHtmlAttribute('class', 'col-sm-12')
            ->setHtmlAttribute('placeholder', 'Zpráva...');
        $form->addSubmit('submit')
            ->setHtmlAttribute('class', 'submitBnt');
        $form->onSuccess[] = [$this, 'success'];
        return $form;
    }

    public function success(Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
        $mail = new Nette\Mail\Message;
        $mail->setFrom('GreatLook <noreply@greatlook.cz>')
            ->addTo('greatlookbio@gmail.com')
            ->setSubject('Nová zpráva z formuláře')
            ->setHtmlBody(
                "Od: " . $values->name . "<br> Email: " . $values->email . "<br> Zpráva: " . $values->message
            );
        $mailer = new Nette\Mail\SmtpMailer();
        $mailer->send($mail);

        $this->flashMessage('Formulář byl úspěšně odeslaný. Budeme Vás kontaktovat.', 'success');
        $this->redirect('this');
    }
}
