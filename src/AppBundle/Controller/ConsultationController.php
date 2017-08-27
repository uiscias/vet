<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Consultation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }

        $consultation = new Consultation();
        $consultation->setClient($client);
        $editForm = $this->createForm('AppBundle\Form\ConsultationType', $consultation);
//        $form = $this->get('form.factory')->create(ConsultationType::class, $consultation);
//        $editForm->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
        if ($request->isMethod('POST') && $editForm->handleRequest($request)->isValid()) {
            foreach ($consultation->getPhotosConsultation() as $photo){
                $datafile = $photo->getDatafile();
                if (isset($datafile)){
                    $filteredData=substr($datafile, strpos($datafile, ",")+1);
                    $unencodedData=base64_decode($filteredData);
                    $name = str_replace(" ", "", md5(uniqid()) . $photo . '.png');
                    $photo->setLink($name);
                    $upload_path = $this->getParameter('photos_consultation_directory');
                    $fp = fopen( $upload_path . '/' . $name, 'wb' );
                    fwrite( $fp, $unencodedData);
                    fclose( $fp );
                    $this->getDoctrine()->getManager()->persist($photo);

                }
                $consultation->getClient()->setLastConsultationToNow();
            }
            if($consultation->getDebtValueForThisConsultation() > 0){
                $consultation->setHasDebts(true);
            }else{
                $consultation->setHasDebts(false);
            }
//            dump($consultation);
            $em = $this->getDoctrine()->getManager();
            $consultation->setClient($client);
            $em->persist($consultation);
            $em->flush();

            $request->getSession()->getFlashBag()->add('oprationEmail', " votre email a été bien envoyé");

//            return $this->redirectToRoute('consultation_show', array('id' => $consultation->getId()));
            return $this->redirectToRoute('consultation_edit', array('id' => $consultation->getId()));
        }



/*            $em = $this->getDoctrine()->getManager();
            $em->persist($consultation);
            $em->flush($consultation);

            return $this->redirectToRoute('consultation_show', array('id' => $consultation->getId()));

        }
*/
        return $this->render('consultation/new.html.twig', array(
            'consultation' => $consultation,
            'edit_form' => $editForm->createView(),
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
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }



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
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }

        $deleteForm = $this->createDeleteForm($consultation);
        $editForm = $this->createForm('AppBundle\Form\ConsultationType', $consultation);
        $consultationOriginale = $this->getDoctrine()->getManager()->getRepository('AppBundle:Consultation')->find($consultation);

        $originalPhotos = new \Doctrine\Common\Collections\ArrayCollection();
        // Create an ArrayCollection of the current PhotoConsultation objects in the database
        foreach ($consultationOriginale ->getPhotosConsultation() as $photo) {
            $originalPhotos->add($photo);
        }
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $consultation->getClient()->setLastConsultationToNow();

            foreach ($consultation->getPhotosConsultation() as $photo){
                $datafile = $photo->getDatafile();

                if (isset($datafile)){
                    $filteredData=substr($datafile, strpos($datafile, ",")+1);
                    $unencodedData=base64_decode($filteredData);
                    $name = str_replace(" ", "", md5(uniqid()) . $photo . '.png');
                    $photo->setLink($name);
                    $upload_path = $this->getParameter('photos_consultation_directory');
                    $fp = fopen( $upload_path . '/' . $name, 'wb' );
                    fwrite( $fp, $unencodedData);
                    fclose( $fp );
                    $this->getDoctrine()->getManager()->persist($photo);

                }
            }

            // remove the relationship between the tag and the Task
            foreach ($originalPhotos as $photo) {
                if (false === $consultation->getPhotosConsultation()->contains($photo)) {
                    // remove the PhotosConsultation from the Consultation
                    $consultation->removePhotosConsultation($photo);
                    $photo->setConsultation(null);
                    $this->getDoctrine()->getManager()->persist($photo);
                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag); TBC
                }else{

                }
            }

       /*
        * $data = $_POST['data'];
        * $file = md5(uniqid()) . '.png';
        * $uri =  substr($data,strpos($data,",") 1);

        // save to file
        file_put_contents($file, base64_decode($uri));

        // return the filename
        echo json_encode($file);

        */



            $this->getDoctrine()->getManager()->persist($consultation);
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
            ->add('deleteButton', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'Supprimer la consultation',
                'attr' => array(
                    'onclick' => 'return confirm("Etes vous certain de vouloir supprimer cette consultation ?")',
                    'class' => 'btn btn-del'
                )))
            ->getForm()
        ;
    }
}
