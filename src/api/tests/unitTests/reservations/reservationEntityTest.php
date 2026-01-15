<?php

use PHPUnit\Framework\TestCase;
use App\Reservations\ReservationEntity\ReservationEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;

final class reservationEntityTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    // dit test de create functie in de controller
    public function testEmail(): void
    {
        $reservationEntity = new ReservationEntity();
        $reservationEntity->setId(1);
        $this->assertEquals(1, $reservationEntity->getId());
    }

    // dit test de create functie in de controller bij een exception
    public function testRestaurant(): void
    {
        $reservationEntity = new ReservationEntity();
        $restaurantEntity = new RestaurantEntity();
        $restaurantEntity->setId(1);
        $reservationEntity->setRestaurant($restaurantEntity);
        $this->assertEquals($restaurantEntity, $reservationEntity->getRestaurant());
    }
}