<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Consultation;
use AppBundle\Entity\PhotosConsultation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Photosconsultation controller.
 *
 * @Route("photosconsultation")
 */
class PhotosConsultationController extends Controller
{
    /**
     * Lists all photosConsultation entities.
     *
     *
     *
     * @Route("/", name="photosconsultation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $photosConsultations = $em->getRepository('AppBundle:PhotosConsultation')->findAll();

        return $this->render('photosconsultation/index.html.twig', array(
            'photosConsultations' => $photosConsultations,
        ));
    }

    /**
     * Creates a new photosConsultation entity.
     *
     * @Route("/{consultation}/new", name="photosconsultation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, \AppBundle\Entity\Consultation $consultation)
    {
        $photosConsultation = new Photosconsultation();
        $photosConsultation->setConsultation($consultation);
        $form = $this->createForm('AppBundle\Form\PhotosConsultationType', $photosConsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            dump($photosConsultation);
            $photosConsultation->setConsultation($consultation);
            $em->persist($photosConsultation);
            $em->flush($photosConsultation);


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

            return $this->redirectToRoute('photosconsultation_show', array('id' => $photosConsultation->getId()));
        }

        return $this->render('photosconsultation/new.html.twig', array(
            'photosConsultation' => $photosConsultation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a photosConsultation entity.
     *
     * @Route("/{id}", name="photosconsultation_show")
     * @Method("GET")
     */
    public function showAction(PhotosConsultation $photosConsultation)
    {
        $deleteForm = $this->createDeleteForm($photosConsultation);

        return $this->render('photosconsultation/show.html.twig', array(
            'photosConsultation' => $photosConsultation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing photosConsultation entity.
     *
     * @Route("/{id}/edit", name="photosconsultation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PhotosConsultation $photosConsultation)
    {
        $deleteForm = $this->createDeleteForm($photosConsultation);
        $editForm = $this->createForm('AppBundle\Form\PhotosConsultationType', $photosConsultation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photosconsultation_edit', array('id' => $photosConsultation->getId()));
        }

        return $this->render('photosconsultation/edit.html.twig', array(
            'photosConsultation' => $photosConsultation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a photosConsultation entity.
     *
     * @Route("/{id}", name="photosconsultation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PhotosConsultation $photosConsultation)
    {
        $form = $this->createDeleteForm($photosConsultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($photosConsultation);
            $em->flush($photosConsultation);
        }

        return $this->redirectToRoute('photosconsultation_index');
    }

    /**
     * Creates a form to delete a photosConsultation entity.
     *
     * @param PhotosConsultation $photosConsultation The photosConsultation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PhotosConsultation $photosConsultation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('photosconsultation_delete', array('id' => $photosConsultation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
