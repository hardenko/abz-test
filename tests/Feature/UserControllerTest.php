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
    public function testCreateUser($payload, ?string $token, int $expectedStatus, array $expectedResponse): void
    {
        if (is_callable($payload)) {
            $payload = $payload();
        }

        $headers = [];
        if ($token !== null) {
            $encodedToken = base64_encode($token);
            Cache::put("auth_token_$encodedToken", 'valid', now()->addMinutes(40));
            $headers = ['Authorization' => "Bearer $encodedToken"];
        }

        $response = $this->postJson('/api/users', $payload, $headers);

        if (isset($expectedResponse['exact'])) {
            $response->assertStatus($expectedStatus)
                ->assertExactJson($expectedResponse['exact']);
        } else {
            $response->assertStatus($expectedStatus)
                ->assertJsonStructure($expectedResponse['structure']);
        }
    }

    #[DataProvider('provideGetUserList')]
    public function testGetUserList(int $usersCount, ?array $queryParams, int $expectedStatus, array $expectedResponse): void
    {
        Position::factory()->count(5)->create();
        User::factory()->count($usersCount)->create();

        $url = "/api/users";
        if ($queryParams) {
            $url .= '?' . http_build_query($queryParams);
        }

        $response = $this->getJson($url);

        if (isset($expectedResponse['exact'])) {
            $response->assertStatus($expectedStatus)
                ->assertExactJson($expectedResponse['exact']);
        } else {
            $response->assertStatus($expectedStatus)
                ->assertJsonStructure($expectedResponse['structure']);
        }
    }

    #[DataProvider('provideGetUser')]
    public function testGetUser($userId, int $expectedStatus, array $expectedResponse): void
    {
        if ($userId === 'exists') {
            Position::factory()->create();
            $user = User::factory()->create();
            $userId = $user->id;
        }

        $response = $this->getJson("/api/users/$userId");

        if (isset($expectedResponse['exact'])) {
            $response->assertStatus($expectedStatus)
                ->assertExactJson($expectedResponse['exact']);
        } else {
            $response->assertStatus($expectedStatus)
                ->assertJsonStructure($expectedResponse['structure']);
        }
    }

    public static function provideCreateUser(): array
    {
        return [
            'success - valid data' => [
                'payload' => function () {
                    $position = Position::factory()->create();

                    return [
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'phone' => '+380500500501',
                        'position_id' => $position->id,
                        'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(100),
                    ];
                },
                'token' => 'test-token',
                'expectedStatus' => 201,
                'expectedResponse' => [
                    'structure' => [
                        "success",
                        "user_id",
                        "message",
                    ]
                ]
            ],
            'failure - missing required fields' => [
                'payload' => [
                    'name' => 'Test User',
                    // Missing email, phone, position_id, photo
                ],
                'token' => 'test-token',
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'exact' => [
                        'success' => false,
                        'message' => 'Validation failed',
                        'fails' => [
                            'email' => ['The email field is required.'],
                            'phone' => ['The phone field is required.'],
                            'position_id' => ['The position id field is required.'],
                            'photo' => ['The photo field is required.'],
                        ]
                    ]
                ]
            ],
            'failure - invalid auth token' => [
                'payload' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'phone' => '+380500500501',
                    'position_id' => 1,
                    'photo' => UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(100),
                ],
                'token' => null,
                'expectedStatus' => 401,
                'expectedResponse' => [
                    'exact' => [
                        'success' => false,
                        'message' => 'The token expired.'
                    ]
                ]
            ],
        ];
    }

    public static function provideGetUserList(): array
    {
        return [
            'success - default parameters' => [
                'usersCount' => 45,
                'queryParams' => null,
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'structure' => self::successGetUserListResponse()
                ]
            ],
            'success - custom page and count' => [
                'usersCount' => 45,
                'queryParams' => ['page' => 2, 'count' => 10],
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'structure' => self::successGetUserListResponse()
                ]
            ],
            'failure - page not found' => [
                'usersCount' => 10,
                'queryParams' => ['page' => 100],
                'expectedStatus' => 404,
                'expectedResponse' => [
                    'exact' => self::userListNotFoundPageResponse()
                ]
            ],
            'failure - validation fails' => [
                'usersCount' => 10,
                'queryParams' => ['page' => 0, 'count' => 'a'],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'exact' => self::userListValidationFailResponse()
                ]
            ],
        ];
    }

    public static function provideGetUser(): array
    {
        return [
            'success - user exists' => [
                'userId' => 'exists',
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'structure' => self::successGetUserResponse()
                ]
            ],
            'failure - non-integer id' => [
                'userId' => 'a',
                'expectedStatus' => 400,
                'expectedResponse' => [
                    'exact' => self::userNotIntegerIdResponse()
                ]
            ],
            'failure - user not found' => [
                'userId' => 100000,
                'expectedStatus' => 404,
                'expectedResponse' => [
                    'exact' => self::userNotFoundIdResponse()
                ]
            ],
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
