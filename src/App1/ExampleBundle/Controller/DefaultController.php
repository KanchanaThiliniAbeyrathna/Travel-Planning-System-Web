<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App1\ExampleBundle\Entity\Visitingplace;
require_once 'C:\xampp\htdocs\Asem5\app1\src\App1\ExampleBundle\Connections\connection.php';

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $visitingplaces = $em->getRepository('App1ExampleBundle:Visitingplace')->findAll();

        return $this->render('App1ExampleBundle:Default:index.html.twig', array(
            'visitingplaces' => $visitingplaces,
        ));
    }
    public function aboutAction()
    {
        return $this->render('App1ExampleBundle:Default:about.html.twig');
        
    }

    public function contactAction(Request $request)
    {
        if ( $request->getMethod()=='POST'){
            $connection = connect();

            $query = "insert into feedback (name,email,telephone,subject,message,seen) values( ";
            $query .= " '{$request->get('name')}','{$request->get('email')}','{$request->get('telephone')}','{$request->get('subject')}','{$request->get('message')}','0')";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
            colse_connection($connection);
        }
        return $this->render('App1ExampleBundle:Default:contact.html.twig');
    }

    public function hotelsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $hotels = $em->getRepository('App1ExampleBundle:Hotel')->findAll();

        $hotelCategories = $em->getRepository('App1ExampleBundle:HotelCategory')->findAll();

        return $this->render('App1ExampleBundle:Default:hotels.html.twig', array(
            'hotels' => $hotels,
            'hotelCategories' => $hotelCategories,
        ));
    }

    public function placesAction()
    {

        $em = $this->getDoctrine()->getManager();
        $visitingplaces = $em->getRepository('App1ExampleBundle:Visitingplace')->findAll();

        $placeCategories = $em->getRepository('App1ExampleBundle:PlaceCategory')->findAll();

        return $this->render('App1ExampleBundle:Default:places.html.twig', array(
            'visitingplaces' => $visitingplaces,
            'placeCategories' => $placeCategories,
        ));
    }

}
