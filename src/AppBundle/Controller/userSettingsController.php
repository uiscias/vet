<?php

namespace AppBundle\Controller;

use AppBundle\Entity\userSettings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Usersetting controller.
 *
 * @Route("usersettings")
 */
class userSettingsController extends Controller
{
    /**
     * Lists all userSetting entities.
     *
     * @Route("/", name="usersettings_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userSettings = $em->getRepository('AppBundle:userSettings')->findAll();

        return $this->render('usersettings/index.html.twig', array(
            'userSettings' => $userSettings,
        ));
    }

    /**
     * Creates a new userSetting entity.
     *
     * @Route("/new", name="usersettings_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userSetting = new Usersetting();
        $form = $this->createForm('AppBundle\Form\userSettingsType', $userSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userSetting);
            $em->flush();

            return $this->redirectToRoute('usersettings_show', array('id' => $userSetting->getId()));
        }

        return $this->render('usersettings/new.html.twig', array(
            'userSetting' => $userSetting,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a userSetting entity.
     *
     * @Route("/{id}", name="usersettings_show")
     * @Method("GET")
     */
    public function showAction(userSettings $userSetting)
    {
        $deleteForm = $this->createDeleteForm($userSetting);

        return $this->render('usersettings/show.html.twig', array(
            'userSetting' => $userSetting,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing userSetting entity.
     *
     * @Route("/{id}/edit", name="usersettings_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, userSettings $userSetting)
    {
        $deleteForm = $this->createDeleteForm($userSetting);
        $editForm = $this->createForm('AppBundle\Form\userSettingsType', $userSetting);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usersettings_edit', array('id' => $userSetting->getId()));
        }

        return $this->render('usersettings/edit.html.twig', array(
            'userSetting' => $userSetting,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a userSetting entity.
     *
     * @Route("/{id}", name="usersettings_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, userSettings $userSetting)
    {
        $form = $this->createDeleteForm($userSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userSetting);
            $em->flush();
        }

        return $this->redirectToRoute('usersettings_index');
    }

    /**
     * Creates a form to delete a userSetting entity.
     *
     * @param userSettings $userSetting The userSetting entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(userSettings $userSetting)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usersettings_delete', array('id' => $userSetting->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
