<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->delete('sheets/{id}', function (MongoDB\Database $db, $id) use ($app) {
    $result = $db->Sheets->deleteOne([
        '_id' => new MongoDB\Bson\ObjectID($id),
    ]);

    return [
        'deleted' => $result->getDeletedCount() === 1,
    ];
});

// Set the value of a given cell
$app->put('sheets/{id}/cells', function (Illuminate\Http\Request $request, $id) {
    $updates = [];

    foreach ($request->json()->all() as list($table, $row, $col, $value)) {
        $key = sprintf('tables.%d.rows.%d.%d', $table, $row, $col);
        $updates[$key] = $value;
    }

    $updateDoc = ['$set' => $updates];
    $selectDoc = ['_id' => new MongoDB\Bson\ObjectID($id)];
    $result = App\Document::collection()->updateOne($selectDoc, $updateDoc);
    return [
        'found' => $result->getMatchedCount() === 1,
        'modified' => $result->getModifiedCount() === 1,
    ];
});

$app->post('sheets', function (MongoDB\Database $db, Illuminate\Http\Request $request) use ($app) {
    $result = $db->Sheets->deleteMany([]);
    $json = $request->json()->all();
    $doc = App\Document::fromArray($json);
    $doc->save();
    $data = (array) $db->Sheets->findOne();
    return App\Document::find($data['_id'])->toArray();
});

$app->get('/sheets/{id}', function (MongoDB\Database $db) use ($app) {
    return $db->Sheets->find()->toArray();
});
