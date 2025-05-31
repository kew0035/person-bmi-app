<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$app = AppFactory::create();

$app->add(function (Request $request, $handler) {
    $method = $request->getMethod();
    $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Content-Type, Accept, Origin, Authorization',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
    ];

    if ($method === 'OPTIONS') {
        $response = new \Slim\Psr7\Response();
        foreach ($headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }
        return $response->withStatus(200);
    }

    $response = $handler->handle($request);
    foreach ($headers as $key => $value) {
        $response = $response->withHeader($key, $value);
    }
    return $response;
});

// get all person
$app->get('/person', function (Request $request, Response $response) {
    try {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT * FROM person");
        $data = $stmt->fetchAll();

        $payload = json_encode($data);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $error = ['error' => $e->getMessage()];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// get person by id
$app->get('/person/{id}', function (Request $request, Response $response, $args) {
    try {
        $id = (int)$args['id'];
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM person WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Person not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// add person
$app->post('/person', function (Request $request, Response $response) {
    try {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!$data || !isset($data['name'], $data['yob'], $data['weight'], $data['height'], $data['bmi'])) {
            throw new Exception('Missing required fields');
        }

        $age = date('Y') - (int)$data['yob'];
        $bmi = floatval($data['bmi']);
        if ($bmi < 18.5) $category = 'Underweight';
        elseif ($bmi < 24.9) $category = 'Normal';
        elseif ($bmi < 29.9) $category = 'Overweight';
        else $category = 'Obese';

        $pdo = getPDO();
        $stmt = $pdo->prepare("INSERT INTO person (name, yob, weight, height, bmi, category, age, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['yob'],
            $data['weight'],
            $data['height'],
            $bmi,
            $category,
            $age,
            $data['photoUrl'] ?? null
        ]);

        $insertedId = $pdo->lastInsertId();
        $response->getBody()->write(json_encode(['message' => 'Person added', 'id' => $insertedId]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

// delete person
$app->delete('/person/{id}', function (Request $request, Response $response, $args) {
    try {
        $id = (int)$args['id'];
        $pdo = getPDO();
        $stmt = $pdo->prepare("DELETE FROM person WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(['message' => 'Successful deletion']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['error' => 'Person not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// update person - PUT 
$app->put('/person/{id}', function (Request $request, Response $response, $args) {
    try {
        $id = (int)$args['id'];
        $data = json_decode($request->getBody()->getContents(), true);

        if (!$data || !isset($data['name'], $data['yob'], $data['weight'], $data['height'], $data['bmi'])) {
            throw new Exception('Missing required fields');
        }

        $age = date('Y') - (int)$data['yob'];
        $bmi = floatval($data['bmi']);
        if ($bmi < 18.5) $category = 'Underweight';
        elseif ($bmi < 24.9) $category = 'Normal';
        elseif ($bmi < 29.9) $category = 'Overweight';
        else $category = 'Obese';

        $pdo = getPDO();
        $stmt = $pdo->prepare("UPDATE person SET name=?, yob=?, weight=?, height=?, bmi=?, category=?, age=?, image=? WHERE id=?");
        $stmt->execute([
            $data['name'],
            $data['yob'],
            $data['weight'],
            $data['height'],
            $bmi,
            $category,
            $age,
            $data['photoUrl'] ?? null,
            $id
        ]);

        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(['message' => 'Update successful']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['error' => 'Person not found or no changes made']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});


$app->run();
