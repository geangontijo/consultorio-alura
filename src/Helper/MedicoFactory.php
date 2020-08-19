<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;
use stdClass;

class MedicoFactory
{
    private EspecialidadeRepository $er;
    public function __construct(EspecialidadeRepository $er)
    {
        $this->er = $er;
    }

    public function hidratarMedico(stdClass $dados): Medico
    {
        $especialidade = $this->er->find($dados->especialidadeId);
        $medico = new Medico();
        $medico->setNome($dados->nome);
        $medico->setCrm($dados->crm);
        $medico->setEspecialidade($especialidade);
        return $medico;
    }
}
