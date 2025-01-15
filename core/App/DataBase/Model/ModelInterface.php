<?php

namespace Kernel\Application\DataBase\Model;

interface ModelInterface
{
    public function create(): void;

    public function update(): void;

    public function delete(): void;

    public function find(): void;

    public function all(): void;

    public function first(): void;

    public function where(): void;
}
