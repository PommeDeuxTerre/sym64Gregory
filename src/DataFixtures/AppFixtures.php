<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
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

        // posts
        for ($i=1;$i<=100;$i++){
            $post = new Post();
            $rand_user = $users[array_rand($users)];

            $post->setUser($rand_user);
            $post->setPostDateCreated(new \dateTime('now - 30 days'));
            $post->setPostPublished(rand(0, 1) == 1);
            if ($post->isPostPublished()){
                $post->setPostDatePublished(new \dateTime('now - ' . mt_rand(6, 25) . ' days'));
            }
            $post->setPostTitle(ucfirst($faker->words(mt_rand(2,5),true)));
            $post->setPostDescription($faker->paragraphs(mt_rand(3,6), true));

            $posts[] = $post;

            $manager->persist($post);
        }
        $published_post = array_values(array_filter($posts, fn(Post $post)=>$post->isPostPublished()));

        // comments
        for ($i=1;$i<=sizeof($published_post)*5;$i++){
            $comment = new Comment();
            $rand_user = $users[array_rand($users)];
            $rand_post = $published_post[array_rand($published_post)];

            $comment->setUser($rand_user);
            $comment->setPost($rand_post);
            $comment->setCommentPublished(true);
            $comment->setCommentMessage($faker->paragraphs(1, true));
            $comment->setCommentDateCreated(new \dateTime('now - ' . mt_rand(0, 4) . ' days'));
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
