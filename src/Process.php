<?php


namespace Sohris\Thread;

class Process
{
    private $pid;

    public function __construct(int $pid)
    {
        $this->pid = $pid;
    }

    public function getPID()
    {
        return $this->pid;
    }

}