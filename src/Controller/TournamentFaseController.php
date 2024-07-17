<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\TournamentFase;
use App\Dto\TournamentFaseInputDto;
use App\Service\TournamentFaseService;

#[OA\Tag(name: 'tournament fase', description: 'crud on tournaments')]
class TournamentFaseController extends AbstractController {

    #[OA\Response(
        response: 201,
        description: 'Returns the new tournament fase',
        content: new Model(type: TournamentFase::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: TournamentFaseInputDto::class)
    )]
    #[Route('/tournament-fase', name: 'create_tournament_fase', methods: ['POST'])]
    public function create(Request $request, TournamentFaseService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $tournamentFase = $service->create($body);
        return $this->json([
            'message' => 'tournament fase created!',
            'data' => $tournamentFase,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the tournaments found',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: TournamentFase::class))
        )
    )]
    #[Route('/tournament-fase', name: 'list_tournament_fase', methods: ['GET'])]
    public function list(TournamentFaseService $service): JsonResponse {
        return $this->json([
            'message' => 'tournament fases listed!',
            'data' => $service->list(),
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the tournament found',
        content: new Model(type: TournamentFase::class)
    )]
    #[Route('/tournament-fase/{id}', name: 'get_tournament_fase', methods: ['GET'])]
    public function get(int $id, TournamentFaseService $service): JsonResponse {
        $tournamentFase = $service->findById($id);
        return $this->json([
            'message' => 'tournament fase founded!',
            'data' => $tournamentFase,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the updated tournament fase',
        content: new Model(type: TournamentFase::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: TournamentFaseInputDto::class)
    )]
    #[Route('/tournament-fase/{id}', name: 'update_tournament_fase', methods: ['PUT'])]
    public function update(Request $request, int $id, TournamentFaseService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $tournamentFase = $service->updateById($id, $body);
        return $this->json([
            'message' => 'tournament fase updated!',
            'data' => $tournamentFase,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns void',
    )]
    #[Route('/tournament-fase/{id}', name: 'delete_tournament_fase', methods: ['delete'])]
    public function delete(int $id, TournamentFaseService $service): JsonResponse {
        $service->deleteById($id);
        return $this->json([
            'message' => 'tournament fase delete!',
            'data' => null,
        ]);
    }
}
