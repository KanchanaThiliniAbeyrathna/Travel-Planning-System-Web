<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App1\ExampleBundle\Entity\Visitingplace;
 
class AjaxUpdateController extends Controller
{
    public function updateAction(Request $request)
    {
        $data = $request->get('input');
        
        $em = $this->getDoctrine()->getManager();
 
        $query = $em->createQuery(''
                . 'SELECT c.name '
                . 'FROM App1ExampleBundle:Visitingplace c ' 
                . 'WHERE c.name LIKE :data '
                . 'ORDER BY c.name ASC'
                )
                ->setParameter('data', '%' . $data . '%');
        $results = $query->getResult();
        
        $placesList = '<ul id="matchList" class="list-group">';
        foreach ($results as $result) {
            $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['name']); // Replace text field input by bold one
            $placesList .= '<li class="list-group-item" id="'.$result['name'].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
        }
        $placesList .= '</ul>';
 
        $response = new JsonResponse();
        $response->setData(array('placesList' => $placesList));
        return $response;
    }

    public function acceptHAction(Request $request)
    {
        $id = $request->get('acceptH');
 
        $response = new JsonResponse();
        $response->setData(array('id' => $id));
        return $response;
    }
}