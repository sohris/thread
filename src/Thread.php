<?php

namespace Sohris\Thread;

use RuntimeException;

class Thread
{
    private $parent_func;
    private $child_func;
    private $async = false;
    private $process;


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
            $this->child_func = $func;
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

    public function run()
    {

        $pid = pcntl_fork();

        if ($pid === -1) {
            throw new \RuntimeException("Can't create a new fork in current task!");
        } else if ($pid) {
            $this->process = new Process($pid);
            if (\is_callable($this->parent_func)) {
                \call_user_func($this->parent_func, $this->process);
            }
        } else {
            $process = new Process(getmypid());
            if (\is_callable($this->child_func)) {
                \call_user_func($this->child_func, $process);
            }
        }
    }
}
