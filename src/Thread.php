<?php

namespace Sohris\Thread;

use React\Promise\Promise;
use RuntimeException;

class Thread
{
    private $parent_func;
    private $child_func;
    private $process;
    private $name;

    public function __construct()
    {
        pcntl_async_signals(true);
    }

    public function parent(callable $func)
    {
        if(is_callable($func))
        {
            $this->parent_func = $func;
        }
        return $this;
    }

    public function child(callable $func)
    {
        if(is_callable($func))
        {

            $this->child_func = \Closure::bind($func, $this, static::class);
        }
        return $this;
    }

    public function addSignal(int $sig, callable $func)
    {

        return $this;
    }

    public function isAsync(bool $async = true)
    {

        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function run()
    {
        $that = $this;
        return new Promise(function ($resolver) use ($that){

            $pid = pcntl_fork();

            if ($pid === -1) {
                throw new \RuntimeException("Can't create a new fork in current task!");
            } else if ($pid) {
                $that->process = new Process($pid);
                $that->running = true;
                if (\is_callable($that->parent_func)) {
                    \call_user_func($that->parent_func, $that->process);
                }
                pcntl_signal(SIGCHLD, function () use ($that){
                    $that->running = false;
                });
            } else {
                $process = new Process(getmypid());
                if($this->name)
                    $process->setName($this->name);
                if (\is_callable($that->child_func)) {
                    \call_user_func($that->child_func, $process);
                }
                exit();
            }
            
        });
    }
}
