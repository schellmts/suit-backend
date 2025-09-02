<?php

return [
    'priorities' => [
        1 => [ 'first_interaction' => 1, 'finish' => 4 ], // Crítico
        2 => [ 'first_interaction' => 2, 'finish' => 8 ], // Urgente
        3 => [ 'first_interaction' => 4, 'finish' => 24 ], // Alta
        4 => [ 'first_interaction' => 8, 'finish' => 48 ], // Média
        5 => [ 'first_interaction' => 24, 'finish' => 72 ], // Baixa
        6 => [ 'first_interaction' => 24, 'finish' => 46 ], // Email
    ],
];
