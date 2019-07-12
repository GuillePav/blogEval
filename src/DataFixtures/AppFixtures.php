<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        //Création de 30 objets BlogPost en BDD :
        for ($i=0; $i<30; $i++) {

            $cat = new Category();
            $cat->setName('Cat'.random_int(0,8));
            $manager->persist($cat);

            $blogPost = new BlogPost();
            $blogPost->setTitle('Titre n°'.$i);
            $blogPost->setSlug('Slug');
            $blogPost->setContent('Ceci est le contenu du post n° '.$i);
            $blogPost->setDate(12072019);
            $blogPost->setFeatured(true);

            $manager->persist($blogPost);
            $manager->flush();

    }

        //Création d'un objet dont le champ featured = 0 :
        $blogPost = new BlogPost();
        $blogPost->setTitle('Titre n°'.$i);
        $blogPost->setSlug('Slug');
        $blogPost->setContent('Ceci est le contenu du post n° '.$i);
        $blogPost->setDate(12072019);
        $blogPost->setFeatured(false);

        $manager->persist($blogPost);
        $manager->flush();




}
}
