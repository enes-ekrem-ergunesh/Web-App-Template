<?php
/**
 * @OA\Get(path="/books", tags={"books"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all books from the API (Admin only).",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of books.")
 * )
 */
Flight::route('GET /books', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::bookService()->get_all($user));
});

/**
 * @OA\Get(path="/books/{id}", tags={"books"}, security={{"ApiKeyAuth": {}}},
 *     summary="Return Individual book from the API (Admin only).",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of book"),
 *     @OA\Response(response="200", description="Fetch individual book")
 * )
 */
Flight::route('GET /books/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::bookService()->get_by_id($user, $id));
});

/**
  * @OA\Post(
  *     path="/books", security={{"ApiKeyAuth": {}}},
 *     summary="Add book to the API (Admin only).",
  *     description="Add user book",
  *     tags={"books"},
  *     @OA\RequestBody(description="Basic book info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="name", type="string", example="test",	description="Name of the book"),
  *    				@OA\Property(property="author_id", type="number", example="1",	description="ID of the author"),
  *    				@OA\Property(property="language", type="string", example="English",	description="Book's language"),
  *    				@OA\Property(property="cover", type="string", example="cover-link",	description="Book's cover"),
  *    				@OA\Property(property="source", type="string", example="source-link",	description="Book's source"),
  *    				@OA\Property(property="release_date", type="string", example="2002-02-22",	description="Release date of the book" ),
  *           @OA\Property(property="user_id", type="number", example="1",	description="ID of the user who added the book to the database" ),
  *    				@OA\Property(property="activity", type="string", example="active",	description="Status of the book (exists / deleted)"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="book that has been created"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('POST /books', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::bookService()->add($user, Flight::request()->data->getData()));
});

/**
  * @OA\Put(
  *     path="/books/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Update book in the API (Admin only).",
  *     description="Update user book",
  *     tags={"books"},
  *     @OA\Parameter(in="path", name="id", example=1, description="book ID"),
  *     @OA\RequestBody(description="Basic book info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="name", type="string", example="test",	description="Name of the book"),
  *    				@OA\Property(property="author_id", type="number", example="1",	description="ID of the author"),
  *    				@OA\Property(property="language", type="string", example="English",	description="Book's language"),
  *    				@OA\Property(property="cover", type="string", example="cover-link",	description="Book's cover"),
  *    				@OA\Property(property="source", type="string", example="source-link",	description="Book's source"),
  *    				@OA\Property(property="release_date", type="string", example="2002-02-22",	description="Release date of the book" ),
  *           @OA\Property(property="user_id", type="number", example="1",	description="ID of the user who added the book to the database" ),
  *    				@OA\Property(property="activity", type="string", example="active",	description="Status of the book (exists / deleted)"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="book that has been updated"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('PUT /books/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');  
  $data = Flight::request()->data->getData();
  Flight::json(Flight::bookService()->update($user, $id, $data));
});

/**
  * @OA\Delete(
  *     path="/books/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Delete book from the API (Admin only).",
  *     description="Delete book",
  *     tags={"books"},
  *     @OA\Parameter(in="path", name="id", example=1, description="book ID"),
  *     @OA\Response(
  *         response=200,
  *         description="book deleted"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('DELETE /books/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::bookService()->delete($user, $id);
  Flight::json(["message" => "deleted"]);
});




/**
 * @OA\Get(path="/publicbooks", tags={"books"},
 *         summary="Return public books from the API.",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of books.")
 * )
 */
Flight::route('GET /publicbooks', function(){
  Flight::json(Flight::bookService()->get_public_books());
});

/**
 * @OA\Get(path="/publicbooks/{id}", tags={"books"},
 *     summary="Return Individual public book from the API.",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of book"),
 *     @OA\Response(response="200", description="Fetch individual book")
 * )
 */
Flight::route('GET /publicbooks/@id', function($id){
  Flight::json(Flight::bookService()->get_public_book($id));
});

/**
 * @OA\Get(path="/publicbooks_by_author/{id}", tags={"books"},
 *     summary="Return public books of individual author from the API.",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of author"),
 *     @OA\Response(response="200", description="Fetch individual book")
 * )
 */
Flight::route('GET /publicbooks_by_author/@id', function($id){
  Flight::json(Flight::bookService()->get_public_books_by_author($id));
});



?>
