<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



/**
 * @OA\Get(path="/users", tags={"users"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all users from the API (Admin only).",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of users.")
 * )
 */
Flight::route('GET /users', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userService()->get_all($user));
});

/**
 * @OA\Get(path="/users/{id}", tags={"users"}, security={{"ApiKeyAuth": {}}},
 *     summary="Return Individual user from the API (Admin only).",
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of user"),
 *     @OA\Response(response="200", description="Fetch individual user")
 * )
 */
Flight::route('GET /users/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userService()->get_by_id($user, $id));
});

/**
  * @OA\Post(
  *     path="/users", security={{"ApiKeyAuth": {}}},
 *     summary="Add user to the API (Admin only).",
  *     description="Add user user",
  *     tags={"users"},
  *     @OA\RequestBody(description="Basic user info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="email", type="string", example="test@example.com",	description="email of the user"),
  *    				@OA\Property(property="password", type="string", example="123",	description="password of the user"),
  *    				@OA\Property(property="first_name", type="string", example="John",	description="user's first name"),
  *    				@OA\Property(property="last_name", type="string", example="Watson",	description="user's last name"),
  *           @OA\Property(property="avatar_id", type="number", example="1",	description="Avatar id" ),
  *    				@OA\Property(property="admin", type="number", example="0",	description="Status of the user (admin->1 / not admin->0)"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="user that has been created"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('POST /users', function(){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::json(Flight::userService()->add($user, Flight::request()->data->getData()));
});

/**
  * @OA\Put(
  *     path="/users/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Update user in the API (Admin only).",
  *     description="Update user user",
  *     tags={"users"},
  *     @OA\Parameter(in="path", name="id", example=1, description="user ID"),
  *     @OA\RequestBody(description="Basic user info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="email", type="string", example="test@example.com",	description="email of the user"),
  *    				@OA\Property(property="password", type="string", example="123",	description="password of the user"),
  *    				@OA\Property(property="first_name", type="string", example="John",	description="user's first name"),
  *    				@OA\Property(property="last_name", type="string", example="Watson",	description="user's last name"),
  *           @OA\Property(property="avatar_id", type="number", example="1",	description="Avatar id" ),
  *    				@OA\Property(property="admin", type="number", example="0",	description="Status of the user (admin->1 / not admin->0)"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="user that has been updated"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('PUT /users/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');  
  $data = Flight::request()->data->getData();
  Flight::json(Flight::userService()->update($user, $id, $data));
});

/**
  * @OA\Delete(
  *     path="/users/{id}", security={{"ApiKeyAuth": {}}},
 *     summary="Delete user from the API (Admin only).",
  *     description="Delete user",
  *     tags={"users"},
  *     @OA\Parameter(in="path", name="id", example=1, description="user ID"),
  *     @OA\Response(
  *         response=200,
  *         description="user deleted"
  *     ),
  *     @OA\Response(
  *         response=500,
  *         description="Error"
  *     )
  * )
*/
Flight::route('DELETE /users/@id', function($id){
  // who is the user who calls this method?
  $user = Flight::get('user');
  Flight::userService()->delete($user, $id);
  Flight::json(["message" => "deleted"]);
});


/**
  * @OA\Post(
  *     path="/login",
  *     description="Login to the system",
  *     tags={"account"},
  *     @OA\RequestBody(description="Basic user info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="email", type="string", example="user@example.com",	description="Email"),
  *    				@OA\Property(property="password", type="string", example="12345678",	description="Password" )
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="JWT Token on successful response"
  *     ),
  *     @OA\Response(
  *         response=404,
  *         description="Wrong Password | User doesn't exist"
  *     )
  * )
*/
Flight::route('POST /login', function(){
  $login = Flight::request()->data->getData();
  $user = Flight::userDao()->get_user_by_email($login['email']);
  if (isset($user['id'])){
    if($user['password'] == md5($login['password'])){
      unset($user['password']);
      $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
      Flight::json(['token' => $jwt]);
    }else{
      Flight::json(["message" => "Wrong password"], 404);
    }
  }else{
    Flight::json(["message" => "User doesn't exist"], 404);
  }
});

