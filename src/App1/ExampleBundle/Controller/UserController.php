<?php

namespace App1\ExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App1\ExampleBundle\Entity\User;
use App1\ExampleBundle\Form\UserType;
use App1\ExampleBundle\Modal\Login;
require_once 'C:\xampp\htdocs\Asem5\app1\src\App1\ExampleBundle\Connections\connection.php';


/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('App1ExampleBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Login
     *
     * @Route("/login", name="user_login")
     * @Method({"GET", "POST"})
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('App1ExampleBundle:User');


        if ( $request->getMethod()=='POST'){
            $session->clear();
            $email= $request->get('email');
            $password = sha1($request->get('password'));
            $remember = $request->get('remember');
            
            $connection = connect();
            $query = "SELECT * from user where email='{$email}' and password='{$password}' LIMIT 1";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
            $user = mysqli_fetch_assoc($result);
            colse_connection($connection);

            $role_id = $user['role_id'];
            $id = $user['id'];

            if($user['active'] == '1' ){
                //if ($remember == 'remember-me') {
                    $login = new Login();
                    $login->setEmail($email);
                    $login->setPassword($password); 
                    $login->setId($id);

                    
                    $login->setRoleId($role_id);

                    $session->set('login' , $login);
                //} 
                if($role_id == 3){
                    return $this->render('App1ExampleBundle:Default:index.html.twig',array('email' => $email));
                }elseif ($role_id == 2) {
                    return $this->render('userbase.html.twig');
                }               
                return $this->render('App1ExampleBundle:Default:dashboard.html.twig',array(
                    'hotelrequests' => getAllHotelRequests(),
                    'comments' => getAllComments(),
                ));
            }else{
                return $this->render('user/login.html.twig',array('msg' => 'invalid login..Try again' ));
            }
        }else{
            if($session->has('login')){
                $login = $session->get('login');
                $email = $login->getEmail();
                $password = $login->getPassword();
                $role_id = $login->getRoleId();

                $connection = connect();
                $query = "SELECT * from user where email='{$login->getEmail()}' and password='{$login->getPassword()}' LIMIT 1";
                $result = mysqli_query($connection,$query);
                confirm_query($result);
                $user = mysqli_fetch_assoc($result);
                colse_connection($connection);

                $role_id = $user['role_id'];
                if($user){
                    if($role_id == 3){
                        return $this->render('App1ExampleBundle:Default:index.html.twig',array('email' => $email));
                    }elseif ($role_id == 2) {
                        return $this->render('userbase.html.twig');
                    }               
                    return $this->render('App1ExampleBundle:Default:dashboard.html.twig',array(
                        'hotelrequests' => getAllHotelRequests(),
                        'comments' => getAllComments(),
                    ));                
                }
            }
        }
        return $this->render('user/login.html.twig',array('msg' => 'Please Login' ));
    }

    /**
     * Logout
     *
     * @Route("/logout", name="user_logout")
     * @Method({"GET", "POST"})
     */
    public function logoutAction(Request $request) 
    {
        $session = $request->getSession();
        $session->clear();
        return $this->render('App1ExampleBundle:Default:index.html.twig');
    }

    /**
     * SignUp
     *
     * @Route("/signup", name="user_signup")
     * @Method({"GET", "POST"})
     */
    public function SignUpAction(Request $request)
    {
        if ( $request->getMethod()=='POST'){
            $connection = connect();
            $user = new User();
            $user->setNameInFull($request->get('name'));
            $user->setEmail($request->get('email'));
            $user->setPassword(sha1($request->get('password')));
            $user->setActive('0');
            $query = "insert into user (";
            $query .= " name_in_full,email,password,role_id,active";
            $query .= ") values( ";
            $query .= " '{$user->getNameInFull()}','{$user->getEmail()}','{$user->getPassword()}','3','{$user->getActive()}'";
            $query .= ")";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
            colse_connection($connection);
            if ( $user->getActive() == 0 ){
                $message = \Swift_Message::newInstance();

                $message ->setSubject('TraTRIP | Signup | Verification');
                $message ->setFrom('malshapavan@gmail.com');

                $message ->setTo($user->getEmail());
                
                $body_plain_txt = 'Hello ';
                $body_plain_txt .= $user->getNameInFull();
                $body_plain_txt .= ' , 
Thank you for signing up at TraTRIP.
Your account has been created, you can login with the following Email and the password you entered during sign up after you have activated your account by pressing the url below.

Email: ';
                $body_plain_txt .= $user->getEmail();
                $body_plain_txt .= '

Please click this link to activate your account:
http://localhost/Asem5/app1/web/app_dev.php/user/verify/'.$user->getEmail().'/'.$user->getPassword();

                $message ->setBody($body_plain_txt);
                         
                $this->get('mailer')->send($message);

                $confirmmessage = 'Hi ';
                $confirmmessage .= $user->getNameInFull();
                $confirmmessage .= '    
Thank you for signing up at TraTRIP. 

Your account is created and must be activated before you can use it. 

To activate the account go to your email account.';

                return $this->render('1mainpage.html.twig',array('confirmmessage' => $confirmmessage));
            }else{
                return $this->render('App1ExampleBundle:Default:index.html.twig');
            }
            return $this->redirectToRoute('user_login', array('msg' => 'Your Account is activated.' ));
        }

        return $this->render('user/signup.html.twig');
    }

    /**
     *
     * @Route("/resetpw/{id}", name="user_resetpw")
     * @Method({"GET", "POST"})
     */
    public function ResetpwAction(Request $request, $id)
    {
        $connection = connect();
        $query = "SELECT password from user WHERE id = '{$id}' LIMIT 1";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        $row=mysqli_fetch_assoc($result);
        $pw_db=sha1($row['password']);

        if ( $request->getMethod()=='POST'){
            $connection = connect();
            $password = sha1($request->get('password'));
            $query = "UPDATE user SET ";
            $query .= "password = '{$password}' WHERE id = '{$id}' LIMIT 1";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
            colse_connection($connection);
          
            return $this->redirectToRoute('user_edit', array('id' => $id ));
        }

        return $this->render('user/passwordReset.html.twig', array('pw_db' => $pw_db ));
    }

    /**
     * Verify
     *
     * @Route("/verify/{email}/{password}", name="user_verify")
     * @Method({"GET", "POST"})
     */
    public function Verify($email,$password)
    {
        $connection = connect();
        $query = "UPDATE user SET ";
        $query .= "active = '1' WHERE email = '{$email}' and password='{$password}' LIMIT 1";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        colse_connection($connection);
        return $this->redirectToRoute('user_login', array('msg' => 'Your Account is activated.' ));
        
    }
    /**
     * HotelRequest
     *
     * @Route("/hotelrequest", name="user_hotelrequest")
     * @Method({"GET", "POST"})
     */
    public function HotelRequestAction(Request $request)
    {
        if ( $request->getMethod()=='POST'){
            $connection = connect();

            $query = "insert into hotel_request (name_in_full,email,telephone,role_id,active) values( ";
            $query .= " '{$request->get('name')}','{$request->get('email')}','{$request->get('telephone')}','2','0')";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
            colse_connection($connection);
            $confirmmessage = 'Hi ';
            $confirmmessage .= $request->get('name');
            $confirmmessage .= '    
Thank you for adding new hotel request at TraTRIP. 

We will call you and notify when to activate your user account. 

To activate the account go to your email account.';

                return $this->render('1mainpage.html.twig',array('confirmmessage' => $confirmmessage));
        }

        return $this->render('user/hotelrequest.html.twig');
    }

    /**
     *
     * @Route("/deleteH/{email}", name="deleteH")
     * @Method({"GET", "POST"})
     */
    public function deleteHotelReqAction($email)
    {
        deleteHotelUser($email);
        return $this->render('App1ExampleBundle:Default:dashboard.html.twig',array(
            'hotelrequests' => getAllHotelRequests(),
            'comments' => getAllComments(),
        ));
    }

    /**
     *
     * @Route("/acceptH/{email}", name="acceptH")
     * @Method({"GET", "POST"})
     */
    public function acceptHotelReqAction($email)
    {
        activateHotelUser($email);
        $message = \Swift_Message::newInstance();

        $message ->setSubject('TraTRIP | Signup | Verification');
        $message ->setFrom('malshapavan@gmail.com');

        $message ->setTo($email);
        
        $body_plain_txt = 'Hello ';
        $body_plain_txt .= ' , 
Thank you for signing up at TraTRIP.
We added you as a hotel admin and your account has been created, you can login with the following Email.

Email: ';
        $body_plain_txt .= $email;
        $body_plain_txt .= '

Please click this link to add password to your account:
http://localhost/Asem5/app1/web/app_dev.php/user/HresetPW/'.$email;

        $message ->setBody($body_plain_txt);
                 
        $this->get('mailer')->send($message);

        return $this->render('App1ExampleBundle:Default:dashboard.html.twig',array(
            'hotelrequests' => getAllHotelRequests(),
            'comments' => getAllComments(),
        ));
    }

        /**
     *
     * @Route("/HresetPW/{email}", name="HresetPW")
     * @Method({"GET", "POST"})
     */
    public function HresetPWAction(Request $request,$email)
    {
        if ( $request->getMethod()=='POST'){
            $password = sha1($request->get('password'));
            $connection = connect();
            $query = "UPDATE user SET ";
            $query .= "password = '{$password}' WHERE email = '{$email}' LIMIT 1";
            $result = mysqli_query($connection,$query);
            confirm_query($result);
            colse_connection($connection);
            return $this->redirectToRoute('user_login', array('msg' => 'Your Account is activated.' ));
        }
        return $this->render('user/hotelUserSignup.html.twig', array(
            'email' => $email,
        ));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('App1\ExampleBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('App1\ExampleBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

function send_email(User $user){
         
    $message = \Swift_Message::newInstance();

    $message ->setSubject('TraTRIP | Signup | Verification');
    $message ->setFrom('greetingcard.shop.sl@gmail.com');

    $message ->setTo($user->getEmail());
    
    $body_plain_txt = '
     
    Thanks for signing up!
    Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
     
    ------------------------
    Email: {$user->getEmail()}
    Password: {$user->getPassword()}
    ------------------------
     
    Please click this link to activate your account:
    http://www.yourwebsite.com/verify.php?email= {$user->getEmail()} 
     
    '; // Our message above including the link

    $message ->setBody($body_plain_txt);
    $result = $mailer->send($message);
     
    return $result;
     
}

function searchEmail($uploadedURL,$id){
    
}

function getAllHotelRequests(){     
    $connection = connect();
    $query = "SELECT * from hotel_request WHERE active = '0' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    $hotelrequests = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push ($hotelrequests, $row);
    }
    return $hotelrequests;
}

function getAllComments(){     
    $connection = connect();
    $query = "SELECT * from feedback WHERE seen = '0' ";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    $comments = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push ($comments, $row);
    }
    return $comments;
}

function activateHotelUser($email){
    $connection = connect();
    $query = "UPDATE user SET ";
    $query .= "active = '1' WHERE email = '{$email}' LIMIT 1";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
}

function deleteHotelUser($email){
    $connection = connect();
    $query = "DELETE FROM user WHERE email = '{$email}' LIMIT 1";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
}