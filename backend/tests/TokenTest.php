<?php

namespace VkMusic\Tests;
use VkMusic\Tests\Support\DatabaseTruncate;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TokenTest extends TestCase
{
    use DatabaseTruncate;

    public function testPost() {
        $userData = [
            'uid' => 3108667,
            'first_name' => 'Сергей',
            'last_name' => 'Мельников',
            'nickname' => ''
        ];

        $data = [
            'api_url' => 'https://api.vk.com/api.php',
            'api_id' => '5747571',
            'api_settings' => 1,
            'viewer_id' => 3108667,
            'viewer_type' => 4,
            'sid' => 'f05ace00a4b4f7819a19074ef45e96e064572b8ff62e2c8948fec86d70ac299ed46c5a6eb296bf8e46742',
            'secret' => '1417d4c2eb',
            'access_token' => '97125670d9b18f8e4f4fdc77f2a3099fc97f673a5ef5f8fe3062a3ad043e753733d1bb7aa30ed517e7878',
            'user_id' => 0,
            'group_id' => 133947888,
            'is_app_user' => 1,
            'auth_key' => '6c873241b6e8ab0341773cf6e21cb9ad',
            'parent_language' => 0,
            'ad_info' => 'ElsdCQJdQV1iAwNNRARQBHV7FAsmMQxVUUZGNgBQbwYfQyQrWQA=',
            'is_secure' => 1,
            'ads_app_id' => '5747571_83ee1fbd32b9777a96',
            'referrer' => 'unknown',
            'lc_name' => 81501025,
            'sign' => 'dd1e5480eea832e4ea32cb95d9108b39217eb987d2d68989a9ea2e424e174968',
            'hash' => '',
            'api_result' => json_encode([
                'response' => [$userData]
            ])
        ];

        $this->postJson('/api/token', $data);

        $this->assertResponseOk();

        $this->seeJsonStructure([
            'token'
        ]);

        $json = $this->decodeResponseJson();

        $this->seeInDatabase('users', [
            'uid' => $data['viewer_id'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'nickname' => $userData['nickname'],
        ]);

        $this->seeInDatabase('tokens', [
            'token' => $json['token'],
            'user_id' => $data['viewer_id'],
            'data' => json_encode($data)
        ]);
    }
}