/**
  * @OA\Post(
  *     path="/sign_up",
  *     description="Sign up",
  *     tags={"account"},
  *     @OA\RequestBody(description="Basic user info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="email", type="string", example="newuser@gmail.com",	description="Email"),
  *    				@OA\Property(property="password", type="string", example="12345678",	description="Password" ),
  *    				@OA\Property(property="first_name", type="string", example="new",	description="First Name" ),
  *    				@OA\Property(property="last_name", type="string", example="user",	description="Last Name"),
  *    				@OA\Property(property="avatar_id", type="number", example="1",	description="Avatar"),
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="JWT Token on successful response"
  *     ),
  *     @OA\Response(
  *         response=406,
  *         description="Invalid Password | User already exist"
  *     )
  * )
*/
Flight::route('POST /sign_up', function(){
  $entity = Flight::request()->data->getData();
  $user = Flight::userDao()->get_user_by_email($entity['email']);
  if (isset($user['id'])){
    // If user with same email already exists
    Flight::json(["message" => "User already exist"], 409);
  }
  else{
    if(strlen($entity['password']) < 8 || strlen($entity['password']) > 20){
      // If password is too short or too long
      Flight::json(["message" => "Invalid password"], 406);
    }
    else if (isset($entity['admin'])){
      if($entity['admin'] == 1)
      // If user trying to sign up as admin
      throw new Exception("This is hack you will be traced, be prepared ;)");
    }
    else{
      // add the user to the database
      $entity['password'] = md5($entity['password']);
      $entity['admin'] = 0;
      $user = Flight::userService()->sign_up($entity);
      // Flight::json($user);
      unset($user['password']);
      $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
      Flight::json(['token' => $jwt]);
    }
  }
});

/**
  * @OA\GET(
  *     path="/current_user",
  *     description="Get current user information",
  *     tags={"account"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Response(
  *         response=200,
  *         description="JWT Token on successful response"
  *     ),
  *     @OA\Response(
  *         response=404,
  *         description="Wrong Password | User doesn't exist"
  *     )
  * )
*/
Flight::route('GET /current_user', function(){
  $user = Flight::get('user');
  Flight::json($user);
});

/**
  * @OA\PUT(
  *     path="/current_user",
  *     description="Update current user information",
  *     tags={"account"}, security={{"ApiKeyAuth": {}}},
  *     @OA\RequestBody(description="Basic user info", required=true,
  *       @OA\MediaType(mediaType="application/json",
  *    			@OA\Schema(
  *    				@OA\Property(property="avatar_id", type="number", example="1", description="Avatar id"),
  *    				@OA\Property(property="first_name", type="string", example="Mark", description="First Name"),
  *    				@OA\Property(property="last_name", type="string", example="Spector", description="Last Name"),
  *    				@OA\Property(property="old_password", type="string", example="123", description="Old Password"),
  *    				@OA\Property(property="password", type="string", example="321", description="New Password" )
  *        )
  *     )),
  *     @OA\Response(
  *         response=200,
  *         description="JWT Token on successful response"
  *     ),
  *     @OA\Response(
  *         response=404,
  *         description="Wrong Password | User doesn't exist"
  *     )
  * )
*/
Flight::route('PUT /current_user', function(){
  $entity = Flight::request()->data->getData();
  // Flight::json($entity);
  $user = Flight::userDao()->get_user_by_email(Flight::get('user')['email']);
  if($user['password'] == md5($entity['old_password'])){
    if(strlen($entity['password']) < 8 || strlen($entity['password']) > 20){
      // If password is too short or too long
      Flight::json(["message" => "Invalid password"], 406);
    }
    else{
      unset($entity['old_password']);
      $entity['email'] = $user['email'];
      $entity['admin'] = $user['admin'];
      $entity['password'] = md5($entity['password']);
      // Flight::json($entity);
      $user = Flight::userService()->update_current($entity, $user['id']);
      unset($user['password']);
      $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
      Flight::json(['token' => $jwt]);
    }
  }
  else{
    Flight::json(["message" => "Wrong password"], 404);
  }
});

?>
