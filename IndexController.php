<?php
namespace App\Controller;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class IndexController extends AbstractController
{
    private $entityManager;
    


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        
    }

        
    #[Route('/', name: 'tache_list')]
    public function home()
    {
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles
        $articles= $this->entityManager->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig',['taches'=> $taches]);
    }

    #[Route('/taches/create', name: 'new_tache',methods: ["POST","GET"])]
    public function new(Request $request,FormFactoryInterface $formFactory)
    {
    $tache = new Tache();
    $form =  $this->createFormBuilder($tache)->add('nom', TextType::class)->add('prix', TextType::class)->add('save', SubmitType::class, array('label' => 'Créer'))->getForm();
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $tache = $form->getData();
        //$this->entityManager->getDoctrine()->getManager();
        $this->entityManager->persist($tache);
        $this->entityManager->flush();
        return $this->redirectToRoute('tache_list');
    }
        return $this->render('taches/new.html.twig',['form' => $form->createView()]);
    }


    #[Route('/tache/edit/{id}', name: 'edit_tache',methods: ["POST","GET"])]
    public function edit(Request $request, $id,FormFactoryInterface $formFactory) {
        $tache = new tache();
        $tache = $this->entityManager->getRepository(tache::class)->find($id);
    
        $form = $this->createFormBuilder($tache)
        ->add('nom', TextType::class)
        ->add('prix', TextType::class)
        ->add('save', SubmitType::class, array('label' => 'Modifier'))->getForm();
    
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->flush();
    
        return $this->redirectToRoute('tache_list');
        }  
        return $this->render('taches/edit.html.twig', ['form' => $form->createView()]);
    }
        

  
    #[Route('/tache/delete/{id}', name: 'delete_tache')]
    public function delete(Request $request, $id) {
        $tache = $this->entityManager->getRepository(tache::class)->find($id);
    
        //$entityManager = $this->getDoctrine()->getManager();
        $this->entityManager->remove($tache);
        $this->entityManager->flush();
    
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('tache_list');
    }
   
    
    #[Route('/tache/{id}', name: 'tache_show')]
    public function show($id) {
        $tache = $this->entityManager->getRepository(tache::class)->find($id);
        return $this->render('taches/show.html.twig',array('tache' => $tache));
    }
    


    // #[Route('/tache/save', name: 'save')]
    // public function save() {
    //     $tache = new tache();
    //     $tache->setNom('tache 3');
    //     $tache->setPrix(00);
    //     $this->entityManager->persist($tache);
    //     $this->entityManager->flush();
    //     return new Response('tache enregisté avec id '.$tache->getId());
    // }
    



 
}

?>