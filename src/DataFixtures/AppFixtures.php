<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\Section;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Faker\Factory AS Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->hasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr-FR');
        // users
        $user = new User();
        $user->setUsername("admin");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->hasher->hashPassword($user, 'admin'));
        $users[] = $user;

        $manager->persist($user);

        for($i=1;$i<=10;$i++){
            $user = new User();
            $user->setUsername($faker->name());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, "password"));
            $users[] = $user;

            $manager->persist($user);
        }

        // tags
        for ($i=1;$i<=20;$i++){
            $tag = new Tag();
            $tag->setTagName(ucfirst($faker->shuffle($faker->word()).$faker->shuffle($faker->word()).$faker->shuffle($faker->word())));
            $tags[] = $tag;
            $manager->persist($tag);
        }

        // sections
        for ($i=1;$i<=20;$i++){
            $section = new Section();

            $section->setSectionTitle(ucfirst($faker->words(rand(1, 2),true)));
            $section->setSectionDescription($faker->paragraphs(1,true));
            $sections[] = $section;
            $manager->persist($section);
        }

        // articles
        for ($i=1;$i<=100;$i++){
            $article = new Article();
            $rand_user = $users[array_rand($users)];

            $article->setUser($rand_user);
            $article->setArticleDateCreated(new \dateTime('now - 30 days'));
            $article->setArticlePublished(rand(0, 1) == 1);
            if ($article->isArticlePublished()){
                $article->setArticleDatePublished(new \dateTime('now - ' . mt_rand(6, 25) . ' days'));
            }
            $article->setTitle(ucfirst($faker->words(mt_rand(2,5),true)));
            $article->setArticleDescription($faker->paragraphs(mt_rand(3,6), true));


            // tags
            for ($j=1;$j<=5;$j++){
                $rand_tag = $tags[array_rand($tags)];
                $article->addTag($rand_tag);
            }

            // sections
            for ($j=1;$j<=5;$j++){
                $rand_section = $sections[array_rand($sections)];
                $article->addSection($rand_section);
            }

            $articles[] = $article;

            $manager->persist($article);
        }

        $published_articles = array_values(array_filter($articles, fn(Article $article)=>$article->isArticlePublished()));

        // comments
        for ($i=1;$i<=sizeof($published_articles)*5;$i++){
            $comment = new Comment();
            $rand_user = $users[array_rand($users)];
            $rand_article = $published_articles[array_rand($published_articles)];

            $comment->setUser($rand_user);
            $comment->setArticle($rand_article);
            $comment->setCommentPublished(true);
            $comment->setCommentMessage($faker->paragraphs(1, true));
            $comment->setCommentDateCreated(new \dateTime('now - ' . mt_rand(0, 4) . ' days'));
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
