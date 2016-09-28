<?php

namespace Combustion\StandardLib\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UuidModel
 *
 * @package     Combustion\StandardLib\Traits
 * @author      Carlos Granados <cgranados@combustiongroup.com>
 */
trait UuidModel
{

    protected static $idColumn = 'id';

    public static function bootUuidModel()
    {
        static::creating(function (Model $model) {
            $model->setAttribute(static::$idColumn, Uuid::uuid4()->toString());
            return true;
        });

        static::saving(function (Model $model) {
            $currentUuid = $model->getOriginal(static::$idColumn);

            if ($currentUuid !== $model->{static::$idColumn}) {
                $model->setAttribute(static::$idColumn, $currentUuid);
            }
        });
    }
}
