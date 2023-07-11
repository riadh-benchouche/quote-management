<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse mail est déjà utilisée')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    private ?string $plainPassword;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: 'Cette adresse mail "{{ value }}" n\'est pas valide.')]
    #[Assert\Unique(message: 'L\'adresse e-mail est déjà utilisée.')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le champ mot de passe ne peut pas être vide.')]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'avatarName')]
    private ?File $avatarFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatarName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File|null $avatarFile
     * @return User
     */
    public function setAvatarFile(?File $avatarFile): User
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    /**
     * @param string|null $avatarName
     * @return User
     */
    public function setAvatarName(?string $avatarName): User
    {
        $this->avatarName = $avatarName;
        return $this;
    }

    // public function __serialize(): array
    // {
    //     return [
    //         'id' => $this->id,
    //         'email' => $this->email,
    //         'password' => $this->password,
    //     ];
    // }
    // public function __unserialize(array $serialized)
    // {
    //     $this->id = $serialized['id'];
    //     $this->email = $serialized['email'];
    //     $this->password = $serialized['password'];
    //     return $this;
    // }

    public function setPlainPassword(?string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
        // if ($plainPassword) {
        //     $this->password = password_hash($plainPassword, PASSWORD_BCRYPT);
        // }
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }


    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
