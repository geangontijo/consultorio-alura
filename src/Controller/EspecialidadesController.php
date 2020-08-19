<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{

    private EntityManager $em;
    private EspecialidadeRepository $er;
    public function __construct(EntityManagerInterface $em, EspecialidadeRepository $er)
    {
        $this->em = $em;
        $this->er = $er;
    }
    /**
     * @Route("/especialidades", name="especialidades", methods="POST")
     */
    public function create(Request $request): Response
    {
        $body = $request->getContent();
        $json = json_decode($body);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($json->descricao);

        $this->em->persist($especialidade);
        $this->em->flush();

        return new JsonResponse($especialidade->extract());
    }

    /**
     * @Route("/especialidades", methods="GET")
     */
    public function index(): Response
    {
        $especialidadeList = [];
        foreach ($this->er->findAll() as $especialidade) {
            $especialidadeList[] = $especialidade->extract();
        }

        return new JsonResponse($especialidadeList);
    }

    /**
     * @Route("/especialidades/{id}", methods="GET")
     */
    public function indexParam(int $id)
    {
        $especialidade = $this->er->find($id);

        if (is_null($especialidade)) {

            return new Response('', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($especialidade->extract());
    }

    /**
     * @Route("/especialidades/{id}", methods="PUT")
     */
    public function edit(int $id, Request $request): Response
    {
        $body = $request->getContent();
        $json = json_decode($body);

        $especialidade = $this->er->find($id);
        $especialidade->setDescricao($json->descricao);

        $this->em->flush();

        return new JsonResponse($especialidade->extract());
    }

    /**
     * @Route("/especialidades/{id}", methods="DELETE")
     */
    public function destroy(int $id): Response
    {
        try {
            $especialidade = $this->em->getReference(Especialidade::class, $id);
            $this->em->remove($especialidade);
            $this->em->flush();
            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        } catch (Exception $err) {

            return new JsonResponse([
                'status' => false,
                'mensagem' => 'Não foi possivel remover essa especialidade, certifique-se de que ela não está sendo usada'
            ]);
        }
    }
}
