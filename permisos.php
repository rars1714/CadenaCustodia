<?php
$permisos = [
    'Abogado' => [
        'ingresar_evidencia' => true,
        'consultar_evidencia' => 'propias',
        'crear_casos' => false,
        'consultar_casos' => 'propios',
    ],
    'Perito' => [
        'ingresar_evidencia' => true,
        'consultar_evidencia' => 'propias',
        'crear_casos' => false,
        'consultar_casos' => 'propios',
    ],
    'Fiscal' => [
        'ingresar_evidencia' => false,
        'consultar_evidencia' => 'todas',
        'crear_casos' => true,
        'consultar_casos' => 'propios',
    ],
    'Juez' => [
        'ingresar_evidencia' => false,
        'consultar_evidencia' => false,
        'crear_casos' => false,
        'consultar_casos' => 'propios',
    ],
];
