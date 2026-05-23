<?php

declare(strict_types=1);

namespace App\Objecting;

use App\Objecting\DependencyInjection\ObjectExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ObjectBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ObjectExtension();
    }
}
