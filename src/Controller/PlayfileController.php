<?php

namespace App\Controller;

use App\Entity\Playfile;
use App\Service\Youtube;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayfileController extends Controller
{
    /**
     * @Route("/admin/playfile", name="playfile")
     */
    public function indexPlayfile()
    {
        return $this->render('playfile/index.html.twig', [
            'playfiles' => $this->getDoctrine()->getManager()->getRepository(Playfile::class)->findAll()
        ]);
    }

    public function listPublicPlayfiles(){
        $playfiles = $this->getDoctrine()->getRepository(Playfile::class)->findAll();
        return $this->render('playfile/list.html.twig', [
            'playfiles' => $playfiles
        ]);
    }

    /**
     * @Route("/admin/playfile/new", name="playfile_new")
     */
    public function newPlayfile(Request $request)
    {
       $playfile = new Playfile();

       $form = $this->createFormBuilder($playfile)
           ->add('title', TextType::class)
           ->add('key', TextType::class)
           ->add('slug', TextType::class)
           ->add('description', TextType::class)
           ->add('content', TextType::class)
           ->add('save', SubmitType::class, array('label' => 'Créer'))
           ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($playfile);
             $entityManager->flush();

            return $this->redirectToRoute('playfile');
        }

       return $this->render('playfile/new.html.twig', [
          'form' => $form->createView()
       ]);
    }

    /**
     * @Route("/admin/playfile/edit/{id}", name="playfile_edit")
     */
    public function editPlayfile($id, Request $request)
    {
        /** @var Playfile $playfile */
        $playfile = $this->getDoctrine()->getRepository(Playfile::class)->find($id);

        $form = $this->createFormBuilder($playfile)
            ->add('title', TextType::class)
            ->add('key', TextType::class)
            ->add('slug', TextType::class)
            ->add('description', TextType::class)
            ->add('content', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Créer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('playfile');
        }

        return $this->render('playfile/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lets-play/{slug}.html", name="playfile_show")
     * @param $playfileId
     */
    public function showPlayfile( $slug ){
        /** @var Playfile $playfile */
        $playfile = $this->getDoctrine()->getManager()->getRepository(Playfile::class)
            ->findOneBy(['slug' => $slug]);
        return $this->render('playfile/show.html.twig', [
            'playfile' => $playfile
        ]);
    }

    /**
     * @Route("/admin/playfile/videosupdate/{id}", name="playfile_update")
     * @param $playfileId
     */
    public function updateVideos( $id ){
        /** @var Playfile $playfile */
        $playfile = $this->getDoctrine()->getManager()->getRepository(Playfile::class)
            ->find($id);

        /** @var Youtube $serviceYT */
        $serviceYT = $this->container->get(Youtube::class);

        $serviceYT->updatePlayfile($playfile->getKey());
        die($id);
    }


}
