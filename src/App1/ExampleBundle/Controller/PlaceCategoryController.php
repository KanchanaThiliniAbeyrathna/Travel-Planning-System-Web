<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\PlaceCategory;
use App1\ExampleBundle\Form\PlaceCategoryType;

/**
 * PlaceCategory controller.
 *
 * @Route("/placecategory")
 */
class PlaceCategoryController extends Controller
{
    /**
     * Lists all PlaceCategory entities.
     *
     * @Route("/", name="placecategory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $placeCategories = $em->getRepository('App1ExampleBundle:PlaceCategory')->findAll();

        return $this->render('placecategory/index.html.twig', array(
            'placeCategories' => $placeCategories,
        ));
    }

    /**
     * Creates a new PlaceCategory entity.
     *
     * @Route("/new", name="placecategory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $placeCategory = new PlaceCategory();
        $form = $this->createForm('App1\ExampleBundle\Form\PlaceCategoryType', $placeCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($placeCategory);
            $em->flush();

            return $this->redirectToRoute('placecategory_show', array('id' => $placeCategory->getId()));
        }

        return $this->render('placecategory/new.html.twig', array(
            'placeCategory' => $placeCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PlaceCategory entity.
     *
     * @Route("/{id}", name="placecategory_show")
     * @Method("GET")
     */
    public function showAction(PlaceCategory $placeCategory)
    {
        $deleteForm = $this->createDeleteForm($placeCategory);

        return $this->render('placecategory/show.html.twig', array(
            'placeCategory' => $placeCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PlaceCategory entity.
     *
     * @Route("/{id}/edit", name="placecategory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlaceCategory $placeCategory)
    {
        $deleteForm = $this->createDeleteForm($placeCategory);
        $editForm = $this->createForm('App1\ExampleBundle\Form\PlaceCategoryType', $placeCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($placeCategory);
            $em->flush();

            return $this->redirectToRoute('placecategory_edit', array('id' => $placeCategory->getId()));
        }

        return $this->render('placecategory/edit.html.twig', array(
            'placeCategory' => $placeCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PlaceCategory entity.
     *
     * @Route("/{id}", name="placecategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlaceCategory $placeCategory)
    {
        $form = $this->createDeleteForm($placeCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($placeCategory);
            $em->flush();
        }

        return $this->redirectToRoute('placecategory_index');
    }

    /**
     * Creates a form to delete a PlaceCategory entity.
     *
     * @param PlaceCategory $placeCategory The PlaceCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlaceCategory $placeCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('placecategory_delete', array('id' => $placeCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
