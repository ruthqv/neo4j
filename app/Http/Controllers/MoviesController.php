<?php

namespace App\Http\Controllers;

use App\Entities\Movie;
use GraphAware\Neo4j\OGM\EntityManager;
use GraphAware\Neo4j\OGM\Repository\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MoviesController extends Controller
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
    public function store(Request $request)
    {
        // $input = json_decode($request->getContent(), true);
        $movie = new Movie();
        $movie->setTitle($request->input('title'));
        $movie->setTagline($request->input('tagline'));
        $movie->setRelease($request->input('release'));

        $this->entityManager->persist($movie);
        $this->entityManager->flush();
        return (new Response())->setContent($movie->getId());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (new Response())->setContent($this->movieRepository->findOneById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $input = json_decode($request->getContent(), true);
        $movie = $this->movieRepository->findOneById($id);
        if(!empty($request->input('title'))) {
            $movie->setTitle($request->input('title'));
        }
        if(!empty($request->input('tagline'))) {
            $movie->setTagline($request->input('tagline'));
        }
        if(!empty($request->input('release'))) {
            $movie->setRelease($request->input('release'));
        }        
        $this->entityManager->persist($movie);
        $this->entityManager->flush();
        return (new Response())->setContent($movie->getId());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->entityManager->detach($this->movieRepository->findOneById($id));
        $this->entityManager->remove($this->movieRepository->findOneById($id));
        $this->entityManager->flush();
    }
}
