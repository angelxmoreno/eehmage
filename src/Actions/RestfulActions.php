<?php
declare(strict_types=1);

namespace App\Actions;

use App\Database\ModelBase;
use App\Errors\HttpValidationException;
use App\Errors\ValidationError;

/**
 * Class RestfulActions
 * @package App\Actions
 */
abstract class RestfulActions extends ActionBase
{
    const GET = 'GET';
    const DELETE = 'DELETE';
    const POST = 'POST';
    const PATCH = 'PATCH';

    function run()
    {
        $id = $this->getArg('id', false);
        $method = $this->getRequest()->getMethod();

        if ($method === self::GET && $id) {
            $this->view($id);
        } elseif ($method === self::GET && !$id) {
            $this->list();
        } elseif ($method === self::DELETE && $id) {
            $this->delete($id);
        } elseif ($method === self::POST && $id) {
            $this->update($id);
        } elseif ($method === self::POST && !$id) {
            $this->create();
        }
    }

    /**
     * @param string $id
     */
    public function view(string $id)
    {
        $this->setData($this->getModel()::find($id));
    }

    /**
     * @return string
     */
    abstract protected function getModel(): string;

    public function list()
    {
        $this->setData($this->getModel()::all());
    }

    /**
     * @param string $id
     */
    public function delete(string $id)
    {
        $this->setData("todo");
    }

    /**
     * @param string $id
     */
    public function update(string $id)
    {
        $this->setData("todo");
    }

    /**
     * @throws \Throwable
     */
    public function create()
    {
        try {
            /** @var ModelBase $entity */
            $entity = $this->getModel()::buildFromRequest($this->getRequest());
            $entity->validateOrFail();
            $entity->saveOrFail();
            $this->setData($entity, 200);
        } catch (ValidationError $validationError) {
            throw new HttpValidationException($this->getRequest(), $validationError);
        }
    }

}