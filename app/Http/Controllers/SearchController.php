<?php

namespace App\Http\Controllers;


use App\Entities\Movie;
use App\Entities\Person;

use GraphAware\Neo4j\OGM\EntityManager;
use GraphAware\Neo4j\OGM\Repository\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GraphAware\Neo4j\OGM\Annotations as OGM;

class SearchController extends Controller
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BaseRepository
     */
    private $movieRepository;

    /**
     * MovieController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->movieRepository = $this->entityManager->getRepository(Movie::class);
        $this->personRepository = $this->entityManager->getRepository(Person::class);


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return (new Response())->setContent($this->movieRepository->findAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

		$query = $this->entityManager->createQuery('MATCH (n:Person {name:"Christina Ricci"})-[:ACTED_IN]->(movie) RETURN movie.title;');
		$query->addEntityMapping('n', Person::class);
		$result = $query->execute();

        return (new Response())->setContent($result);
    }
}
