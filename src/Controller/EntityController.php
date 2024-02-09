<?php

namespace YoannLeonard\G\Controller;

use YoannLeonard\G\Game;
use YoannLeonard\G\Model\Entity;

class EntityController extends Controller
{
    private static ?EntityController $instance = null;



    public static function getInstance(): EntityController
    {
        if (self::$instance === null) {
            self::$instance = new EntityController();
        }
        return self::$instance;
    }

    public function renderEntity(Entity $entity): string
    {
        $name = $entity->getEntityName();
        return file_get_contents('src/View/Entity/' . $name);
    }
}