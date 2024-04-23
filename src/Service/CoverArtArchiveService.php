<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoverArtArchiveService
{
    private HttpClientInterface $client;
    private string $baseUrl = 'http://coverartarchive.org';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCoverArt(string $mbid): ?array
    {
        $response = $this->client->request('GET', $this->baseUrl . '/release/' . $mbid);

        if ($response->getStatusCode() === 200) {
            $content = $response->toArray();
            return $content['images'] ?? null;
        }

        return null;
    }
}