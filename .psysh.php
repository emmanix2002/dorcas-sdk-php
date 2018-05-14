<?php

return [
    // You can write your own tab completion matchers, too! Here are some that
    // enable tab completion for MongoDB database and collection names:
    'tabCompletionMatchers' => [
        new \Psy\TabCompletion\Matcher\FunctionsMatcher,
        new \Psy\TabCompletion\Matcher\VariablesMatcher,
    ],
];