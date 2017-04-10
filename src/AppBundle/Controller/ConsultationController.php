<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Consultation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Consultation controller.
 *
 * @Route("consultation")
 */
class ConsultationController extends Controller
{
    /**
     * Lists all consultation entities.
     *
     * @Route("/", name="consultation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $consultations = $em->getRepository('AppBundle:Consultation')->findAll();

        return $this->render('consultation/index.html.twig', array(
            'consultations' => $consultations,
        ));
    }

    /**
     * Creates a new consultation entity.
     *
     * @Route("/{client}/new", name="consultation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Client $client)
    {

        $consultation = new Consultation();
        $consultation->setClient($client);
//        $form = $this->createForm('AppBundle\Form\ConsultationType', $consultation);
        $form = $this->get('form.factory')->create(ConsultationType::class, $consultation);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            dump($consultation);
            $em = $this->getDoctrine()->getManager();
            $consultation->setClient($client);
            $em->persist($consultation);
            $em->flush();

            $request->getSession()->getFlashBag()->add('oprationEmail', " votre email a été bien envoyé");

            return $this->redirectToRoute('consultation_show', array('id' => $consultation->getId()));
        }



/*            $em = $this->getDoctrine()->getManager();
            $em->persist($consultation);
            $em->flush($consultation);

            return $this->redirectToRoute('consultation_show', array('id' => $consultation->getId()));

        }
*/
        return $this->render('consultation/new.html.twig', array(
            'consultation' => $consultation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a consultation entity.
     *
     * @Route("/{id}", name="consultation_show")
     * @Method("GET")
     */
    public function showAction(Consultation $consultation)
    {
        $deleteForm = $this->createDeleteForm($consultation);

        return $this->render('consultation/show.html.twig', array(
            'consultation' => $consultation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing consultation entity.
     *
     * @Route("/{id}/edit", name="consultation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Consultation $consultation)
    {
        $deleteForm = $this->createDeleteForm($consultation);
        $editForm = $this->createForm('AppBundle\Form\ConsultationType', $consultation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('consultation_edit', array('id' => $consultation->getId()));
        }

        return $this->render('consultation/edit.html.twig', array(
            'consultation' => $consultation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a consultation entity.
     *
     * @Route("/{id}", name="consultation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Consultation $consultation)
    {
        $form = $this->createDeleteForm($consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($consultation);
            $em->flush($consultation);
        }

        return $this->redirectToRoute('consultation_index');
    }

    /**
     * Creates a form to delete a consultation entity.
     *
     * @param Consultation $consultation The consultation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Consultation $consultation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('consultation_delete', array('id' => $consultation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
