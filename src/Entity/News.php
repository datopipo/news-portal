<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use App\Constants\AppConstants;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: AppConstants::MESSAGES['news']['title_required'])]
    #[Assert\Length(
        min: AppConstants::NEWS_TITLE_MIN_LENGTH,
        max: AppConstants::NEWS_TITLE_MAX_LENGTH,
        minMessage: AppConstants::MESSAGES['news']['title_length'],
        maxMessage: AppConstants::MESSAGES['news']['title_length']
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: AppConstants::MESSAGES['news']['short_desc_required'])]
    #[Assert\Length(
        min: AppConstants::NEWS_SHORT_DESC_MIN_LENGTH,
        max: AppConstants::NEWS_SHORT_DESC_MAX_LENGTH,
        minMessage: AppConstants::MESSAGES['news']['short_desc_length'],
        maxMessage: AppConstants::MESSAGES['news']['short_desc_length']
    )]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: AppConstants::MESSAGES['news']['content_required'])]
    #[Assert\Length(
        min: AppConstants::NEWS_CONTENT_MIN_LENGTH,
        minMessage: AppConstants::MESSAGES['news']['content_min_length']
    )]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $insertDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Image(
        maxSize: AppConstants::MAX_FILE_SIZE,
        mimeTypes: AppConstants::ALLOWED_IMAGE_TYPES,
        mimeTypesMessage: AppConstants::MESSAGES['news']['image_type'],
        maxSizeMessage: AppConstants::MESSAGES['news']['image_size']
    )]
    private ?string $picture = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'news')]
    #[Assert\Count(
        min: 1,
        minMessage: AppConstants::MESSAGES['news']['category_required']
    )]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'news', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    #[Assert\PositiveOrZero]
    private int $viewCount = 0;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->insertDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getInsertDate(): ?\DateTimeInterface
    {
        return $this->insertDate;
    }

    public function setInsertDate(\DateTimeInterface $insertDate): static
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setNews($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getNews() === $this) {
                $comment->setNews(null);
            }
        }

        return $this;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function incrementViewCount(): static
    {
        $this->viewCount++;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
 