<?php

namespace App\Controller;

use App\Service\CoverArtArchiveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class CoverArtArchiveController extends AbstractController
{
    private mixed $coverArtService;
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
            return $this->json(['error' => 'Cover art not found'], Response::HTTP_NOT_FOUND);
        }

        $coverArts = [];
        foreach ($coverArt as $c) {
            $coverArts[] = $c['thumbnails']['1200'];
        }
        //dd($coverArts);

        return $this->render('cover_art_archive/index.html.twig', [
            'title' => 'Cover Art Archive',
            'images' => $coverArts
        ]);
        //return $this->json($coverArt);
    }
}