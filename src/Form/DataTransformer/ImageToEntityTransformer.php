<?php
namespace App\Form\DataTransformer;

use App\Entity\Images;
use App\Repository\ImagesRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ImageToEntityTransformer implements DataTransformerInterface
{
    private $imagesRepository;

    public function __construct(ImagesRepository $imagesRepository)
    {
        $this->imagesRepository = $imagesRepository;
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value->getFileName();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $image = $this->imagesRepository->findOneBy(['fileName' => $value]);

        if (null === $image) {
            throw new TransformationFailedException(sprintf('The image "%s" does not exist.', $value));
        }

        return $image;
    }
}
