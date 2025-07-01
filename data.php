<?php
header('Content-Type: application/json');

$file = 'data.json';

// JSON ဖိုင် မရှိသေးရင် ဖန်တီးထားမယ်
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$data = json_decode(file_get_contents($file), true);

$action = $_GET['action'] ?? '';

if ($action == 'read') {
    echo json_encode($data);
    exit;
}

if ($action == 'create') {
    $input = json_decode(file_get_contents('php://input'), true);
    $newId = count($data) > 0 ? max(array_column($data, 'id')) + 1 : 1;
    $newItem = [
        'id' => $newId,
        'name' => $input['name'],
        'money' => $input['money']
    ];
    $data[] = $newItem;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    echo json_encode($data);
    exit;
}

if ($action == 'delete') {
    $id = $_GET['id'] ?? 0;
    $data = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    $data = array_values($data);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    echo json_encode($data);
    exit;
}

if ($action == 'update') {
    $input = json_decode(file_get_contents('php://input'), true);
    foreach ($data as &$item) {
        if ($item['id'] == $input['id']) {
            $item['name'] = $input['name'];
            $item['money'] = $input['money'];
        }
    }
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    echo json_encode($data);
    exit;
}
?>
