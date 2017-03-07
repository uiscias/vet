<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reminder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->getDoctrine()->getManager();

        $reminders = $em->getRepository('AppBundle:Reminder')->findAll();

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
}
