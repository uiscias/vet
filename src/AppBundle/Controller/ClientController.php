<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Consultation;
use AppBundle\Entity\ClientSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */

/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        // The second parameter is used to specify on what object the role is tested.
//        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        // the above is a shortcut for this
     //   $user = $this->get('security.token_storage')->getToken()->getUser();

// yay! Use this to see if the user is logged in
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException();
            }
            if ($this->getUser()) {
                $user = $this->getUser()->getUsername();
            }

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Client');


        $clientSearch = new ClientSearch();
        $searchForm = $this->createForm('AppBundle\Form\ClientSearchType', $clientSearch);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $clientSearch = $clientSearch->getSearchField();
            $query = $repository->createQueryBuilder('c')
                ->where('c.associatedUsername = :username')
                ->andWhere('(c.firstName LIKE :searchElem OR c.lastName LIKE :searchElem OR c.phone LIKE :searchElem OR c.phone2 LIKE :searchElem OR c.eMail LIKE :searchElem) and c.deletedAt IS NULL')->setParameter('username', $user)
                ->setParameter('searchElem', '%'.$clientSearch.'%')
                ->orderBy('c.lastConsultation', 'DESC')
                ->setMaxResults(250)
                ->getQuery();

            $clients = $query->getResult();

        }else{
            $query = $repository->createQueryBuilder('c')
                ->where('c.associatedUsername = :username')
                ->setParameter('username', $user)
                ->orderBy('c.lastConsultation', 'DESC')
                ->setMaxResults(50)
                ->getQuery();

            $clients = $query->getResult();
        }



        return $this->render('client/index.html.twig', array(
            'clients' => $clients,
            'searchForm' => $searchForm->createView(),
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }

        $client = new Client();
        $client->setAssociatedUsername($user);
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $client->setLastConsultationToNow();
            $em->persist($client);
            $em->flush($client);

            return $this->redirectToRoute('client_consultations', array('id' => $client->getId()));
        }

        return $this->render('client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays clients's debts.
     *
     * @Route("/debts", name="client_debts")
     * @Method("GET")
     */
    public function debtsAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }

        $em = $this->getDoctrine()->getManager();
        $querySumDebts = $em->createQueryBuilder('debts')
            ->select('SUM(g.debtValueForThisConsultation) as debt')
            ->from('AppBundle\Entity\Consultation', 'g')
            ->addselect('c.id')
            ->addselect('c.firstName')
            ->addselect('c.lastName')
            ->leftJoin('g.client', 'c')
            ->where('g.debtValueForThisConsultation > 0 and c.associatedUsername = :user')
            ->addGroupBy('g.client')
            ->orderBy('debt', 'DESC')
            ->setParameter('user', $user)
            ->getQuery();

        $SumDebts = $querySumDebts->getResult();

        return $this->render('client/debts.html.twig', array(
            'debts' => $SumDebts
        ));
    }


    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);

        return $this->render('client/show.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays client's consultations.
     *
     * @Route("/{id}/consultations", name="client_consultations")
     * @Method("GET")
     */
    public function consultationsAction(Client $client)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
        }
        if ($client->getAssociatedUsername() != $user) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($client);

        $em = $this->getDoctrine()->getManager();
        $consultations = $em->getRepository('AppBundle:Consultation')->findBy(array('client' => $client->getId()), array('id' => 'DESC') );


        return $this->render('client/consultations.html.twig', array(
            'client' => $client,
            'consultations' => $consultations,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('AppBundle\Form\ClientType', $client);
        $clientOriginal = $this->getDoctrine()->getManager()->getRepository('AppBundle:Client')->find($client);

        $originalAnimals = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($clientOriginal->getAnimals() as $animal) {
            $originalAnimals->add($animal);
        }
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // remove the relationship between the tag and the Task
            foreach ($originalAnimals as $animal) {

                if (false === $client->getAnimals()->contains($animal)) {
                    // remove the PhotosConsultation from the Consultation
                    $client->removeAnimal($animal);
                    $animal->setClient(null);
                    $this->getDoctrine()->getManager()->persist($animal);
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



            $this->getDoctrine()->getManager()->persist($client);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('client_consultations', array('id' => $client->getId()));
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="client_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush($client);
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->add('deleteButton', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'Supprimer le client',
                'attr' => array(
                    'onclick' => 'return confirm("Etes vous certain de vouloir supprimer le client ?")',
                    'class' => 'btn btn-del'
                )))
            ->getForm()
        ;
    }


}
