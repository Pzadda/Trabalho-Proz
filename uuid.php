<?php

function generateUuid(){
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); //versão 4
    $data[8] = chr(ord($data[8]) & 0x0f | 0x40); //versão variante
    return vsprintf('%s%s-%s-%s-%s-%s%s%s%S', str_split(bin2hex($data), 4))
}

?>