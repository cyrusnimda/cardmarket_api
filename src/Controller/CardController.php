<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Card;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/cards')]
class CardController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->cardRepository = $entityManager->getRepository(Card::class);
    }

    #[Route('/{id}', name: 'find_card', requirements: ['id' => '\d+'])]
    public function find(int $id): JsonResponse
    {
        $card = $this->cardRepository->find($id);
        $response  = [
            'status' => 'OK',
            'card' => $card
        ];
        $json = $this->serializer->serialize($response, 'json');

        return JsonResponse::fromJsonString($json);
    }
 
    #[Route('/random', name: 'get_random_cards')]
    public function getRandomCards(): JsonResponse
    {
        $totalCards = $this->cardRepository->getTotalCards();

        $randomNumbers = $this->getFourRandomNumbers($totalCards);
        $cards = $this->cardRepository->findById($randomNumbers);

        $response  = [
            'status' => 'OK',
            'cards' => $cards
        ];
        $json = $this->serializer->serialize($response, 'json');

        return JsonResponse::fromJsonString($json);
    }

    #[Route('/search/{name}', name: 'search_cards')]
    public function searchCards(string $name): JsonResponse
    {
        $cards = $this->cardRepository->searchByName($name);

        $response  =[
            'status' => 'OK',
            'cards' => $cards
        ];
        $json = $this->serializer->serialize($response, 'json');

        return JsonResponse::fromJsonString($json);
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
