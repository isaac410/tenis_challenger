<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Player;
use App\Dto\PlayerInputDto;
use App\Service\PlayerService;

#[OA\Tag(name: 'player')]
class PlayerController extends AbstractController {

    #[OA\Response(
        response: 201,
        description: 'Returns the new player',
        content: new Model(type: Player::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: PlayerInputDto::class)
    )]
    #[Route('/player', name: 'create_player', methods: ['POST'])]
    public function create(Request $request, PlayerService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $player = $service->create($body);
        return $this->json([
            'message' => 'player created!',
            'data' => $player,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the players found',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: Player::class))
        )
    )]
    #[Route('/player', name: 'list_player', methods: ['GET'])]
    public function list(PlayerService $playerService): JsonResponse {
        return $this->json([
            'message' => 'players listed!',
            'data' => $playerService->list(),
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the player found',
        content: new Model(type: Player::class)
    )]
    #[Route('/player/{id}', name: 'get_player', methods: ['GET'])]
    public function get(int $id, PlayerService $playerService): JsonResponse {
        $player = $playerService->findById($id);
        return $this->json([
            'message' => 'player founded!',
            'data' => $player,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the updated player',
        content: new Model(type: Player::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: PlayerInputDto::class)
    )]
    #[Route('/player/{id}', name: 'update_player', methods: ['PUT'])]
    public function update(Request $request, int $id, PlayerService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $player = $service->updateById($id, $body);
        return $this->json([
            'message' => 'player updated!',
            'data' => $player,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns void',
    )]
    #[Route('/player/{id}', name: 'delete_player', methods: ['delete'])]
    public function delete(int $id, PlayerService $service): JsonResponse {
        $service->deleteById($id);
        return $this->json([
            'message' => 'player delete!',
            'data' => null,
        ]);
    }
}
