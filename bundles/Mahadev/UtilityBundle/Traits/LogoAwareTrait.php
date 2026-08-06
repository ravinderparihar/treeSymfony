<?php


namespace Mahadev\UtilityBundle\Traits;


use Symfony\Component\HttpFoundation\File\File as File;

trait LogoAwareTrait
{

    protected ?File $logo =  null;

    /** @var string|null  */
    protected ?string $logoUrl = null;

    /** @var string|null  */
    protected ?string $logoName = null;

    public function getLogo(): ?File
    {
        return $this->logo;
    }


    public function setLogo(?File $logo): void
    {
        $this->updatedAt = new \DateTime();
        $this->logo = $logo;
    }

    /**
     * @return string|null
     */
    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    /**
     * @param string|null $logoUrl
     */
    public function setLogoUrl(?string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
    }

    /**
     * @return string|null
     */
    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    /**
     * @param string|null $logoName
     */
    public function setLogoName(?string $logoName): void
    {
        $this->logoName = $logoName;
    }
}