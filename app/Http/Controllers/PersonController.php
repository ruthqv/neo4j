<?php

namespace App\Http\Controllers;

use App\Entities\Person;
use GraphAware\Neo4j\OGM\EntityManager;
use GraphAware\Neo4j\OGM\Repository\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BaseRepository
     */
    private $personRepository;

    /**
     * PersonController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->personRepository = $this->entityManager->getRepository(Person::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        return (new Response())->setContent($this->personRepository->findAll());
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
        $person = new Person();
        $person->setName($request->input('name'));
        $person->setBorn($request->input('born'));
        $this->entityManager->persist($person);
        $this->entityManager->flush();
        return (new Response())->setContent($person->getId());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = $this->personRepository->findOneById($id);
        echo sprintf("- %s is born in %d\n", $person->getName(), $person->getBorn());
        echo "  The movies in which he acted are : \n";
        foreach ($person->getMovies() as $movie) {
            echo sprintf("    -- %s\n", $movie->getTitle());
        }
        return (new Response())->setContent($this->personRepository->findOneById($id));
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
        $person = $this->personRepository->findOneById($id);
        if(!empty($request->input('name'))) {
            $person->setName($request->input('name'));
        }
        if(!empty($request->input('born'))) {
            $person->setBorn($request->input('born'));
        }
        $this->entityManager->persist($person);
        $this->entityManager->flush();
        return (new Response())->setContent($person->getId());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->entityManager->detach($this->personRepository->findOneById($id));
        $this->entityManager->remove($this->personRepository->findOneById($id));
        $this->entityManager->flush();
    }
}
