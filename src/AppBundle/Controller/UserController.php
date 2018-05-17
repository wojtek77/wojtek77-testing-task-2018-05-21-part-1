<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;

class UserController extends FOSRestController
{

    /**
     * @Rest\Get("/user/")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/user/")
     */
    public function postAction(Request $request)
    {
        $data = new User;
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        if (empty($firstname) || empty($lastname)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setFirstname($firstname);
        $data->setLastname($lastname);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("User Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateAction($id, Request $request)
    {
        $data = new User;
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($firstname) && !empty($lastname)) {
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $em->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        } elseif (empty($firstname) && !empty($lastname)) {
            $user->setLastname($lastname);
            $em->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        } elseif (!empty($firstname) && empty($lastname)) {
            $user->setFirstname($firstname);
            $em->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        } else
            return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteAction($id)
    {
        $data = new User;
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($user);
            $em->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
