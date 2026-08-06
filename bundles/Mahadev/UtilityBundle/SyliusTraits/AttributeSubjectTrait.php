<?php

namespace Mahadev\UtilityBundle\SyliusTraits;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Webmozart\Assert\Assert;

trait AttributeSubjectTrait
{
    /** @var Collection|AttributeValueInterface[] */
    protected $attributes;

    /** @var array  */
    private array $attributesValues = [];

    public function __call($name, $arguments)
    {
        if($this->hasAttributeByCode($name)){
            return $this->getAttributeByCode($name)->getValue();
        }

//        if(!isset($arguments[0]) or !$arguments[0]) throw new \Exception('Method ' . $name . ' not exists');

        return null;

    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     * @Ignore()
     */
    public function getAttributesByLocale(
        string $localeCode,
        string $fallbackLocaleCode,
        ?string $baseLocaleCode = null
    ): Collection {
        if (null === $baseLocaleCode || $baseLocaleCode === $fallbackLocaleCode) {
            $baseLocaleCode = $fallbackLocaleCode;
            $fallbackLocaleCode = null;
        }

        $attributes = $this->attributes->filter(
            function (AttributeValueInterface $attribute) use ($baseLocaleCode) {
                return $attribute->getLocaleCode() === $baseLocaleCode;
            }
        );

        $attributesWithFallback = [];
        foreach ($attributes as $attribute) {
            $attributesWithFallback[] = $this->getAttributeInDifferentLocale($attribute, $localeCode, $fallbackLocaleCode);
        }

        return new ArrayCollection($attributesWithFallback);
    }

    /**
     * {@inheritdoc}
     */
    public function addAttribute(?AttributeValueInterface $attribute): void
    {
        /** @var AttributeValueInterface $attribute */
        Assert::isInstanceOf(
            $attribute,
            AttributeValueInterface::class,
            'Attribute objects added to a Product object have to implement ProductAttributeValueInterface'
        );

        if (!$this->hasAttribute($attribute)) {
            $attribute->setSubject($this);
            $this->attributes->add($attribute);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttribute(?AttributeValueInterface $attribute): void
    {
        /** @var AttributeValueInterface $attribute */
        Assert::isInstanceOf(
            $attribute,
            AttributeValueInterface::class,
            'Attribute objects removed from a Product object have to implement ProductAttributeValueInterface'
        );

        if ($this->hasAttribute($attribute)) {
            $this->attributes->removeElement($attribute);
            $attribute->setSubject(null);
        }
    }

    /**
     * {@inheritdoc}
     * @Ignore()
     */
    public function hasAttribute(AttributeValueInterface $attribute): bool
    {
        return $this->attributes->contains($attribute);
    }

    /**
     * {@inheritdoc}
     * @Ignore()
     */
    public function hasAttributeByCode(string $attributeCode): bool
    {

        foreach ($this->attributes as $attribute) {
            if ($attribute->getCode() === $attributeCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * @Ignore()
     */
    public function hasAttributeByCodeAndLocale(string $attributeCode, ?string $localeCode = null): bool
    {
        $localeCode = $localeCode ?: $this->getTranslation()->getLocale();

        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->getCode() === $attributeCode
                && $attribute->getLocaleCode() === $localeCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * @Ignore()
     */
    public function getAttributeByCode(string $attributeCode): ?AttributeValueInterface
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getCode() === $attributeCode) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     * @Ignore()
     */
    public function getAttributeByCodeAndLocale(string $attributeCode, ?string $localeCode = null): ?AttributeValueInterface
    {
        if (null === $localeCode) {
            $localeCode = $this->getTranslation()->getLocale();
        }

        foreach ($this->attributes as $attribute) {
            if ($attribute->getAttribute()->getCode() === $attributeCode &&
                $attribute->getLocaleCode() === $localeCode) {
                return $attribute;
            }
        }

        return null;
    }

    private function getAttributeInDifferentLocale(
        AttributeValueInterface $attributeValue,
        string $localeCode,
        ?string $fallbackLocaleCode = null
    ): AttributeValueInterface {
        if (!$this->hasNotEmptyAttributeByCodeAndLocale($attributeValue->getCode(), $localeCode)) {
            if (
                null !== $fallbackLocaleCode &&
                $this->hasNotEmptyAttributeByCodeAndLocale($attributeValue->getCode(), $fallbackLocaleCode)
            ) {
                return $this->getAttributeByCodeAndLocale($attributeValue->getCode(), $fallbackLocaleCode);
            }

            return $attributeValue;
        }

        return $this->getAttributeByCodeAndLocale($attributeValue->getCode(), $localeCode);
    }

    private function hasNotEmptyAttributeByCodeAndLocale(string $attributeCode, string $localeCode): bool
    {
        $attributeValue = $this->getAttributeByCodeAndLocale($attributeCode, $localeCode);
        if (null === $attributeValue) {
            return false;
        }

        $value = $attributeValue->getValue();
        if ('' === $value || null === $value || [] === $value) {
            return false;
        }

        return true;
    }

    public function setAttributeValue($code, $value){
        if($this->hasAttributeByCode($code)){
            $this->getAttributeByCode($code)->setValue($value);
        }
    }

    public function getAttributesValues(): array {
        /** @var AttributeValueInterface $attribute */
        foreach ($this->getAttributes() as $attribute){
            $config = $attribute->getAttribute()->getConfiguration();
//            dump($config);
            if(!(isset($config['password']) && $config['password']) && !isset($this->attributesValues[$attribute->getCode()] ))
                $this->attributesValues[$attribute->getCode()] = $attribute->getValue();
        }
        return  $this->attributesValues;
    }

    /**
     * @param array $attributesValues
     */
    public function setAttributesValues(array $attributesValues): void
    {
        $this->attributesValues = $attributesValues;
    }

}