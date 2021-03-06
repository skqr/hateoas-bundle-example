<?php

namespace HateoasInc\Bundle\ExampleBundle\Rest\Resource;

// REST.
use GoIntegro\Hateoas\JsonApi\EntityResource;

class UserResource extends EntityResource
{
    /**
     * @var array
     */
    public static $fieldBlacklist = ['password', 'salt'];
    /**
     * @var array
     */
    public static $relationshipBlacklist = ['followers'];
}
