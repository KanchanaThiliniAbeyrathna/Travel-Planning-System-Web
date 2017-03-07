<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\Visitingplace;
use App1\ExampleBundle\Form\VisitingplaceType;
use App1\ExampleBundle\Modal\UploadFileMover;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App1\ExampleBundle\Entity\Hotel;
use App1\ExampleBundle\Entity\City;
use App1\ExampleBundle\Entity\PlaceCategory;
use App1\ExampleBundle\Entity\Trip;

require_once 'C:\xampp\htdocs\Asem5\app1\src\App1\ExampleBundle\Connections\connection.php';

/**
 * Visitingplace controller.
 *
 * @Route("/visitingplace")
 */
class VisitingplaceController extends Controller
{

    /**
     * Lists all Visitingplace entities.
     *
     * @Route("/", name="visitingplace_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $visitingplaces = $em->getRepository('App1ExampleBundle:Visitingplace')->findAll();

        return $this->render('visitingplace/index.html.twig', array(
            'visitingplaces' => $visitingplaces,
        ));
    }

    /**
     * Creates a new Visitingplace entity.
     *
     * @Route("/new", name="visitingplace_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $visitingplace = new Visitingplace();
        $form = $this->createForm('App1\ExampleBundle\Form\VisitingplaceType', $visitingplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($visitingplace);
            $em->flush();

            $postData = $request->request->all();
            insert_contact_number($postData,$visitingplace->getId());
            update_latlong_place($visitingplace);

            $image = $request->files->get('img');
            $uploadedURL='';
            $message='';

            if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
                if (($image->getSize() < 20000000000)) {
                    $originalName = $image->getClientOriginalName();
                    $name_array = explode('.', $originalName);
                    $file_type = $name_array[sizeof($name_array) - 1];
                    $valid_filetypes = array('jpg', 'jpeg', 'bmp', 'png');
                    if (in_array(strtolower($file_type), $valid_filetypes)) {

                        $visitingplace->setFile($image);
                        $visitingplace->setSubDirectory('uploads');
                        $visitingplace->processFile();
                        $visitingplace->setPath($uploadedURL);
                        $uploadedURL=$uploadedURL = $visitingplace->getUploadDirectory() . '/uploads/'. $image->getBasename();
                        updatepath($uploadedURL,$visitingplace->getId());
                    } else {
                        $message = 'Invalid File Type';
                    }
                } else {
                    $message = 'Size exceeds limit';
                }
            } else {
                $message = 'File Error';
            }
            return $this->redirectToRoute('visitingplace_show', array('id' => $visitingplace->getId()));
        }

        return $this->render('visitingplace/new.html.twig', array(
            'visitingplace' => $visitingplace,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Visitingplace entity.
     *
     * @Route("/{id}", name="visitingplace_show")
     * @Method("GET")
     */
    public function showAction(Visitingplace $visitingplace)
    {
        $deleteForm = $this->createDeleteForm($visitingplace);

        return $this->render('visitingplace/show.html.twig', array(
            'visitingplace' => $visitingplace,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Visitingplace entity.
     *
     * @Route("/{id}/place", name="visitingplace_showplace")
     * @Method("GET")
     */
    public function showplaceAction(Visitingplace $visitingplace)
    {
        return $this->render('App1ExampleBundle:Default:showplace.html.twig', array(
            'visitingplace' => $visitingplace,
        ));
    }

    /**
     * @Route("/searchplace/{data}", name="visitingplace_search")
     */
    public function searchplacesAction($data)
    {
        echo $data;
        $connection = connect();
        $query = "SELECT * from visitingplace where name = '{$data}' ";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        $visitingplaces = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push ($visitingplaces, $row);
        }
        colse_connection($connection);

        return $this->render('App1ExampleBundle:Default:searchplaces.html.twig', array(
            'visitingplaces' => $visitingplaces,
        ));
    }

    /**
     * Displays a form to edit an existing Visitingplace entity.
     *
     * @Route("/{id}/edit", name="visitingplace_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Visitingplace $visitingplace)
    {
        $deleteForm = $this->createDeleteForm($visitingplace);
        $editForm = $this->createForm('App1\ExampleBundle\Form\VisitingplaceType', $visitingplace);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($visitingplace);
            $em->flush();

            
            $postData = $request->request->all();

            insert_contact_number($postData,$visitingplace->getId());
            update_latlong_place($visitingplace);

            $image = $request->files->get('img');
            $uploadedURL='';
            $message='';

            if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
                if (($image->getSize() < 20000000000)) {
                    $originalName = $image->getClientOriginalName();
                    $name_array = explode('.', $originalName);
                    $file_type = $name_array[sizeof($name_array) - 1];
                    $valid_filetypes = array('jpg', 'jpeg', 'bmp', 'png');
                    if (in_array(strtolower($file_type), $valid_filetypes)) {

                        $visitingplace->setFile($image);
                        $visitingplace->setSubDirectory('uploads');
                        $visitingplace->processFile();
                        $visitingplace->setPath($uploadedURL);
                        $uploadedURL=$uploadedURL = $visitingplace->getUploadDirectory() . '/uploads/'. $image->getBasename();
                        updatepath($uploadedURL,$visitingplace->getId());
                    } else {
                        $message = 'Invalid File Type';
                    }
                } else {
                    $message = 'Size exceeds limit';
                }
            } else {
                $message = 'File Error';
            }

            return $this->redirectToRoute('visitingplace_edit', array('id' => $visitingplace->getId()));
        }

        return $this->render('visitingplace/edit.html.twig', array(
            'visitingplace' => $visitingplace,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Visitingplace entity.
     *
     * @Route("/{id}", name="visitingplace_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Visitingplace $visitingplace)
    {
        $form = $this->createDeleteForm($visitingplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($visitingplace);
            $em->flush();
        }

        return $this->redirectToRoute('visitingplace_index');
    }

    /**
     * Creates a form to delete a Visitingplace entity.
     *
     * @param Visitingplace $visitingplace The Visitingplace entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Visitingplace $visitingplace)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('visitingplace_delete', array('id' => $visitingplace->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

function insert_contact_number($postData){
    $connection = connect();
    $contact_numbers = $postData;
    $x=0;
    while (1) {
        if (isset($contact_numbers['input'.$x])){
            $contact_number = mysqli_real_escape_string($connection,$contact_numbers['input'.$x]);
            
            
            $query = "insert into contact_number (contact_number,visitingplace_id,hotel_id) values ('{$contact_number}','{$visitingplace_id}',null)";
            $result = mysqli_query($connection,$query);
            if (!$result) {
                die("Database query failed");
            }
            $x++;
        }else{
            break;
        }
        
    }
}

function updatepath($uploadedURL,$id){
    $connection = connect();
    $query = "UPDATE visitingplace SET ";
    $query .= "path = '{$uploadedURL}' WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    colse_connection($connection);
}

function update_latlong_place(Visitingplace $visitingplace){
    $connection = connect();
    $id= $visitingplace->getId();
    $address = $visitingplace->getName();
    $prepAddr = str_replace(' ','+',$address);
    $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
    $output= json_decode($geocode);
    $latitude = $output->results[0]->geometry->location->lat;
    $longitude = $output->results[0]->geometry->location->lng;

    $query = "UPDATE visitingplace SET ";
    $query .= "latitude = '{$latitude}',longitude = '{$longitude}' WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    colse_connection($connection);
}

