<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, Gamme>
     */
    #[ORM\OneToMany(targetEntity: Gamme::class, mappedBy: 'referent')]
    private Collection $gammes;

    /**
     * @var Collection<int, QualifPoste>
     */
    #[ORM\OneToMany(targetEntity: QualifPoste::class, mappedBy: 'usr', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $qualifPostes;

    /**
     * @var Collection<int, Realisation>
     */
    #[ORM\OneToMany(targetEntity: Realisation::class, mappedBy: 'ouvrier')]
    private Collection $realisations;

    public function __construct()
    {
        $this->gammes = new ArrayCollection();
        $this->qualifPostes = new ArrayCollection();
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Gamme>
     */
    public function getGammes(): Collection
    {
        return $this->gammes;
    }

    public function addGamme(Gamme $gamme): static
    {
        if (!$this->gammes->contains($gamme)) {
            $this->gammes->add($gamme);
            $gamme->setReferent($this);
        }

        return $this;
    }

    public function removeGamme(Gamme $gamme): static
    {
        if ($this->gammes->removeElement($gamme)) {
            // set the owning side to null (unless already changed)
            if ($gamme->getReferent() === $this) {
                $gamme->setReferent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QualifPoste>
     */
    public function getQualifPostes(): Collection
    {
        return $this->qualifPostes;
    }

    public function addQualifPoste(QualifPoste $qualifPoste): static
    {
        if (!$this->qualifPostes->contains($qualifPoste)) {
            $this->qualifPostes->add($qualifPoste);
            $qualifPoste->setUsr($this);
        }

        return $this;
    }

    public function removeQualifPoste(QualifPoste $qualifPoste): static
    {
        if ($this->qualifPostes->removeElement($qualifPoste)) {
            // set the owning side to null (unless already changed)
            if ($qualifPoste->getUsr() === $this) {
                $qualifPoste->setUsr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Realisation>
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisation $realisation): static
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations->add($realisation);
            $realisation->setOuvrier($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): static
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getOuvrier() === $this) {
                $realisation->setOuvrier(null);
            }
        }

        return $this;
    }
    public function isQualifiedForPostes($poste): bool
    {
        $qualifs = $this->getQualifPostes();

        if($qualifs != []){
            return true;
        }

        else{
            return false;
        }
    }
}
