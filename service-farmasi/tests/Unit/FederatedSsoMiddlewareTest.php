<?php

namespace Tests\Unit;

use App\Http\Middleware\FederatedSsoMiddleware;
use PHPUnit\Framework\TestCase;

class FederatedSsoMiddlewareTest extends TestCase
{
    public function test_maps_doctor_and_staff_roles_to_apoteker()
    {
        $middleware = new FederatedSsoMiddleware();

        $method = new \ReflectionMethod($middleware, 'mapSsoRoleToLocalRole');
        $method->setAccessible(true);

        $this->assertSame('apoteker', $method->invoke($middleware, 'dokter'));
        $this->assertSame('apoteker', $method->invoke($middleware, 'doctor'));
        $this->assertSame('apoteker', $method->invoke($middleware, 'staff'));
    }

    public function test_maps_admin_role_to_admin_farmasi()
    {
        $middleware = new FederatedSsoMiddleware();

        $method = new \ReflectionMethod($middleware, 'mapSsoRoleToLocalRole');
        $method->setAccessible(true);

        $this->assertSame('admin_farmasi', $method->invoke($middleware, 'admin'));
    }
}
