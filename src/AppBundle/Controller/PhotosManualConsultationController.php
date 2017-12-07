<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Consultation;
use AppBundle\Entity\PhotosManualConsultation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Photosconsultation controller.
 *
 * @Route("photosmanualconsultation")
 */
class PhotosManualConsultationController extends Controller
{
    /**
     * Lists all photosManualConsultation entities.
     *
     *
     *
     * @Route("/", name="photosmanualconsultation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $photosManualConsultations = $em->getRepository('AppBundle:PhotosManualConsultation')->findAll();

        return $this->render('photosmanualconsultation/index.html.twig', array(
            'photosManualConsultations' => $photosManualConsultations,
        ));
    }

    /**
     * Creates a new photosManualConsultation entity.
     *
     * @Route("/{consultation}/new", name="photosmanualconsultation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, \AppBundle\Entity\Consultation $consultation)
    {
        $photosManualConsultation = new Photosconsultation();
        $photosManualConsultation->setConsultation($consultation);
        $form = $this->createForm('AppBundle\Form\PhotosManualConsultationType', $photosManualConsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            dump($photosManualConsultation);
            $photosManualConsultation->setConsultation($consultation);
            $em->persist($photosManualConsultation);
            $em->flush($photosManualConsultation);


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

            return $this->redirectToRoute('photosmanualconsultation_show', array('id' => $photosManualConsultation->getId()));
        }

        return $this->render('photosmanualconsultation/new.html.twig', array(
            'photosManualConsultation' => $photosManualConsultation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a photosManualConsultation entity.
     *
     * @Route("/{id}", name="photosmanualconsultation_show")
     * @Method("GET")
     */
    public function showAction(PhotosManualConsultation $photosManualConsultation)
    {
        $deleteForm = $this->createDeleteForm($photosManualConsultation);

        return $this->render('photosmanualconsultation/show.html.twig', array(
            'photosManualConsultation' => $photosManualConsultation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing photosManualConsultation entity.
     *
     * @Route("/{id}/edit", name="photosmanualconsultation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PhotosManualConsultation $photosManualConsultation)
    {
        $deleteForm = $this->createDeleteForm($photosManualConsultation);
        $editForm = $this->createForm('AppBundle\Form\PhotosManualConsultationType', $photosManualConsultation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photosmanualconsultation_edit', array('id' => $photosManualConsultation->getId()));
        }

        return $this->render('photosmanualconsultation/edit.html.twig', array(
            'photosManualConsultation' => $photosManualConsultation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a photosManualConsultation entity.
     *
     * @Route("/{id}", name="photosmanualconsultation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PhotosManualConsultation $photosManualConsultation)
    {
        $form = $this->createDeleteForm($photosManualConsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($photosManualConsultation);
            $em->flush($photosManualConsultation);
        }

        return $this->redirectToRoute('photosmanualconsultation_index');
    }

    /**
     * Creates a form to delete a photosManualConsultation entity.
     *
     * @param PhotosManualConsultation $photosManualConsultation The photosManualConsultation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PhotosManualConsultation $photosManualConsultation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('photosmanualconsultation_delete', array('id' => $photosManualConsultation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
