<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $crm;

    /**
     * @ORM\Column(type="string")
     */
    private string $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Especialidade $especialidade;

    function getCrm(): int
    {
        return $this->crm;
    }

    function setCrm($crm): self
    {
        $this->crm = $crm;
        return $this;
    }

    function getNome(): string
    {
        return $this->nome;
    }

    function setNome($nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function extract()
    {
        return [
            "id" => $this->id,
            "crm" => $this->crm,
            "nome" => $this->nome,
            "especialidade" => $this->especialidade->getDescricao()
        ];
    }

    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }
}
