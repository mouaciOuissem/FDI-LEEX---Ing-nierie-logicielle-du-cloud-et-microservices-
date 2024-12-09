<?php
// api/src/Serializer/ApiNormalizer

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Cette classe permet de modifier le retour json de l'Api sur les méthodes PUT et POST et GET.
 */
final class ApiNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    /**
     * Change le format de retour json pour les méthodes PUT et POST et GET
     *
     * @param [type] $object
     * @param [type] $format
     * @param array $context
     * @return void
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->decorated->normalize($object, $format, $context);
        
        $result = [];
        $result["message"] = "Ok";
        $result["data"] = $data;
        
        return $result;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    /**
     *
     *
     * @param [type] $object
     * @param [type] $format
     * @param array $context
     * @return void
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
       
        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}