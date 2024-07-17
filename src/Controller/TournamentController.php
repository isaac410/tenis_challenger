<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Tournament;
use App\Dto\TournamentInputDto;
use App\Dto\TournamentDetailDto;
use App\Service\TournamentService;

#[OA\Tag(name: 'tournament', description: 'crud on tournaments')]
class TournamentController extends AbstractController {

    #[OA\Response(
        response: 201,
        description: 'Returns the new tournament',
        content: new Model(type: Tournament::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: TournamentInputDto::class)
    )]
    #[Route('/tournament', name: 'create_tournament', methods: ['POST'])]
    public function create(Request $request, TournamentService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $tournament = $service->create($body);
        return $this->json([
            'message' => 'tournament created!',
            'data' => $tournament,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the tournaments found',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: Tournament::class))
        )
    )]
    #[OA\Parameter(
        name: "start_date",
        in: "query",
        required: false,
        description: "Start date for filtering tournaments",
        schema: new OA\Schema(type: "string", format: "date")
    )]
    #[OA\Parameter(
        name: "end_date",
        in: "query",
        required: false,
        description: "End date for filtering tournaments",
        schema: new OA\Schema(type: "string", format: "date")
    )]
    #[OA\Parameter(
        name: "gender",
        in: "query",
        required: false,
        description: "Gender for filtering tournaments",
        schema: new OA\Schema(type: "string", enum: ["male", "female"])
    )]
    #[Route('/tournament', name: 'list_tournament', methods: ['GET'])]
    public function list(Request $request, TournamentService $tournamentService): JsonResponse {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $gender = $request->query->get('gender');
        return $this->json([
            'message' => 'tournaments listed!',
            'data' => $tournamentService->list($startDate, $endDate, $gender),
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'returns the tournament found',
        content: new Model(type: TournamentDetailDto::class)
    )]
    #[Route('/tournament/{id}', name: 'get_tournament', methods: ['GET'])]
    public function get(int $id, TournamentService $tournamentService): JsonResponse {
        $tournament = $tournamentService->findById($id);
        return $this->json([
            'message' => 'tournament founded!',
            'data' => $tournament,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the updated tournament',
        content: new Model(type: Tournament::class)
    )]
    #[OA\RequestBody(
        content: new Model(type: TournamentInputDto::class)
    )]
    #[Route('/tournament/{id}', name: 'update_tournament', methods: ['PUT'])]
    public function update(Request $request, int $id, TournamentService $service): JsonResponse {
        $body = json_decode($request->getContent(), true);
        $tournament = $service->updateById($id, $body);
        return $this->json([
            'message' => 'tournament updated!',
            'data' => $tournament,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns void',
    )]
    #[Route('/tournament/{id}', name: 'delete_tournament', methods: ['delete'])]
    public function delete(int $id, TournamentService $service): JsonResponse {
        $service->deleteById($id);
        return $this->json([
            'message' => 'tournament delete!',
            'data' => null,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the finished tournament datail',
        content: new Model(type: Tournament::class)
    )]
    #[Route('/tournament/{id}/simulate', name: 'simulate_tournament', methods: ['GET'])]
    public function simulate(int $id, TournamentService $service): JsonResponse {

        $result = $service->simulateTournament((int) $id);

        return $this->json([
            'message' => 'Tournament simulated successfully!',
            'data' => $result
        ]);
    }
}
