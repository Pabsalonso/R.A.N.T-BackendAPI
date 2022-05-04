<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StepRepository::class)
 */
class Step
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $stepNo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgb64;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="steps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stepText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stepImg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStepNo(): ?int
    {
        return $this->stepNo;
    }

    public function setStepNo(int $stepNo): self
    {
        $this->stepNo = $stepNo;

        return $this;
    }

    public function getImgb64(): ?string
    {
        return $this->imgb64;
    }

    public function setImgb64(?string $imgb64): self
    {
        $this->imgb64 = $imgb64;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getStepText(): ?string
    {
        return $this->stepText;
    }

    public function setStepText(string $stepText): self
    {
        $this->stepText = $stepText;

        return $this;
    }

    public function getStepImg(): ?string
    {
        return $this->stepImg;
    }

    public function setStepImg(?string $stepImg): self
    {
        $this->stepImg = $stepImg;

        return $this;
    }
}
