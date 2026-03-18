<?php

use PHPUnit\Framework\TestCase;
use App\Reservations\ReservationService\ReservationService;
use App\Reservations\ReservationRepository\ReservationRepository;
use App\Restaurants\RestaurantRepository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Reservations\reservationEntity\ReservationEntity;
use App\Restaurants\RestaurantEntity\RestaurantEntity;

final class reservationServiceTest extends TestCase
{
    private ReservationService $ReservationService;
    private ReservationRepository $ReservationRepositoryMock;
    private RestaurantRepository $RestaurantRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ReservationService = new ReservationService(
            $this->ReservationRepositoryMock = $this->createMock(ReservationRepository::class),
            $this->RestaurantRepositoryMock = $this->createMock(RestaurantRepository::class),
            $this->createMock(EntityManagerInterface::class)
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    // dit test de create functie in de controller
    public function testCreate(): void
    {
        $reservationEntity = new ReservationEntity();
        $reservationEntity->setStartDate(new \DateTimeImmutable('2025-12-11 12:00:00'));
        $reservationEntity->setEndDate(new \DateTimeImmutable('2025-12-11 14:00:00'));
        $reservationEntity->setAmountPeople(4);
        $reservationEntity->setEmail('alsdjf@aklsdjf.nl');
        $restaurantEntity = new RestaurantEntity();
        $restaurantEntity->setId(1);
        $restaurantEntity->setNaam('Test Restaurant');
        $reservationEntity->setRestaurant($restaurantEntity);


        $this->RestaurantRepositoryMock
            ->method('find')
            ->willReturn($restaurantEntity);

        $this->ReservationRepositoryMock
            ->method('save');

        $actual = $this->ReservationService->createReservation([
            'startDate' => '2025-12-11 12:00:00',
            'endDate' => '2025-12-11 14:00:00',
            'amountPeople' => 4,
            'email' => 'alsdjf@aklsdjf.nl',
            'restaurantId' => $restaurantEntity
            ]);
        $expected = $reservationEntity;
        $this->assertEquals($expected, $actual);
    }

    // dit test de create functie in de controller bij een exception
    public function testCreateException(): void
    {
        $this->expectExceptionMessage("Restaurant ID is required");

        $reservationEntity = new ReservationEntity();
        $reservationEntity->setId(1);
        $reservationEntity->setStartDate(new \DateTimeImmutable('2025-12-11 12:00:00'));
        $reservationEntity->setEndDate(new \DateTimeImmutable('2025-12-11 14:00:00'));
        $reservationEntity->setAmountPeople(4);
        $reservationEntity->setEmail('alsdjf@aklsdjf.nl');

        $this->ReservationRepositoryMock
            ->method('save');


        $this->ReservationService->createReservation([
            'startDate' => '2025-12-11 12:00:00',
            'endDate' => '2025-12-11 14:00:00',
            'amountPeople' => 4,
            'email' => 'alsdjf@aklsdjf.nl',
            'restaurantId' => null
            ]);
    }
}