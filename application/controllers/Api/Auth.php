<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Auth extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Users_model');

        // if ($this->authtoken() == 'salah') {
        //     return $this->response(array('kode' => '401', 'pesan' => 'signature tidak sesuai', 'data' => []), '401');
        //     die();
        // }
    }

    function configToken()
    {
        $cnf['exp'] = 3600; //milisecond
        $cnf['secretkey'] = '2212336221';
        return $cnf;
    }
    public function users_get()
    {
        // Users from a data store e.g. database
        // $users = [
        //     ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
        //     ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        // ];

        // $id = $this->get( 'id' );

        // if ( $id === null )
        // {
        //     // Check if the users data store contains users
        //     if ( $users )
        //     {
        //         // Set the response and exit
        //         $this->response( $users, 200 );
        //     }
        //     else
        //     {
        //         // Set the response and exit
        //         $this->response( [
        //             'status' => false,
        //             'message' => 'No users were found'
        //         ], 404 );
        //     }
        // }
        // else
        // {
        //     if ( array_key_exists( $id, $users ) )
        //     {
        //         $this->response( $users[$id], 200 );
        //     }
        //     else
        //     {
        //         $this->response( [
        //             'status' => false,
        //             'message' => 'No such user found'
        //         ], 404 );
        //     }
        // }

        // $this->response([
        //     'status' => 200,
        //     'data'   => $this->Users_model->getUsers()
        // ],RestController::HTTP_OK);

        // 


        // $key = 'example_key';
        // $payload = [
        //     'iss' => 'http://example.org',
        //     'aud' => 'http://example.com',
        //     'iat' => 1356999524,
        //     'nbf' => 1357000000
        // ];

        // /**
        //  * IMPORTANT:
        //  * You must specify supported algorithms for your application. See
        //  * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
        //  * for a list of spec-compliant algorithms.
        //  */
        // $jwt = JWT::encode($payload, $key, 'HS256');
        // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        // print_r($decoded);

        // /*
        // NOTE: This will now be an object instead of an associative array. To get
        // an associative array, you will need to cast it as such:
        // */

        // $decoded_array = (array) $decoded;

        // /**
        //  * You can add a leeway to account for when there is a clock skew times between
        //  * the signing and verifying servers. It is recommended that this leeway should
        //  * not be bigger than a few minutes.
        //  *
        //  * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
        //  */
        // JWT::$leeway = 60; // $leeway in seconds
        // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    }

    public function getToken_get()
    {
        $exp = time() + 3600;
        $token = array(
            "iss" => 'apprestservice',
            "aud" => 'pengguna',
            "iat" => time(),
            "nbf" => time() + 10,
            "exp" => $exp,
            "data" => array(
                "username" => "rizki",
                "password" => "kepo ya"
            )
        );

        $jwt = JWT::encode($token, $this->configToken()['secretkey'], 'HS256');
        $output = [
            'status' => 200,
            'message' => 'Berhasil login',
            "token" => $jwt,
            "expireAt" => $token['exp']
        ];
        $data = array('kode' => '200', 'pesan' => 'token', 'data' => array('token' => $jwt, 'exp' => $exp));
        $this->response($data, 200);
    }

    public function authtoken_post()
    {
        $header = $this->input->request_headers()['Authorization'];  
        // if (!$header) return $this->response(['message' => ' Token required'],404);
        $token = explode(' ', $header)[1];

        try {
            $decoded = JWT::decode($token, new Key ($this->configToken()['secretkey'], 'HS256'));
            // $response = [
            //     'id' => $decoded->uid,
            //     'email' => $decoded->email
            // ];
            return $this->response(['data_decode' =>$decoded]);
        } catch (\Throwable $th) {
            return $this->response(['message' => 'Invalid Token'],404);
        }
    }
}
