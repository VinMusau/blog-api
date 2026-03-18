<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Post;
use App\Models\Donation;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Posts', Post::count())
                ->description('Live on the site')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
/*
            Stat::make('Total Reads', Post::sum('views'))
                ->description('Across all posts')
                ->descriptionIcon('heroicon-m-eye')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            
        */
            Stat::make('Global Likes', Post::withCount('likes')
                ->get()
                ->sum('likes_count'))
                ->description('All-time likes')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),

            Stat::make('Total Donations', Donation::where('status', 'completed')->sum('amount'))
                ->description('Coffees from readers')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
