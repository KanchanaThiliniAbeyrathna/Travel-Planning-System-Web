<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\Hotel;
use App1\ExampleBundle\Form\HotelType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App1\ExampleBundle\Modal\UploadFileMover;

require_once 'C:\xampp\htdocs\Asem5\app1\src\App1\ExampleBundle\Connections\connection.php';

/**
 * Hotel controller.
 *
 * @Route("/hotel")
 */
class HotelController extends Controller
{

    /**
     * Lists all Hotel entities.
     *
     * @Route("/", name="hotel_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $hotels = $em->getRepository('App1ExampleBundle:Hotel')->findAll();

        return $this->render('hotel/index.html.twig', array(
            'hotels' => $hotels,
        ));
    }

    /**
     * Lists all .
     *
     * @Route("/", name="hotel_show1")
     * 
     */
    public function show1Action()
    {
        

        return $this->render('hotel/show1.html.twig');
    }

    /**
     * Creates a new Hotel entity.
     *
     * @Route("/new", name="hotel_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $hotel = new Hotel();
        $form = $this->createForm('App1\ExampleBundle\Form\HotelType', $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hotel);
            $em->flush();

            $postData = $request->request->all();
            insert_contact_no($postData,$hotel->getId());
            update_latlong($hotel);

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

                        $hotel->setFile($image);
                        $hotel->setSubDirectory('uploads');
                        $hotel->processFile();
                        $hotel->setPath($uploadedURL);
                        $uploadedURL=$uploadedURL = $hotel->getUploadDirectory() . '/uploads/'. $image->getBasename();
                        updatehotelpath($uploadedURL,$hotel->getId());
                    } else {
                        $message = 'Invalid File Type';
                    }
                } else {
                    $message = 'Size exceeds limit';
                }
            } else {
                $message = 'File Error';
            }
            return $this->redirectToRoute('hotel_show', array('id' => $hotel->getId()));
        }

        return $this->render('hotel/new.html.twig', array(
            'hotel' => $hotel,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Hotel entity.
     *
     * @Route("/{id}", name="hotel_show")
     * @Method("GET")
     */
    public function showAction(Hotel $hotel)
    {
        $deleteForm = $this->createDeleteForm($hotel);

        return $this->render('hotel/show.html.twig', array(
            'hotel' => $hotel,
            'delete_form' => $deleteForm->createView(),
        ));
    }

        /**
     * Finds and displays a Hotel entity.
     *
     * @Route("/{id}/hotel", name="hotel_showhotel")
     * @Method("GET")
     */
    public function showhotelAction(Hotel $hotel)
    {
        return $this->render('App1ExampleBundle:Default:showhotel.html.twig', array(
            'hotel' => $hotel,
        ));
    }

    /**
     * Displays a form to edit an existing Hotel entity.
     *
     * @Route("/{id}/edit", name="hotel_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Hotel $hotel)
    {
        $deleteForm = $this->createDeleteForm($hotel);
        $editForm = $this->createForm('App1\ExampleBundle\Form\HotelType', $hotel);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hotel);
            $em->flush();

            $postData = $request->request->all();
            insert_contact_no($postData,$hotel->getId());
            update_latlong($hotel);

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

                        $hotel->setFile($image);
                        $hotel->setSubDirectory('uploads');
                        $hotel->processFile();
                        $hotel->setPath($uploadedURL);
                        $uploadedURL=$uploadedURL = $hotel->getUploadDirectory() . '/uploads/'. $image->getBasename();
                        updatehotelpath($uploadedURL,$hotel->getId());
                    } else {
                        $message = 'Invalid File Type';
                    }
                } else {
                    $message = 'Size exceeds limit';
                }
            } else {
                $message = 'File Error';
            }

            return $this->redirectToRoute('hotel_edit', array('id' => $hotel->getId()));
        }

        return $this->render('hotel/edit.html.twig', array(
            'hotel' => $hotel,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Hotel entity.
     *
     * @Route("/{id}", name="hotel_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Hotel $hotel)
    {
        $form = $this->createDeleteForm($hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hotel);
            $em->flush();
        }

        return $this->redirectToRoute('hotel_index');
    }

    /**
     * Creates a form to delete a Hotel entity.
     *
     * @param Hotel $hotel The Hotel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hotel $hotel)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hotel_delete', array('id' => $hotel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}

function insert_contact_no($postData,$hotel_id){
    $connection = connect();
    $contact_numbers = $postData;
    $x=0;
    while (1) {
        if (isset($contact_numbers['input'.$x])){
            $contact_number = mysqli_real_escape_string($connection,$contact_numbers['input'.$x]);
            
            
            $query = "insert into contact_number (contact_number,visitingplace_id,hotel_id) values ('{$contact_number}',null,'{$hotel_id}')";
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

function updatehotelpath($uploadedURL,$id){
    $connection = connect();
    $query = "UPDATE hotel SET ";
    $query .= "path = '{$uploadedURL}' WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    colse_connection($connection);
}

function update_latlong(Hotel $hotel){
    $connection = connect();
    $id= $hotel->getId();
    $address = $hotel->getAddress();
    $prepAddr = str_replace(' ','+',$address);
    $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
    $output= json_decode($geocode);
    $latitude = $output->results[0]->geometry->location->lat;
    $longitude = $output->results[0]->geometry->location->lng;

    $query = "UPDATE hotel SET ";
    $query .= "latitude = '{$latitude}',longitude = '{$longitude}' WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    colse_connection($connection);
}
