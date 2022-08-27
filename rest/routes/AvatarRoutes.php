<?php
/**
 * @OA\Get(path="/avatars", tags={"avatars"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all avatars from the API (Admin only).",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of avatars.")
 * )
 */
Flight::route('GET /avatars', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::avatarService()->get_all($user));
});

/**
 * @OA\Get(path="/avatars/{id}", tags={"avatars"}, security={{"ApiKeyAuth": {}}},
 *     summary="Return Individual avatar from the API (Admin only).",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of avatar"),
 *     @OA\Response(response="200", description="Fetch individual avatar")
 * )
 */
Flight::route('GET /avatars/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::avatarService()->get_by_id($user, $id));
});

/**
  * @OA\Post(
  *     path="/avatars", security={{"ApiKeyAuth": {}}},
 *     summary="Add avatar to the API (Admin only).",
  *     description="Add user avatar",
  *     tags={"avatars"},
  *     @OA\RequestBody(description="Basic avatar info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="location", type="string", example="assets/avatars/test.png",	description="Location of the avatar image file"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="avatar that has been created"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('POST /avatars', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::avatarService()->add($user, Flight::request()->data->getData()));
});

/**
  * @OA\Put(
  *     path="/avatars/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Update avatar in the API (Admin only).",
  *     description="Update user avatar",
  *     tags={"avatars"},
  *     @OA\Parameter(in="path", name="id", example=1, description="avatar ID"),
  *     @OA\RequestBody(description="Basic avatar info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="location", type="string", example="assets/avatars/test.png",	description="Location of the avatar image file"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="avatar that has been updated"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('PUT /avatars/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');  
  $data = Flight::request()->data->getData();
  Flight::json(Flight::avatarService()->update($user, $id, $data));
});

/**
  * @OA\Delete(
  *     path="/avatars/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Delete avatar from the API (Admin only).",
  *     description="Delete avatar",
  *     tags={"avatars"},
  *     @OA\Parameter(in="path", name="id", example=1, description="avatar ID"),
  *     @OA\Response(
  *         response=200,
  *         description="avatar deleted"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('DELETE /avatars/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::avatarService()->delete($user, $id);
  Flight::json(["message" => "deleted"]);
});

?>
