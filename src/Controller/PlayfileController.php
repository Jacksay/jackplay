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

    /**
     * @Route("/admin/playfile/new", name="playfile_new")
     */
    public function newPlayfile(Request $request)
    {
       $playfile = new Playfile();

       $form = $this->createFormBuilder($playfile)
           ->add('title', TextType::class)
           ->add('key', TextType::class)
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
     * @Route("/admin/playfile/videosupdate/{playfileId}", name="playfile_update")
     * @param $playfileId
     */
    public function updateVideos( $playfileId ){
        /** @var Playfile $playfile */
        $playfile = $this->getDoctrine()->getManager()->getRepository(Playfile::class)
            ->find($playfileId);

        /** @var Youtube $serviceYT */
        $serviceYT = $this->container->get(Youtube::class);

        $serviceYT->updatePlayfile($playfile->getKey());
        die($playfileId);
    }


}
