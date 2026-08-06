<?php


namespace Mahadev\UtilityBundle\SyliusTraits;


use Doctrine\Common\Collections\Collection;
use Sylius\Component\Review\Model\ReviewInterface;

trait ReviewableTrait
{
    private Collection $reviews;

    /** @var float|null  */
    private ?float $averageRating;


    public function addReview(ReviewInterface $review): void
    {
        if(!$this->reviews->contains($review)){
            $this->reviews->add($review);
        }
    }

    public function removeReview(ReviewInterface $review): void
    {
        if($this->reviews->contains($review)){
            $this->reviews->remove($review);
        }
    }

    /**
     * @return float|null
     */
    public function getAverageRating(): ?float
    {
        return $this->averageRating;
    }

    /**
     * @param float|null $averageRating
     */
    public function setAverageRating(?float $averageRating): void
    {
        $this->averageRating = $averageRating;
    }

    /**
     * @return Collection
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * @param Collection $reviews
     */
    public function setReviews(Collection $reviews): void
    {
        $this->reviews = $reviews;
    }
}