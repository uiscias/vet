<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\Reminder;
use AppBundle\Entity\Animal;
use AppBundle\Entity\Consultation;
use AppBundle\Entity\ClientSearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $response = $this->forward('AppBundle:Client:index', array());

        // ... further modify the response or return it directly

        return $response;

        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', [
        //    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //]);
    }

    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }


    /**
     * Send reminders of the day.
     *
     * @Route("/sendReminders")
     */
    public function sendRemindersAction()
    {
        $today = new \DateTime("now");
        $today0 = new \DateTime($today->format("Y-m-d"));
        $today23 = new \DateTime($today->format("Y-m-d"));

        $today0->setTime(0,0,0);
        $today23->setTime(23,59,59);

        $repository = $this->getDoctrine()->getRepository('AppBundle\Entity\Reminder');
        $query = $repository->createQueryBuilder('r')
            ->where('r.reminderDateTime >= :today_startdatetime')
            ->andWhere('r.reminderDateTime  <= :today_enddatetime')
            ->andWhere('r.enabled = 1')
            ->andWhere('r.sent = 0')
            ->setParameter('today_startdatetime', $today0)
            ->setParameter('today_enddatetime', $today23)
            ->getQuery();

        $reminders = $query->getResult();

        foreach ($reminders as $reminder){
            // Retrieves a repository managed by the "customer" em
            $client = $this->getDoctrine()
                ->getRepository(Client::class)
                ->find($reminder->getClient()->getId())
            ;
            $animal = $this->getDoctrine()
                ->getRepository(Animal::class)
                ->find($reminder->getAnimal()->getId())
            ;

            switch ($reminder->getMedia()){
                case 'SMS':

                    break;
                case 'PHONE':

                    break;
                case 'MAIL':

                    break;
                case 'ALL':
                    // 'rappel.dr.thielens@gmail.com'
                    $user = $this->getDoctrine()->getRepository('AppBundle\Entity\User')->findByUsername($client->getAssociatedUsername());
                    $mailFrom = $user->getUserSettings();
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('rappel.dr.thielens@gmail.com')
                        ->setTo($client->getEMail())
                        ->setSubject('Rappel de vaccin pour '.$animal->getName())
                        ->setCharset('iso-8859-2')
                        ->setBody(
                            $this->renderView(
                            // app/Resources/views/Emails/reminder_vaccin.html.twig
                                'Emails/reminder_vaccin.html.twig',
                                array('client' => $client->getFirstName().' '.$client->getLastName(),
                                        'animal' => $animal->getName())

                            ),
                            'text/html'
                        );

                    $this->get('mailer')->send($message);
                    $reminder->setSent(true);
                    $this->getDoctrine()->getManager()->flush();

                    //->attach(Swift_Attachment::fromPath('my-document.pdf'))


                    break;
                default:
                    break;
            }

            echo($reminder->getId()."<br>");

        }

        return new Response('<html><body>Reminder Batch !</body></html>');


    }
}
