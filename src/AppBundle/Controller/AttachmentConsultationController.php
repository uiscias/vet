<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Consultation;
use AppBundle\Entity\AttachmentConsultation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Photosconsultation controller.
 *
 * @Route("attachmentconsultation")
 */
class AttachmentConsultationController extends Controller
{
    /**
     * Lists all attachmentConsultation entities.
     *
     *
     *
     * @Route("/", name="attachmentconsultation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $attachmentConsultations = $em->getRepository('AppBundle:AttachmentConsultation')->findAll();

        return $this->render('attachmentconsultation/index.html.twig', array(
            'attachmentConsultations' => $attachmentConsultations,
        ));
    }

    /**
     * Creates a new attachmentConsultation entity.
     *
     * @Route("/{consultation}/new", name="attachmentconsultation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, \AppBundle\Entity\Consultation $consultation)
    {
        $attachmentConsultation = new Photosconsultation();
        $attachmentConsultation->setConsultation($consultation);
        $form = $this->createForm('AppBundle\Form\AttachmentConsultationType', $attachmentConsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            dump($attachmentConsultation);
            $attachmentConsultation->setConsultation($consultation);
            $em->persist($attachmentConsultation);
            $em->flush($attachmentConsultation);


            // Generate a unique name for the file before saving it
            //$fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            /*$file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );*/

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            //$product->setBrochure($fileName);

            return $this->redirectToRoute('attachmentconsultation_show', array('id' => $attachmentConsultation->getId()));
        }

        return $this->render('attachmentconsultation/new.html.twig', array(
            'attachmentConsultation' => $attachmentConsultation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a attachmentConsultation entity.
     *
     * @Route("/{id}", name="attachmentconsultation_show")
     * @Method("GET")
     */
    public function showAction(AttachmentConsultation $attachmentConsultation)
    {
        $deleteForm = $this->createDeleteForm($attachmentConsultation);

        return $this->render('attachmentconsultation/show.html.twig', array(
            'attachmentConsultation' => $attachmentConsultation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing attachmentConsultation entity.
     *
     * @Route("/{id}/edit", name="attachmentconsultation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AttachmentConsultation $attachmentConsultation)
    {
        $deleteForm = $this->createDeleteForm($attachmentConsultation);
        $editForm = $this->createForm('AppBundle\Form\AttachmentConsultationType', $attachmentConsultation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('attachmentconsultation_edit', array('id' => $attachmentConsultation->getId()));
        }

        return $this->render('attachmentconsultation/edit.html.twig', array(
            'attachmentConsultation' => $attachmentConsultation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a attachmentConsultation entity.
     *
     * @Route("/{id}", name="attachmentconsultation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AttachmentConsultation $attachmentConsultation)
    {
        $form = $this->createDeleteForm($attachmentConsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attachmentConsultation);
            $em->flush($attachmentConsultation);
        }

        return $this->redirectToRoute('attachmentconsultation_index');
    }

    /**
     * Creates a form to delete a attachmentConsultation entity.
     *
     * @param AttachmentConsultation $attachmentConsultation The attachmentConsultation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AttachmentConsultation $attachmentConsultation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attachmentconsultation_delete', array('id' => $attachmentConsultation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
