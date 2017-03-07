<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\Trip;
use App1\ExampleBundle\Form\TripType;

require_once 'C:\xampp\htdocs\Asem5\app1\src\App1\ExampleBundle\Connections\connection.php';

/**
 * Trip controller.
 *
 * @Route("/trip")
 */
class TripController extends Controller
{
    /**
     * Lists all Trip entities.
     *
     * @Route("/", name="trip_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $user_id= $request->getSession()->get('login')->getId();
        $trips=get_all_trips($user_id);

        return $this->render('trip/index.html.twig', array(
            'trips' => $trips,
        ));
    }

    /**
     * Creates a new Trip entity.
     *
     * @Route("/new", name="trip_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('App1ExampleBundle:City')->findAll();
        $placeCategories = $em->getRepository('App1ExampleBundle:PlaceCategory')->findAll();

        if ($request->getMethod()=='POST'){
            $connection = connect();

            $placeCategory = $request->get('placeCategory');
            $city = $request->get('city');
            $no_of_days = $request->get('no_of_days');

            return $this->redirectToRoute('trip_select',  array(
                'city' => $city,
                'placeCategory' => $placeCategory,
                'no_of_days' => $no_of_days,
            ));
        }

        return $this->render('trip/new.html.twig', array(
            'cities' => $cities,
            'placeCategories' => $placeCategories,
        ));
    }

    /**
     * @Route("/select/{city}/{placeCategory}/{no_of_days}", name="trip_select")
     * @Method({"GET", "POST"})
     */
    public function selectplacesAction($placeCategory,$city,$no_of_days,Request $request)
    {
        $connection = connect();
        $query = "SELECT * from visitingplace WHERE city_id = '{$city}' AND place_category_id = '{$placeCategory}' ";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        $visitingplaces = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push ($visitingplaces, $row);
        }

