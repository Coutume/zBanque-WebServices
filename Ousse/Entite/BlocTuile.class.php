<?php
/**
 * Created by PhpStorm.
 * User: moribus
 * Date: 11/01/2016
 * Time: 19:39
 */

namespace Ousse\Entite;


class BlocTuile
{
    protected $id;

    protected $x;

    protected $y;

    protected $z;

    protected $bloc;

    protected $type_id;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getBloc()
    {
        return $this->bloc;
    }

    /**
     * @param mixed $bloc
     */
    public function setBloc($bloc)
    {
        $this->bloc = $bloc;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * @param mixed $type_id
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    /**
     * @return mixed
     */
    public function getZ()
    {
        return $this->z;
    }
}