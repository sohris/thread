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

    public function setName(string $name)
    {
        $pid = $this->pid;
        @file_put_contents("/proc/$pid/comm", $name);
    }
}