        $query = "SELECT * from hotel WHERE city_id = '{$city}' ";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        $hotels = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push ($hotels, $row);
        }

        $query = "SELECT city from city WHERE id = '{$city}' LIMIT 1";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        $row = mysqli_fetch_assoc($result);
        $cityname = $row['city'];
        colse_connection($connection);
        
        if ($request->getMethod()=='POST'){
            $connection = connect();

            $tripPlaces = $request->get('place');
            $tripHotels = $request->get('hotel');
            $user_id= $request->getSession()->get('login')->getId();

            $id = insert_trip($user_id,$city,$no_of_days,$cityname);
            trip_add_places($tripHotels,$tripPlaces,$id);

            return $this->redirectToRoute('trip_show', array('id' => $id));

        }

        return $this->render('trip/select.html.twig', array(
            'visitingplaces' => $visitingplaces,
            'hotels' => $hotels,
            'cityname' => $cityname,
        ));
    }

    /**
     * Finds and displays a Trip entity.
     *
     * @Route("/{id}", name="trip_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $trip=get_trip($id);
        $places=get_places($id);
        $hotels=get_hotels($id);

        return $this->render('trip/show.html.twig', array(
            'trip' => $trip, 
            'places' => $places, 
            'hotels' => $hotels,            
        ));
    }

    /**
     * Displays a form to edit an existing Trip entity.
     *
     * @Route("/{id}/edit", name="trip_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Trip $trip)
    {
        $deleteForm = $this->createDeleteForm($trip);
        $editForm = $this->createForm('App1\ExampleBundle\Form\TripType', $trip);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trip);
            $em->flush();

            return $this->redirectToRoute('trip_edit', array('id' => $trip->getId()));
        }

        return $this->render('trip/edit.html.twig', array(
            'trip' => $trip,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Trip entity.
     *
     * @Route("/{id}", name="trip_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Trip $trip)
    {
        $form = $this->createDeleteForm($trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trip);
            $em->flush();
        }

        return $this->redirectToRoute('trip_index');
    }

    /**
     * Creates a form to delete a Trip entity.
     *
     * @param Trip $trip The Trip entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trip $trip)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trip_delete', array('id' => $trip->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

function insert_trip($user_id,$city,$no_of_days,$cityname){
    $connection = connect();
    $name = "Trip to ".$cityname;

    $query = "insert into trip (";
    $query .= " name,city_id,user_id";
    $query .= ") values( ";
    $query .= " '{$name}','{$city}','{$user_id}'";
    $query .= ")";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    $id = mysqli_insert_id($connection);
    return $id;
}

function trip_add_places($tripHotels,$tripPlaces,$id){
    $connection = connect();

    if (is_array($tripPlaces) || is_object($tripPlaces))
    {    
        foreach($tripPlaces  as $tripPlace) {
            $query = "insert into visitingplace_has_trip (";
            $query .= " visitingplace_id,trip_id";
            $query .= ") values( ";
            $query .= " '{$tripPlace}','{$id}'";
            $query .= ")";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
        }
    }
    if (is_array($tripHotels) || is_object($tripHotels))
    {  
        foreach($tripHotels  as $tripHotel) {
            $query = "insert into hotel_has_trip (";
            $query .= " hotel_id,trip_id";
            $query .= ") values( ";
            $query .= " '{$tripHotel}','{$id}'";
            $query .= ")";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
        }
    }
}

function get_trip($id){
    $connection = connect();
    $query = "SELECT * from trip WHERE id = '{$id}' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    $row=mysqli_fetch_assoc($result);
    return $row;
}

function get_places($id){
    $connection = connect();
    $query = "SELECT * from visitingplace_has_trip WHERE trip_id = '{$id}' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
        
    $strlat = get_str_latlong($id)['latitude'];
    $strlng = get_str_latlong($id)['longitude'];

    $places = array();

    while ($row = mysqli_fetch_assoc($result)){    
        $visitingplace_id= $row['visitingplace_id'];
        $query2 = "SELECT id,name,address,path,( 3959  * acos( cos( radians({$strlat}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians({$strlng}) ) + sin( radians({$strlat}) ) * sin( radians( latitude ) ) ) ) AS distance from visitingplace WHERE id = '{$visitingplace_id}' ";
        $result2 = mysqli_query($connection,$query2);
        confirm_query($result2);
        $row2 = mysqli_fetch_assoc($result2);
        array_push ($places, $row2);
    }

    $distance = array();
    foreach ($places as $key => $row)
    {
        $distance[$key] = $row['distance'];
    }
    array_multisort($distance, SORT_ASC, $places);

    return $places;
}

function get_hotels($id){
    $connection = connect();
    $query = "SELECT * from hotel_has_trip WHERE trip_id = '{$id}' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);

    $strlat = get_str_latlong($id)['latitude'];
    $strlng = get_str_latlong($id)['longitude'];

    $hotels = array();
    while ($row = mysqli_fetch_assoc($result)){
        $hotel_id= $row['hotel_id'];
        $query2 = "SELECT id,name,address,path,( 3959  * acos( cos( radians({$strlat}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians({$strlng}) ) + sin( radians({$strlat}) ) * sin( radians( latitude ) ) ) ) AS distance from hotel WHERE id = '{$hotel_id}' ";
        $result2 = mysqli_query($connection,$query2);
        confirm_query($result2);
        $row2 = mysqli_fetch_assoc($result2);
        array_push ($hotels, $row2);
    }

    $distance = array();
    foreach ($hotels as $key => $row)
    {
        $distance[$key] = $row['distance'];
    }
    array_multisort($distance, SORT_ASC, $hotels);

    return $hotels;
}

function get_str_latlong($id){
    $connection = connect();
    $query = "SELECT * from visitingplace_has_trip WHERE trip_id = '{$id}' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
        
    $row = mysqli_fetch_assoc($result);
    $visitingplace_id= $row['visitingplace_id'];
    $q = "SELECT latitude,longitude from visitingplace WHERE id = '{$visitingplace_id}' ";
    $r = mysqli_query($connection,$q);
    confirm_query($r);
    return mysqli_fetch_assoc($r);    
}

function get_all_trips($user_id){
    $connection = connect();
    $query = "SELECT * from trip WHERE user_id = '{$user_id}' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    $trips = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push ($trips, $row);
    }
    return $trips;
}

