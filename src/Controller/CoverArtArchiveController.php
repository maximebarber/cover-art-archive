<?php

namespace App\Controller;

use App\Service\CoverArtArchiveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class CoverArtArchiveController extends AbstractController
{
    private CoverArtArchiveService $coverArtArchiveService;

    public function __construct(CoverArtArchiveService $coverArtArchiveService)
    {
        $this->coverArtArchiveService = $coverArtArchiveService;
    }

    #[Route('/cover-art/{mbid}', name: 'cover_art_archive')]
    public function getCoverArt(string $mbid): Response
    {
        try {
            $coverArt = $this->coverArtArchiveService->getCoverArt($mbid);
        } catch (ClientExceptionInterface $e) {
            return $this->json(['error' => $e], Response::HTTP_NOT_FOUND);
        }

        $images = array_map(function ($c) {
            return $c['thumbnails']['1200'] ?? null;
        }, $coverArt);

        return $this->render('cover_art_archive/index.html.twig', [
            'title' => 'Cover Art Archive',
            'images' => $images
        ]);
        //return $this->json($coverArt);
    }

    #[Route('home', name: 'home')]
    public function home(): Response
    {
        return $this->render('cover_art_archive/home.html.twig', [
            'title' => 'Cover Art Archive Home',
        ]);
    }
}