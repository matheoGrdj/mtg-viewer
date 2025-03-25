<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/card', name: 'api_card_')]
#[OA\Tag(name: 'Card', description: 'Routes for all about cards')]
class ApiCardController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {}

    #[Route('/all', name: 'List all cards', methods: ['GET'])]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Response(response: 200, description: 'List all cards')]
    public function cardAll(Request $request): Response
    {
        $this->logger->info('Listing cards with pagination');
        try {
            $page = max(1, $request->query->getInt('page', 1));
            $setCode = $request->query->get('setCode');

            if (!$setCode) {
                $paginator = $this->entityManager->getRepository(Card::class)->findAllPaginated($page);
            } else {
                $paginator = $this->entityManager->getRepository(Card::class)->findAllPaginatedBySetCode($page, 100, $setCode);
            }

            return $this->json([
                'items' => iterator_to_array($paginator),
                'total' => count($paginator),
                'page' => $page,
                'pages' => ceil(count($paginator) / 100)
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error listing cards: ' . $e->getMessage());
            return $this->json(['error' => 'Error listing cards'], 500);
        }
    }

    #[Route('/search', name: 'Search cards', methods: ['GET'])]
    #[OA\Parameter(name: 'name', description: 'Name of the card', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'setCode', description: 'Set code filter', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'Search cards')]
    #[OA\Response(response: 400, description: 'Name is required')]
    #[OA\Response(response: 500, description: 'Error searching cards')]
    public function cardSearch(Request $request)
    {
        $this->logger->info('Searching cards');
        $name = $request->query->get('name');
        $setCode = $request->query->get('setCode');
        try {
            if ($setCode && strlen($name) == 0) {
                $cards = $this->entityManager->getRepository(Card::class)->findBySetCode($setCode);
                return $this->json($cards);
            }
            if (strlen($name) >= 3) {
                // avant filtre sur le setCodes
                // $cards = $this->entityManager->getRepository(Card::class)->findByName($name);

                $cards = $this->entityManager->getRepository(Card::class)->findByNameAndSetCode($name, $setCode);
                return $this->json($cards);
            }
            return $this->json([]);
        } catch (\Exception $e) {
            $this->logger->error('Error searching cards: ' . $e->getMessage());
            return $this->json(['error' => 'Error searching cards'], 500);
        }
    }

    #[Route('/setcodes', name: 'List setcodes', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List all available set codes')]
    public function listSetCodes(): Response
    {
        $this->logger->info('Listing available set codes');
        try {
            $setCodes = $this->entityManager->getRepository(Card::class)
                ->createQueryBuilder('c')
                ->select('DISTINCT c.setCode')
                ->orderBy('c.setCode', 'ASC')
                ->getQuery()
                ->getResult();

            return $this->json(array_column($setCodes, 'setCode'));
        } catch (\Exception $e) {
            $this->logger->error('Error listing set codes: ' . $e->getMessage());
            return $this->json(['error' => 'Error listing set codes'], 500);
        }
    }

    #[Route('/{uuid}', name: 'Show card', methods: ['GET'])]
    #[OA\Parameter(name: 'uuid', description: 'UUID of the card', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Put(description: 'Get a card by UUID')]
    #[OA\Response(response: 200, description: 'Show card')]
    #[OA\Response(response: 404, description: 'Card not found')]
    public function cardShow(string $uuid): Response
    {
        $this->logger->info('Showing card ' . $uuid);
        try {
            $card = $this->entityManager->getRepository(Card::class)->findOneBy(['uuid' => $uuid]);
        } catch (\Exception $e) {
            $this->logger->error('Error showing card ' . $uuid . ': ' . $e->getMessage());
            return $this->json(['error' => 'Error showing card'], 500);
        }
        if (!$card) {
            return $this->json(['error' => 'Card not found'], 404);
        }
        $this->logger->info('Card ' . $uuid . ' shown');
        return $this->json($card);
    }
}
