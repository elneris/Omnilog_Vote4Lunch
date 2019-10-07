<?php

namespace App\Twig;

use App\Entity\Vote;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlExtension extends AbstractExtension
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('voteUrl', [$this, 'formatUrlVote']),
        ];
    }

    public function formatUrlVote(Vote $vote): string
    {
        return  $this->url . 'vote/' . $vote->getUrl();
    }
}
