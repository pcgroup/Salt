<?php

namespace App\Serializer\CaseJson;

use App\Entity\Framework\LsDefAssociationGrouping;
use App\Service\Api1Uris;
use App\Util\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class LsDefAssociationGroupingNormalizer implements NormalizerInterface
{
    use LastChangeDateTimeTrait;

    public function __construct(
        private readonly Api1Uris $api1Uris,
    ) {
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof LsDefAssociationGrouping;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [LsDefAssociationGrouping::class => true];
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): ?array
    {
        if (!$data instanceof LsDefAssociationGrouping) {
            return null;
        }

        $jsonLd = $context['case-json-ld'] ?? null;
        $addContext = (null !== $jsonLd) ? ($context['add-case-context'] ?? null) : null;
        $addType = (null === $addContext) ? ($context['add-case-type'] ?? null) : $addContext;
        $ret = [
            '@context' => (null !== $addContext)
                ? 'https://purl.imsglobal.org/spec/case/v1p0/context/imscasev1p0_context_v1p0.jsonld'
                : null,
            'type' => (null !== $addType)
                ? 'CFAssociationGrouping'
                : null,
            'identifier' => $data->getIdentifier(),
            'uri' => $this->api1Uris->getUri($data),
            'lastChangeDateTime' => $this->getLastChangeDateTime($data),
            'title' => $data->getTitle(),
            'description' => $data->getDescription(),
        ];

        if (in_array('opensalt', $context['groups'] ?? [], true)) {
            $ret['_opensalt'] = $data->getExtra();
        }

        return Collection::removeEmptyElements($ret);
    }
}
