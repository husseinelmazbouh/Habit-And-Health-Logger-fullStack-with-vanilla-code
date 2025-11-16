<?php
$apis = [
    '/register' => ['controller' => 'userController', 'method' => 'register'],
    '/login' => ['controller' => 'userController', 'method' => 'login'],
    
    '/users' => ['controller' => 'userController', 'method' => 'getUsers'],
    '/users/update' => ['controller' => 'userController', 'method' => 'updateUser'],
    '/users/delete' => ['controller' => 'userController', 'method' => 'deleteUser'],

    '/habits' => ['controller' => 'habitController', 'method' => 'getHabits'],
    '/habits/create' => ['controller' => 'habitController', 'method' => 'createHabit'],
    '/habits/update' => ['controller' => 'habitController', 'method' => 'updateHabit'],
    '/habits/delete' => ['controller' => 'habitController', 'method' => 'deleteHabit'],
    
    '/entries' => ['controller' => 'entryController', 'method' => 'getEntries'],
    '/entries/create' => ['controller' => 'entryController', 'method' => 'createEntry'],
    '/entries/update' => ['controller' => 'entryController', 'method' => 'updateEntry'],
    '/entries/delete' => ['controller' => 'entryController', 'method' => 'deleteEntry'],
    
    '/ai/parse' => ['controller' => 'AiController', 'method' => 'parseText'],
    '/ai/weekly-summary' => ['controller' => 'AiController', 'method' => 'weeklySummary'],
    '/ai/nutrition-advice' => ['controller' => 'AiController', 'method' => 'nutritionAdvice']
];
?>