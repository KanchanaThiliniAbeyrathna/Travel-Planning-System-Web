<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\HotelCategory;
use App1\ExampleBundle\Form\HotelCategoryType;

/**
 * HotelCategory controller.
 *
 * @Route("/hotelcategory")
 */
class HotelCategoryController extends Controller
{
    /**
     * Lists all HotelCategory entities.
     *
     * @Route("/", name="hotelcategory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $hotelCategories = $em->getRepository('App1ExampleBundle:HotelCategory')->findAll();

        return $this->render('hotelcategory/index.html.twig', array(
            'hotelCategories' => $hotelCategories,
        ));
    }

    /**
     * Creates a new HotelCategory entity.
     *
     * @Route("/new", name="hotelcategory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $hotelCategory = new HotelCategory();
        $form = $this->createForm('App1\ExampleBundle\Form\HotelCategoryType', $hotelCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hotelCategory);
            $em->flush();

            return $this->redirectToRoute('hotelcategory_show', array('id' => $hotelCategory->getId()));
        }

        return $this->render('hotelcategory/new.html.twig', array(
            'hotelCategory' => $hotelCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a HotelCategory entity.
     *
     * @Route("/{id}", name="hotelcategory_show")
     * @Method("GET")
     */
    public function showAction(HotelCategory $hotelCategory)
    {
        $deleteForm = $this->createDeleteForm($hotelCategory);

        return $this->render('hotelcategory/show.html.twig', array(
            'hotelCategory' => $hotelCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing HotelCategory entity.
     *
     * @Route("/{id}/edit", name="hotelcategory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, HotelCategory $hotelCategory)
    {
        $deleteForm = $this->createDeleteForm($hotelCategory);
        $editForm = $this->createForm('App1\ExampleBundle\Form\HotelCategoryType', $hotelCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hotelCategory);
            $em->flush();

            return $this->redirectToRoute('hotelcategory_edit', array('id' => $hotelCategory->getId()));
        }

        return $this->render('hotelcategory/edit.html.twig', array(
            'hotelCategory' => $hotelCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HotelCategory entity.
     *
     * @Route("/{id}", name="hotelcategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, HotelCategory $hotelCategory)
    {
        $form = $this->createDeleteForm($hotelCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hotelCategory);
            $em->flush();
        }

        return $this->redirectToRoute('hotelcategory_index');
    }

    /**
     * Creates a form to delete a HotelCategory entity.
     *
     * @param HotelCategory $hotelCategory The HotelCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HotelCategory $hotelCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hotelcategory_delete', array('id' => $hotelCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
