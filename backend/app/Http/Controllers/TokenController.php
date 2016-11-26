<?php

namespace VkMusic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use VkMusic\Http\Requests\TokenRequest;
use VkMusic\Models\Token;
use VkMusic\Models\User;

class TokenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param TokenRequest $request
     * @return Response|JsonResponse
     */
    public function store(TokenRequest $request)
    {
        $response = json_decode($request->get('api_result'), true)['response'][0];


        $user = User::firstOrCreate([
            'uid' => $request->viewer_id
        ], [
            'first_name' => $response['first_name'],
            'last_name' => $response['last_name'],
            'nickname' => $response['nickname']
        ]);

        $data = $request->all();

        $token = new Token([
            'data' => $data
        ]);

        $tt = $token->generateToken(json_encode($data));

        $token->token = $tt;

        $token->user()->associate($user);

        $token->save();

        return new JsonResponse([
            'token' => $token->token
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
