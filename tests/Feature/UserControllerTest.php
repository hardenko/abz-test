<?php

namespace Feature;

use App\Http\Controllers\Api\UserController;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UserController::class)]
final class UserControllerTest extends TestCase
{
    #[DataProvider('provideCreateUser')]
    public function testCreateUser($expectedStatus, $expectedResponse): void
    {
        $position = Position::factory()->create();

        $token = base64_encode('test-token');
        Cache::put("auth_token_$token", 'valid', now()->addMinutes(40));

        $payload = [
            'name' => 'Test',
            'email' => 'test@example.com',
            'phone' => '+380500500501',
            'position_id' => $position->id,
            'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(100),
        ];

        $response = $this->postJson(
            '/api/users',
            $payload,
            ['Authorization' => "Bearer $token"]
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideGetUserList')]
    public function testGetUserList($expectedStatus, $expectedResponse): void
    {
        Position::factory()->count(5)->create();
        User::factory()->count(45)->create();

        $response = $this->makeCall("/api/users");

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideGetUser')]
    public function testGetUser($expectedStatus, $expectedResponse): void
    {
        Position::factory()->create();

        $user = User::factory()->create();

        $response = $this->makeCall("/api/users/$user->id",
        );

        $response->assertStatus($expectedStatus)
            ->assertJsonStructure($expectedResponse);
    }

    #[DataProvider('provideUserListNotFoundPage')]
    public function testUserListNotFoundPage($payload, $expectedStatus, $expectedResponse): void
    {
        $response = $this->makeCall(
            "/api/users",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertExactJson($expectedResponse);
    }

    #[DataProvider('provideUserListValidationFail')]
    public function testUserListValidationFail($payload, $expectedStatus, $expectedResponse): void
    {
        $response = $this->makeCall(
            "/api/users",
            $payload,
        );

        $response->assertStatus($expectedStatus)
            ->assertExactJson($expectedResponse);
    }

    #[DataProvider('provideUserNotIntegerId')]
    public function testUserUserNotIntegerId($expectedStatus, $expectedResponse): void
    {
        $response = $this->makeCall("/api/users/a");

        $response->assertStatus($expectedStatus)
            ->assertExactJson($expectedResponse);
    }

    #[DataProvider('provideUserNotFoundId')]
    public function testUserNotFoundId($expectedStatus, $expectedResponse): void
    {

        $response = $this->makeCall("/api/users/100000");

        $response->assertStatus($expectedStatus)
            ->assertExactJson($expectedResponse);
    }

    public static function provideCreateUser(): array
    {
        return [
            'create user status 201' => [
                'expectedStatus' => 201,
                'expectedResponse' => self::successCreateUserResponse(),
            ],
        ];
    }

    public static function provideGetUserList(): array
    {
        return [
            'get user list status 200 ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successGetUserListResponse(),
            ],
        ];
    }

    public static function provideGetUser(): array
    {
        return [
            'get user status 200 ' => [
                'expectedStatus' => 200,
                'expectedResponse' => self::successGetUserResponse(),
            ],
        ];
    }

    public static function provideUserListNotFoundPage(): array
    {
        return [
            'not found page status 404' => [
                'payload' => ['page' => 100],
                'expectedStatus' => 404,
                'expectedResponse' => self::userListNotFoundPageResponse(),
            ],
        ];
    }

    public static function provideUserListValidationFail(): array
    {
        return [
            'validation fail status 422' => [
                'payload' => [
                    'page' => 0,
                    'count' => 'a'
                ],
                'expectedStatus' => 422,
                'expectedResponse' => self::userListValidationFailResponse(),
            ],
        ];
    }

    public static function provideUserNotIntegerId(): array
    {
        return [
            'user not integer id status 400' => [
                'expectedStatus' => 400,
                'expectedResponse' => self::userNotIntegerIdResponse(),
            ],
        ];
    }

    public static function provideUserNotFoundId(): array
    {
        return [
            'user not found id status 404' => [
                'expectedStatus' => 404,
                'expectedResponse' => self::userNotFoundIdResponse(),
            ],
        ];
    }

    protected function getHttpMethod(): string
    {
        return 'GET';
    }

    private static function successCreateUserResponse(): array
    {
        return [
            "success",
            "user_id",
            "message",
        ];
    }

    private static function successGetUserListResponse(): array
    {
        return [
            "success",
            "page",
            "total_pages",
            "total_users",
            "count",
            "links" => [
                "next_url",
                "prev_url",
            ],
            'users' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'position',
                    'position_id',
                    'registration_timestamp',
                    'photo',
                ]
            ],
        ];
    }

    private static function successGetUserResponse(): array
    {
        return [
            "success",
            'user' => [
                'id',
                'name',
                'email',
                'phone',
                'position',
                'position_id',
                'registration_timestamp',
                'photo',
            ],
        ];
    }

    private static function userListNotFoundPageResponse(): array
    {
        return [
            "success" => false,
            "message" => 'Page not found',
        ];
    }

    private static function userListValidationFailResponse(): array
    {
        return [
            "success" => false,
            "message" => 'Validation failed',
            "fails" => [
                "page" => [
                    "The page field must be at least 1."
                ],
                "count" => [
                    "The count field must be an integer."
                ]
            ]
        ];
    }

    private static function userNotIntegerIdResponse(): array
    {
        return [
            'success' => false,
            'message' => 'The user with the requested id does not exist.',
            'fails' => [
                'userId' => [
                    'The user ID must be an integer.'
                ]
            ],
        ];
    }

    private static function userNotFoundIdResponse(): array
    {
        return [
            'success' => false,
            'message' => 'User not found',
        ];
    }
}
