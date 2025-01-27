<?php

namespace App\DTO\ItemType;

use App\Entity\Framework\LsDefItemType;
use App\Entity\Framework\LsItem;
use App\Form\Type\LsItemJobType;
use Symfony\Component\Validator\Constraints as Assert;

class JobDto implements ItemTypeInterface
{
    public const string ITEM_TYPE_IDENTIFIER = LsDefItemType::TYPE_JOB_IDENTIFIER;
    public const string ITEM_TYPE_FORM = LsItemJobType::class;
    public const string WEBPAGE_KEY = 'ceterms:subjectWebpage';

    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Length(max: 255)]
        public ?string $abbreviatedStatement = null,
        #[Assert\NotBlank()]
        public ?string $fullStatement = null,
        #[Assert\Url(message: 'The webpage must be a valid URL.', requireTld: true)]
        public ?string $webpage = null,
    ) {
    }

    public static function fromItem(LsItem $item): self
    {
        $jobItemInfo = $item->getExtraProperty('extendedItem');

        return new self(
            $item->getAbbreviatedStatement(),
            $item->getFullStatement(),
            $jobItemInfo[self::WEBPAGE_KEY] ?? null
        );
    }

    public function applyToItem(LsItem $item): void
    {
        $item->setAbbreviatedStatement($this->abbreviatedStatement);
        $item->setFullStatement($this->fullStatement);
        $jobItemInfo = [
            'type' => 'job',
        ];
        if ($this->webpage) {
            $jobItemInfo[self::WEBPAGE_KEY] = $this->webpage;
        }
        $item->setExtraProperty('extendedItem', $jobItemInfo);
    }
}
