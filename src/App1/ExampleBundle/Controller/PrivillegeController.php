<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\Privillege;
use App1\ExampleBundle\Form\PrivillegeType;

/**
 * Privillege controller.
 *
 * @Route("/privillege")
 */
class PrivillegeController extends Controller
{
    /**
     * Lists all Privillege entities.
     *
     * @Route("/", name="privillege_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $privilleges = $em->getRepository('App1ExampleBundle:Privillege')->findAll();

        return $this->render('privillege/index.html.twig', array(
            'privilleges' => $privilleges,
        ));
    }

    /**
     * Creates a new Privillege entity.
     *
     * @Route("/new", name="privillege_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $privillege = new Privillege();
        $form = $this->createForm('App1\ExampleBundle\Form\PrivillegeType', $privillege);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($privillege);
            $em->flush();

            return $this->redirectToRoute('privillege_show', array('id' => $privillege->getId()));
        }

        return $this->render('privillege/new.html.twig', array(
            'privillege' => $privillege,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Privillege entity.
     *
     * @Route("/{id}", name="privillege_show")
     * @Method("GET")
     */
    public function showAction(Privillege $privillege)
    {
        $deleteForm = $this->createDeleteForm($privillege);

        return $this->render('privillege/show.html.twig', array(
            'privillege' => $privillege,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Privillege entity.
     *
     * @Route("/{id}/edit", name="privillege_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Privillege $privillege)
    {
        $deleteForm = $this->createDeleteForm($privillege);
        $editForm = $this->createForm('App1\ExampleBundle\Form\PrivillegeType', $privillege);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($privillege);
            $em->flush();

            return $this->redirectToRoute('privillege_edit', array('id' => $privillege->getId()));
        }

        return $this->render('privillege/edit.html.twig', array(
            'privillege' => $privillege,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Privillege entity.
     *
     * @Route("/{id}", name="privillege_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Privillege $privillege)
    {
        $form = $this->createDeleteForm($privillege);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($privillege);
            $em->flush();
        }

        return $this->redirectToRoute('privillege_index');
    }

    /**
     * Creates a form to delete a Privillege entity.
     *
     * @param Privillege $privillege The Privillege entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Privillege $privillege)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('privillege_delete', array('id' => $privillege->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
