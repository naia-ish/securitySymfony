<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em)
    {
        die('todo');

        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }

    /**
     * @Route("/admin/article/{id}/edit")
     * @isGranted("MANAGE", subject="article")
     */
    public function  edit(Article $article)
    {
        // 1º podremos modificar si ROLE_ADMIN_ARTICLE or the author of that article
//        if (!$this->isGranted('MANAGE', $article)) {
//            throw $this->createAccessDeniedException('No access!!');// El mensaje solo se muestra a desarrolladores
//        }

//       2º $this->denyAccessUnlessGranted('MANAGE', $article); EN VEZ DE ESTO
//        SE PUEDE PONER @isGranted("MANAGE", subject="article") SUBJECT HACE REFERENCIA A
//        public function  edit(Article $article) $article
//       SI NO TENEMOS EL SUBJECT UTILIZAREMOS $this->denyAccessUnlessGranted('MANAGE', $article)


        dd($article);
    }
}
