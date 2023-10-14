<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Card;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/cards')]
class CardController extends AbstractController
{

    #[Route('/{id}', name: 'find_card', requirements: ['id' => '\d+'])]
    public function find(int $id): JsonResponse
    {
        return $this->json([
            'status' => 'OK',
            'card' => [
                "id" => $id,
                "name" => "Black lotus",
                "edition" => "A",
            ]
        ]);
    }
    

    #[Route('/random', name: 'get_random_cards')]
    public function getRandomCards(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select($qb->expr()->count('card.id'));
        $qb->from('App:Card','card');
        $totalCards = $qb->getQuery()->getSingleScalarResult();

        $randomNumbers = $this->getFourRandomNumbers($totalCards);
        $cards = $entityManager->getRepository(Card::class)->findById($randomNumbers);
        //dump($cards);

        return $this->json([
            'status' => 'OK',
            'cards' => $serializer->serialize($cards, 'json')
        ]);
    }

    private function getFourRandomNumbers(int $totalCards) : array
    {
        $randomNumbers = [];
        while(count($randomNumbers) < 4){
            $number = rand(1, $totalCards);
            if(!in_array($number, $randomNumbers)){
                $randomNumbers[] = $number;
            }
        }
        return $randomNumbers;
    }

    


}
