<?php
/**
 * @OA\Get(path="/userbooks", tags={"userbooks"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all userbooks from the API (Admin only).",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of userbooks.")
 * )
 */
Flight::route('GET /userbooks', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userbookService()->get_all($user));
});

/**
 * @OA\Get(path="/userbooks/{id}", tags={"userbooks"}, security={{"ApiKeyAuth": {}}},
 *     summary="Return Individual userbook from the API (Admin only).",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of userbook"),
 *     @OA\Response(response="200", description="Fetch individual userbook")
 * )
 */
Flight::route('GET /userbooks/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userbookService()->get_by_id($user, $id));
});

/**
  * @OA\Post(
  *     path="/userbooks", security={{"ApiKeyAuth": {}}},
 *     summary="Add userbook to the API (Admin only).",
  *     description="Add user userbook",
  *     tags={"userbooks"},
  *     @OA\RequestBody(description="Basic userbook info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *           @OA\Property(property="user_id", type="number", example="1",	description="ID of the user" ),
  *           @OA\Property(property="book_id", type="number", example="1",	description="ID of the book" ),
  *           @OA\Property(property="bookmark", type="number", example="null",	description="Bookmark of the book" ),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="userbook that has been created"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('POST /userbooks', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userbookService()->add($user, Flight::request()->data->getData()));
});

/**
  * @OA\Put(
  *     path="/userbooks/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Update userbook in the API (Admin only).",
  *     description="Update user userbook",
  *     tags={"userbooks"},
  *     @OA\Parameter(in="path", name="id", example=1, description="userbook ID"),
  *     @OA\RequestBody(description="Basic userbook info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *           @OA\Property(property="user_id", type="number", example="1",	description="ID of the user" ),
  *           @OA\Property(property="book_id", type="number", example="1",	description="ID of the book" ),
  *           @OA\Property(property="bookmark", type="number", example="null",	description="Bookmark of the book" ),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="userbook that has been updated"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('PUT /userbooks/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');  
  $data = Flight::request()->data->getData();
  Flight::json(Flight::userbookService()->update($user, $id, $data));
});

/**
  * @OA\Delete(
  *     path="/userbooks/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Delete userbook from the API (Admin only).",
  *     description="Delete userbook",
  *     tags={"userbooks"},
  *     @OA\Parameter(in="path", name="id", example=1, description="userbook ID"),
  *     @OA\Response(
  *         response=200,
  *         description="userbook deleted"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('DELETE /userbooks/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::userbookService()->delete($user, $id);
  Flight::json(["message" => "deleted"]);
});






/**
 * @OA\Get(path="/userbooks_shelf", tags={"userbooks"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all userbooks from the API (Shelf).",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of userbooks.")
 * )
 */
Flight::route('GET /userbooks_shelf', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userbookService()->get_userbooks_shelf($user));
});

/**
 * @OA\Get(path="/userbook_shelf/{id}", tags={"userbooks"}, security={{"ApiKeyAuth": {}}},
 *     summary="Return Individual userbook from the API (Shelf).",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of userbook"),
 *     @OA\Response(response="200", description="Fetch individual userbook")
 * )
 */
Flight::route('GET /userbook_shelf/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');

  $result = Flight::userbookService()->get_userbook_shelf($user, $id);
  if($result === false) throw new Exception("Book doesn't exist in the shelf");
  else Flight::json($result);
});

/**
  * @OA\Post(
  *     path="/userbook_shelf/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Add userbook to the API (User's shelf).",
  *     description="Add userbook",
  *     tags={"userbooks"},
 *      @OA\Parameter(in="path", name="id", example=1, description="Id of the Book"),
  *     @OA\Response(
  *         response=200,
  *         description="The book has been added to the shelf successfully"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('POST /userbook_shelf/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');

  // check if the book exist
  $book = Flight::bookService()->get_public_book($id);
  if($book === null){    
    throw new Exception("The book doesn't exist!");
  }

  // check for dublicates
  $userbook = Flight::userbookService()->get_userbook_shelf($user, $id);
  if($userbook !== false){
    throw new Exception("The book has already been added!");
  }

  Flight::userbookService()->add_userbook_shelf($user, $id);
  Flight::json(["message" => "added to the shelf"]);
});

/**
  * @OA\Put(
  *     path="/userbook_shelf/{id}/{bookmark}", security={{"ApiKeyAuth": {}}},
 *     summary="Update userbook in the API (Shelf).",
  *     description="Update bookmark of the book in the shelf",
  *     tags={"userbooks"},
  *     @OA\Parameter(in="path", name="id", example=1, description="userbook ID"),
  *     @OA\Parameter(in="path", name="bookmark", example=1, description="bookmark page no."),
  *     @OA\Response(
  *         response=200,
  *         description="bookmark has been updated!"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('PUT /userbook_shelf/@id/@bookmark', function($id, $bookmark){
  // who is the user who calls this method?
  $user = Flight::get('user');  
  Flight::json(Flight::userbookService()->update_userbook_shelf($user, $id, $bookmark));
});

/**
  * @OA\Delete(
  *     path="/userbooks_shelf/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Delete userbook from the API (Shelf).",
  *     description="Delete userbook",
  *     tags={"userbooks"},
  *     @OA\Parameter(in="path", name="id", example=1, description="Book ID"),
  *     @OA\Response(
  *         response=200,
  *         description="The book has been removed from the shelf!"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('DELETE /userbooks_shelf/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');  
  Flight::json(Flight::userbookService()->delete_userbook_shelf($user, $id));
});

?>
