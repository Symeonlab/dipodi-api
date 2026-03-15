<?php

namespace Tests\Unit;

use App\Http\Responses\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    /**
     * Test success response structure.
     */
    public function test_success_response_has_correct_structure(): void
    {
        $response = ApiResponse::success(['key' => 'value'], 'Success message');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Success message', $data['message']);
        $this->assertEquals(['key' => 'value'], $data['data']);
    }

    /**
     * Test success response without data.
     */
    public function test_success_response_without_data(): void
    {
        $response = ApiResponse::success(null, 'Operation completed');

        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Operation completed', $data['message']);
        $this->assertArrayNotHasKey('data', $data);
    }

    /**
     * Test created response.
     */
    public function test_created_response(): void
    {
        $response = ApiResponse::created(['id' => 1], 'Resource created');

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Resource created', $data['message']);
    }

    /**
     * Test error response structure.
     */
    public function test_error_response_has_correct_structure(): void
    {
        $response = ApiResponse::error('An error occurred', 400);

        $this->assertEquals(400, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('An error occurred', $data['message']);
    }

    /**
     * Test error response with validation errors.
     */
    public function test_error_response_with_errors_array(): void
    {
        $errors = ['email' => ['Email is required']];
        $response = ApiResponse::error('Validation failed', 422, $errors);

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals($errors, $data['errors']);
    }

    /**
     * Test validation error response.
     */
    public function test_validation_error_response(): void
    {
        $errors = ['name' => ['Name is required'], 'email' => ['Email is invalid']];
        $response = ApiResponse::validationError($errors);

        $this->assertEquals(422, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
        $this->assertEquals($errors, $data['errors']);
    }

    /**
     * Test not found response.
     */
    public function test_not_found_response(): void
    {
        $response = ApiResponse::notFound('User not found');

        $this->assertEquals(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('User not found', $data['message']);
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized_response(): void
    {
        $response = ApiResponse::unauthorized();

        $this->assertEquals(401, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Unauthorized', $data['message']);
    }

    /**
     * Test forbidden response.
     */
    public function test_forbidden_response(): void
    {
        $response = ApiResponse::forbidden('Access denied');

        $this->assertEquals(403, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Access denied', $data['message']);
    }

    /**
     * Test server error response.
     */
    public function test_server_error_response(): void
    {
        $response = ApiResponse::serverError();

        $this->assertEquals(500, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Internal server error', $data['message']);
    }

    /**
     * Test too many requests response.
     */
    public function test_too_many_requests_response(): void
    {
        $response = ApiResponse::tooManyRequests();

        $this->assertEquals(429, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Too many requests. Please try again later.', $data['message']);
    }

    /**
     * Test no content response.
     */
    public function test_no_content_response(): void
    {
        $response = ApiResponse::noContent();

        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test paginated response structure.
     */
    public function test_paginated_response_has_meta_and_links(): void
    {
        $items = collect([['id' => 1], ['id' => 2]]);
        $paginator = new LengthAwarePaginator(
            $items,
            10,
            2,
            1,
            ['path' => 'http://localhost']
        );

        $response = ApiResponse::success($paginator);

        $data = json_decode($response->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('meta', $data);
        $this->assertArrayHasKey('links', $data);

        // Check meta structure
        $this->assertEquals(1, $data['meta']['current_page']);
        $this->assertEquals(5, $data['meta']['last_page']);
        $this->assertEquals(2, $data['meta']['per_page']);
        $this->assertEquals(10, $data['meta']['total']);

        // Check links structure
        $this->assertArrayHasKey('first', $data['links']);
        $this->assertArrayHasKey('last', $data['links']);
        $this->assertArrayHasKey('prev', $data['links']);
        $this->assertArrayHasKey('next', $data['links']);
    }
}
