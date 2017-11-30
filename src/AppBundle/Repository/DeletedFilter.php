<?php
/**
 * Created by PhpStorm.
 * User: fdelhaye
 * Date: 11/30/2017
 * Time: 8:42 AM
 */

namespace AppBundle\Repository;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
class DeletedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasField("deletedAt")) {
            $date = date("Y-m-d h:m:s");
            return $targetTableAlias.".deletedAt < '".$date."' OR ".$targetTableAlias.".deletedAt IS NULL";
        }
        return "";
    }
}