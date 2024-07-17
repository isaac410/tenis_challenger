<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\MatchGame;
use App\Dto\MatchGameInputDto;
use App\Service\MatchGameService;

#[OA\Tag(name: 'match game', description: 'crud on match games')]
class MatchGameController extends AbstractController {

    #[OA\Response(
        response: 201,
        description: 'Returns the new match game',
        content: new Model(type: MatchGame::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: MatchGameInputDto::class)
    )]
    #[Route('/match-game', name: 'create_match_game', methods: ['POST'])]
    public function create(Request $request, MatchGameService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $matchGame = $service->create($body);
        return $this->json([
            'message' => 'match game created!',
            'data' => $matchGame,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the match games found',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: MatchGame::class))
        )
    )]
    #[Route('/match-game', name: 'list_match_game', methods: ['GET'])]
    public function list(MatchGameService $service): JsonResponse {
        return $this->json([
            'message' => 'match games listed!',
            'data' => $service->list(),
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the match game found',
        content: new Model(type: MatchGame::class)
    )]
    #[Route('/match-game/{id}', name: 'get_match_game', methods: ['GET'])]
    public function get(int $id, MatchGameService $service): JsonResponse {
        $matchGame = $service->findById($id);
        return $this->json([
            'message' => 'match game founded!',
            'data' => $matchGame,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the updated match game',
        content: new Model(type: MatchGame::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: MatchGameInputDto::class)
    )]
    #[Route('/match-game/{id}', name: 'update_match_game', methods: ['PUT'])]
    public function update(Request $request, int $id, MatchGameService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $matchGame = $service->updateById($id, $body);
        return $this->json([
            'message' => 'match game updated!',
            'data' => $matchGame,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns void',
    )]
    #[Route('/match-game/{id}', name: 'delete_match_game', methods: ['delete'])]
    public function delete(int $id, MatchGameService $service): JsonResponse {
        $service->deleteById($id);
        return $this->json([
            'message' => 'match game delete!',
            'data' => null,
        ]);
    }
}
