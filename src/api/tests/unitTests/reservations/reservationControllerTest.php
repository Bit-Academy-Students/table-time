<?php

use PHPUnit\Framework\TestCase;
use App\Reservations\ReservationController\ReservationController;
use App\Reservations\ReservationService\ReservationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Reservations\reservationEntity\ReservationEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;
use Symfony\Component\HttpFoundation\Response;

final class reservationControllerTest extends TestCase
{
    private ReservationController $ReservationController;
    private ReservationService $ReservationServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ReservationServiceMock = $this->createMock(ReservationService::class);
        $this->ReservationController = new ReservationController($this->ReservationServiceMock);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    // dit test de create functie in de controller
    public function testCreate(): void
    {
        $reservationEntity = new ReservationEntity();
        $reservationEntity->setId(1);
        $reservationEntity->setStartDate(new \DateTimeImmutable('2025-12-11 12:00:00'));
        $reservationEntity->setEndDate(new \DateTimeImmutable('2025-12-11 14:00:00'));
        $reservationEntity->setAmountPeople(4);
        $reservationEntity->setEmail('alsdjf@aklsdjf.nl');
        $restaurantEntity = new RestaurantEntity();
        $restaurantEntity->setId(1);
        $restaurantEntity->setNaam('Test Restaurant');
        $reservationEntity->setRestaurant($restaurantEntity);

        $this->ReservationServiceMock
            ->method('createReservation')
            ->willReturn($reservationEntity);
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode([
                'id' => 1,
                'startDate' => '2025-12-11 12:00:00',
                'endDate' => '2025-12-11 14:00:00',
                'amountPeople' => 4,
                'email' => 'test@example.com',
                'restaurantId' => 1,  
        ]));

        $expected = $this->ReservationController->create($request);
        $actual = new JsonResponse(["Reservation"=>["id"=>1,"startDate"=>"2025-12-11 12:00:00","endDate"=>"2025-12-11 14:00:00","amountPeople"=>4,"email"=>"alsdjf@aklsdjf.nl","restaurant"=>["id"=>1,"naam"=>"Test Restaurant"]],"response"=>"created"], Response::HTTP_CREATED);
        $this->assertEquals($expected, $actual);
    }

    // dit test de create functie in de controller bij een exception
    public function testCreateException(): void
    {
        $reservationEntity = new ReservationEntity();
        $reservationEntity->setId(1);
        $reservationEntity->setStartDate(new \DateTimeImmutable('2025-12-11 12:00:00'));
        $reservationEntity->setEndDate(new \DateTimeImmutable('2025-12-11 14:00:00'));
        $reservationEntity->setAmountPeople(4);
        $reservationEntity->setEmail('alsdjf@aklsdjf.nl');

        $this->ReservationServiceMock
            ->method('createReservation')
            ->willReturn($reservationEntity);
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode([
                'id' => 1,
                'startDate' => '2025-12-11 12:00:00',
                'endDate' => '2025-12-11 14:00:00',
                'amountPeople' => 4,
                'email' => 'test@example.com',
                'restaurantId' => null,  
        ]));

        $expected = $this->ReservationController->create($request);
        $actual = new JsonResponse(["error"=>"restaurantId is verplicht"], Response::HTTP_BAD_REQUEST);
        $this->assertEquals($expected, $actual);
    }

    public function testDelete(): void
    {
        $this->ReservationServiceMock
            ->method('deleteReservation')
            ->willReturn(true);

        $expected = $this->ReservationController->delete(1);
        $actual = new JsonResponse(["response"=>"deleted"], Response::HTTP_OK);
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteNotFound(): void
    {
        $this->ReservationServiceMock
            ->method('deleteReservation')
            ->willReturn(false);

        $expected = $this->ReservationController->delete(999);
        $actual = new JsonResponse(["error"=>"Reservering niet gevonden"], Response::HTTP_NOT_FOUND);
        $this->assertEquals($expected, $actual);
    }

    public function testUpdate(): void
    {
        $reservationEntity = new ReservationEntity();
        $reservationEntity->setId(1);
        $reservationEntity->setStartDate(new \DateTimeImmutable('2025-12-11 12:00:00'));
        $reservationEntity->setEndDate(new \DateTimeImmutable('2025-12-11 14:00:00'));
        $reservationEntity->setAmountPeople(4);
        $reservationEntity->setEmail('alsdjf@aklsdjf.nl');
        $restaurantEntity = new RestaurantEntity();
        $restaurantEntity->setId(1);
        $restaurantEntity->setNaam('Test Restaurant');
        $reservationEntity->setRestaurant($restaurantEntity);
        $this->ReservationServiceMock
            ->method('updateReservation')
            ->willReturn($reservationEntity);
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode([
                'startDate' => '2025-12-11 12:00:00',
                'endDate' => '2025-12-11 14:00:00',
                'amountPeople' => 4,
                'email' => 'alsdjf@aklsdjf.nl',
                'restaurantId' => 1,  
        ]));
        $actual = $this->ReservationController->update(1, $request);
        $expected = new JsonResponse(["Reservation"=>["id"=>1,"startDate"=>"2025-12-11 12:00:00","endDate"=>"2025-12-11 14:00:00","amountPeople"=>4,"email"=>"alsdjf@aklsdjf.nl"],"response"=>"updated"], Response::HTTP_OK);
        $this->assertEquals($expected, $actual);
    }

    public function testUpdateException(): void
    {
        $this->ReservationServiceMock
            ->method('updateReservation')
            ->willReturn(null);
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn(json_encode([
                'startDate' => '2025-12-11 12:00:00',
                'endDate' => '2025-12-11 14:00:00',
                'amountPeople' => 4,
                'email' => 'alsdjf@aklsdjf.nl',
                'restaurantId' => 1,
        ]));
        $expected = $this->ReservationController->update(999, $request);
        $actual = new JsonResponse(["error"=>"Reservering niet gevonden"], Response::HTTP_NOT_FOUND);
        $this->assertEquals($expected, $actual);
    }

}