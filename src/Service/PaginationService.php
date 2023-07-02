<?php

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;

class PaginationService
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginate($results, $page, $pageSize)
    {
        return $this->paginator->paginate(
            $results,
            $page,
            $pageSize
        );
    }
}
