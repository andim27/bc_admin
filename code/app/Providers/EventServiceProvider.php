<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SaleCreated' => [              // Продажа
            'App\Listeners\SaleCreated\AddPoint',  // Добавление баллов
            'App\Listeners\SaleCreated\AddMoney',  // Добавление денежных бонусов
            'App\Listeners\SaleCreated\AddStock',  // Добавление долей
            'App\Listeners\SaleCreated\CloseSale', // Закрытие продажи
            'App\Listeners\SaleCreated\RefreshUserData', // Обновление данных пользователя
        ],
        'App\Events\PointAdded' => [
            'App\Listeners\PointAdded\ReducePoint',          // Обработка начисленных баллов
            'App\Listeners\PointAdded\UpdatePersonalIncome', // Обновление информации по доходу от личных партнеров
        ],
        'App\Events\MoneyAdded' => [
            'App\Listeners\MoneyAdded\ReduceMoney', // Обработка начисленных денег
        ],
        'App\Events\StockAdded' => [
            'App\Listeners\StockAdded\ReduceStock', // Обработка начисленных долей
        ],
        'App\Events\SaleCanceled' => [                         // Продажа отменена
            'App\Listeners\SaleCanceled\CancelPoint',                       // Отмена начисленных баллов
            'App\Listeners\SaleCanceled\UpdatePersonalIncome', // Обновление информации по доходу от личных партнеров
            'App\Listeners\SaleCanceled\CancelMoney',                       // Отмена начисленных денежных бонусов
            'App\Listeners\SaleCanceled\CancelStock',                       // Отмена начисленных долей
            'App\Listeners\SaleCanceled\CloseCancelSale',                   // Закрытие отмены продажи
        ],
        'App\Events\PointCanceled' => [
            'App\Listeners\PointCanceled\ReduceCancelPoint', // Обработка отмененных баллов
        ],
        'App\Events\MoneyCanceled' => [
            'App\Listeners\MoneyCanceled\ReduceCancelMoney', // Обработка отмененных денег
        ],
        'App\Events\StockCanceled' => [
            'App\Listeners\StockCanceled\ReduceCancelStock', // Обработка отмененных долей
        ],
        'App\Events\PointDebited' => [
            'App\Listeners\PointDebited\ReducePoint',    // Обработка начисленных баллов
            'App\Listeners\PointDebited\UpdateUserStep', // Обработка начисленных баллов
            'App\Listeners\PointDebited\AddStock',       // Добавление долей
            'App\Listeners\PointDebited\AddMoney',       // Добавление денежных бонусов
            'App\Listeners\PointDebited\UpdateStructureIncome', // Обновление информации по доходу от структуры
        ],
        'App\Events\UserStepUpdated' => [
            'App\Listeners\UserStepUpdated\UpdateCareer', // Обновление информации о карьере
        ],
        'App\Events\CareerUpdated' => [
            'App\Listeners\CareerUpdated\AddMoney', // Добавление денежных бонусов
            'App\Listeners\CareerUpdated\AddAutoBonus', // Добавление автобонуса
        ],
        'App\Events\AutoBonusAdded' => [
            'App\Listeners\AutoBonusAdded\ReduceAutoBonus', // Обработка начисленных авто-бонусов
        ],
        'App\Events\MentorBonusSetted' => [
            'App\Listeners\MentorBonusSetted\ReduceMentorBonus', // Обработка бонусов наставника
            'App\Listeners\MentorBonusSetted\AddMoney', // Добавление денежных бонусов за бонус наставника
        ],
        'App\Events\MentorBonusFinished' => [
            'App\Listeners\MentorBonusFinished\UpdateMentorBonusDate', // Обновление времени просчета
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

}
