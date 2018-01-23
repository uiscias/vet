<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Client;
use AppBundle\Entity\Consultation;
use AppBundle\Entity\Reminder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Reminder controller.
 *
 * @Route("reminder")
 */
class ReminderController extends Controller
{
    /**
     * Lists all reminder entities.
     *
     * @Route("/", name="reminder_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }

        $em = $this->getDoctrine()->getManager();

        $reminders = $em->getRepository('AppBundle:Reminder')->findAll();
        $rem = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($reminders as $reminder){
            if ($reminder->getClient()->getAssociatedUsername() == $user){
                $rem->add($reminder);
            }
        }
        $reminders = $rem;

        return $this->render('reminder/index.html.twig', array(
            'reminders' => $reminders,
        ));

    }

    /**
     * Creates a new reminder entity.
     *
     * @Route("/new", name="reminder_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reminder = new Reminder();
        $form = $this->createForm('AppBundle\Form\ReminderType', $reminder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reminder);
            $em->flush($reminder);

            return $this->redirectToRoute('reminder_show', array('id' => $reminder->getId()));
        }

        return $this->render('reminder/new.html.twig', array(
            'reminder' => $reminder,
            'form' => $form->createView(),
        ));
    }

    /**
     * Removes a reminder entity (Vaccin) using AjaxCall.
     *
     * @Route("/removeReminderVaccinByAjax", name="reminder_vaccin_remove_ajax")
     * @Method({"POST"})
     */
    public function removeReminderVaccinByAjaxAction(Request $request)
    {
        //This is optional. Do not do this check if you want to call the same action using a regular request.
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $data = json_decode($request->getContent(), true);
        /*
         *** Javascript edit.html extract ***
            var obj = {
                animal: idAnimal,
                client: {{ consultation.client.id }},
                consultation: {{ consultation.id }}
            };
         *
         */

        // Retrieves a repository managed by the "customer" em
        $reminder = $this->getDoctrine()
            ->getRepository(Reminder::class)
            ->find($data['reminder'])
        ;

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($reminder);
        $em->flush($reminder);

        $response = new JsonResponse(
            array(
                'success' => 'true'
            ));

        return $response;

    }

    /**
S     * Creates a new reminder entity (Vaccin) using AjaxCall.
     *
     * @Route("/newReminderVaccinByAjax", name="reminder_vaccin_new_ajax")
     * @Method({"POST"})
     */
    public function newVaccinByAjaxAction(Request $request)
    {
        //This is optional. Do not do this check if you want to call the same action using a regular request.
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $data = json_decode($request->getContent(), true);
        /*
         *** Javascript edit.html extract ***
            var obj = {
                animal: idAnimal,
                client: {{ consultation.client.id }},
                consultation: {{ consultation.id }}
            };
         *
         */

        // Retrieves a repository managed by the "customer" em
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($data['client'])
        ;
        $animal = $this->getDoctrine()
            ->getRepository(Animal::class)
            ->find($data['animal'])
        ;
        $consultation = $this->getDoctrine()
            ->getRepository(Consultation::class)
            ->find($data['consultation'])
        ;
        //TODO: Add no consultation handle case (add a message asking user to first save the consultation

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser();
        }

/*        $repository = $this->getDoctrine()
            ->getRepository('Reminder');
        $query = $repository->createQueryBuilder('c')
            ->where('c.associatedUsername = :username')
            ->andWhere('c.firstName LIKE :searchElem OR c.lastName LIKE :searchElem OR c.phone LIKE :searchElem OR c.phone2 LIKE :searchElem OR c.eMail LIKE :searchElem')
            ->setParameter('username', $user)
            ->setParameter('searchElem', '%'.$clientSearch.'%')
            ->orderBy('c.lastConsultation', 'DESC')
            ->setMaxResults(250)
            ->getQuery();

        $clients = $query->getResult();
*/


        $reminder = new Reminder();
        $reminder->setClient($client);
        $reminder->setAnimal($animal);
        $reminder->setConsultation($consultation);
        $consultation->addReminder($reminder);


        $title = 'Rappel vaccination ';
        $title .= $animal->nameToString();
        $reminder->setTitle($title);

        $reminderDate = $consultation->getDateOfConsultation();
        $reminderDate = $reminderDate->add(new \DateInterval('P'.$user->getUserSettings()->getReminderInterval().'D'));

        $reminder->setReminderDateTime($reminderDate);
        $reminder->setMedia('ALL');
        $reminder->setEnabled(true);
        $reminder->setSent(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reminder);
        $em->flush($reminder);

        $response = new JsonResponse(
            array(
                'reminder' => $reminder->getId(),
                'success' => 'true'
            ));

        return $response;
    }

    /**
     * Finds and displays a reminder entity.
     *
     * @Route("/{id}", name="reminder_show")
     * @Method("GET")
     */
    public function showAction(Reminder $reminder)
    {
        $deleteForm = $this->createDeleteForm($reminder);

        return $this->render('reminder/show.html.twig', array(
            'reminder' => $reminder,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reminder entity.
     *
     * @Route("/{id}/edit", name="reminder_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Reminder $reminder)
    {
        $deleteForm = $this->createDeleteForm($reminder);
        $editForm = $this->createForm('AppBundle\Form\ReminderType', $reminder);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reminder_edit', array('id' => $reminder->getId()));
        }

        return $this->render('reminder/edit.html.twig', array(
            'reminder' => $reminder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reminder entity.
     *
     * @Route("/{id}", name="reminder_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reminder $reminder)
    {
        $form = $this->createDeleteForm($reminder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reminder);
            $em->flush($reminder);
        }

        return $this->redirectToRoute('reminder_index');
    }

    /**
     * Creates a form to delete a reminder entity.
     *
     * @param Reminder $reminder The reminder entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reminder $reminder)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reminder_delete', array('id' => $reminder->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Send reminders of the day.
     *
     * @Route("/send")
     */
    public function sendAction()
    {

        return new Response('<html><body>Reminder page!</body></html>');

    }

}
