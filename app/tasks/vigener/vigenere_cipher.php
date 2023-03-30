<?php

function cutOrPadKey(string $key, int $lenMessage): string
{
    if ($lenMessage > strlen($key)) {
        return str_pad($key, $lenMessage, $key);
    }
    return substr($key, 0, $lenMessage);
}

function translateToNumber(string $key, array $alphabet): array
{
    $key = str_split($key);

    foreach ($key as $alpha) {
        $parseKey[] = array_search($alpha, $alphabet);
    }

    return $parseKey ?? [];
}

function decrypt(string $key, string $message, array $alphabet): string
{
    $key = translateToNumber($key, $alphabet);
    $message = translateToNumber($message, $alphabet);
    $countAlphabet = count($alphabet);

    for ($i = 0; $i < count($message); $i++) {
        $diff = $message[$i] - $key[$i] < 0 ? $countAlphabet + $message[$i] - $key[$i] : $message[$i] - $key[$i];
        $index = $diff % $countAlphabet;
        $decryptAlpha[] = $alphabet[$index];
    }

    return implode('', $decryptAlpha ?? []);
}

function dd(mixed $value): void
{
    var_dump($value);
    die();
}

function main(): void
{
    $stringData = file_get_contents(__DIR__ . '/../storage/vigenere_chiper.json');
    $data = json_decode($stringData, true);

    $alphabet = str_split($data['alphabet']);

    $result['data'] = [];

    foreach ($data['messages'] as $node) {
        $key = cutOrPadKey($node['key'], strlen($node['message']));
        $result['data'][]['message'] = decrypt($key, $node['message'], $alphabet);
    }

    file_put_contents(__DIR__ . '/../storage/vigenere_chiper_result.json', json_encode($result ?? []));
}

main();
