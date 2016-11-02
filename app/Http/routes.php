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
        'id' => $id,
    ]);

    return [
        'deleted' => $result->getDeletedCount() === 1,
    ];
});

// Set the value of a given cell
$app->put('sheets/{id}/table/{table}/cell/{row}/col', function () {
    $key = $json['key'];
    $value = $json['value'];

    $selectDoc = ['_id' => new ObjectID($id)];
    $updateDoc = ['$set' => [$key => $value, ]];
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
