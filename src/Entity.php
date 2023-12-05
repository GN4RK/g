<?php

namespace YoannLeonard\G;

abstract class Entity
{
    private string $entityName;

    public function __construct()
    {
        $entityClassPath = get_class($this);
        $nameParts = explode('\\', $entityClassPath);
        $this->entityName = end($nameParts);
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }


}