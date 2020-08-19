<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    private MedicoFactory $mf;
    /**
     * 
     * @var EntityManager
     */
    private $em;
    public function __construct(EntityManagerInterface $em, MedicoFactory $mf)
    {
        $this->em = $em;
        $this->mf = $mf;
    }

    /**
     * @Route("/medicos", methods="POST")
     */
    public function store(Request $request): Response
    {
        $body = $request->getContent();
        $data = json_decode($body);

        $medico = $this->mf->hidratarMedico($data);

        $this->em->persist($medico);
        $this->em->flush();

        return new JsonResponse($medico->extract());
    }

    /**
     * 
     * @Route("/medicos", methods="GET")
     * 
     */
    public function index(): Response
    {
        $repositorioMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicoList = $repositorioMedicos->findAll();
        $body = [];
        foreach ($medicoList  as $el) {
            $body[] = $el->extract();
        }

        return new JsonResponse($body);
    }

    /**
     *
     * @Route("/medicos/{id}", methods="GET")
     * 
     */
    public function indexParam(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        if (is_null($medico)) {

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse($medico->extract());
    }

    /**
     * 
     * @Route("/medicos/{id}", methods="PUT")
     *
     */
    public function storeParam(int $id, Request $request)
    {
        $id = $request->get('id');

        $body = $request->getContent();
        $data = json_decode($body);

        $medico = $this->mf->hidratarMedico($data);

        $medicoBuscado = $this->buscaMedico($id);

        if (is_null($medicoBuscado)) {

            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }

        $medicoBuscado->setNome($medico->getNome())->setCrm($medico->getCrm());
        $this->em->flush();

        return new JsonResponse($medicoBuscado->extract());
    }

    /**
     *
     * @Route("/medicos/{id}", methods="DELETE")
     * 
     */
    public function destroy(int $id)
    {
        $medico = $this->em->getReference(Medico::class, $id);
        $this->em->remove($medico);

        $this->em->flush();
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    private function buscaMedico(int $id)
    {
        $medicoRepository =  $this->getDoctrine()->getRepository(Medico::class);
        return $medicoRepository->find($id);
    }
}
