<?php
/**
 * Created by PhpStorm.
 * User: fdelhaye
 * Date: 5/13/2017
 * Time: 10:20 PM
 */

namespace AppBundle\Entity;


class ClientSearch
{
    private $searchField;

    public function getSearchField()
    {
        return $this->searchField;
    }

    public function setSearchField($searchField)
    {
        $this->searchField= $searchField;
        return $this;
    }

}