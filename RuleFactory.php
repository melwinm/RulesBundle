<?php

namespace CodeMeme\RulesBundle;

use CodeMeme\RulesBundle\Rule\BehaviorFactory;

class RuleFactory
{
    private $behaviorFactory;

    public function __construct($behaviorFactory)
    {
        $this->behaviorFactory = $behaviorFactory;
    }

    public function createRule($name, $conditions, $actions, $fallbacks = array())
    {
        $rule = new Rule;
        $rule->setName($name);
        
        if (! $conditions) {
            throw new \InvalidArgumentException('Rule conditions must be iterable');
        }
        
        foreach ($conditions as $condition => $values) {
            $rule->getConditions()->add(
                $this->behaviorFactory->createBehavior($condition, $values)
            );
        }
        
        if (! $actions) {
            throw new \InvalidArgumentException('Rule actions must be iterable');
        }
        
        foreach ($actions as $action => $values) {
            $rule->getActions()->add(
                $this->behaviorFactory->createBehavior($action, $values)
            );
        }
        
        if ($fallbacks) {
            foreach ($fallbacks as $fallback => $values) {
                $rule->getFallbacks()->add(
                    $this->behaviorFactory->createBehavior($fallback, $values)
                );
            }
        }
        
        return $rule;
    }

    public function createRules($configs)
    {
        $rules = array();
        
        foreach ($configs as $name => $config) {
            $rules[] = $this->createRule(
                $name,
                $config['if'],
                $config['then'],
                isset($config['else']) ? $config['else'] : array()
            );
        }
        
        return $rules;
    }

}