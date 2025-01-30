<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users',User::query()->where('role_id', 2)->count())
            ->description(Member::where('created_at', '>=', now()->subDays(7))->count() . ' new this week')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),

            Stat::make('Books', Book::count())
            ->description(Book::where('created_at', '>=', now()->subDays(7))->count() . ' new this week')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),

            Stat::make('Book Loans', Transaction::count())
            ->description(Visitor::where('created_at', '>=', now()->subDays(7))->count() . ' new this week')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success')
        ];
    }
}
