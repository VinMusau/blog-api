<?php

namespace App\Http\Resources;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Tables\Table;

class DonationResource extends JsonResource
{
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('user.name')->label('Donor'),
            TextColumn::make('amount')->money(fn($record) => $record->currency),
            TextColumn::make('method')->badge(),
            IconColumn::make('status')
                ->icons([
                    'heroicon-o-clock' => 'pending',
                    'heroicon-o-check' => 'completed',
                    'heroicon-o-x' => 'failed',
                ])
                ->colors([
                    'warning' => 'pending',
                    'success' => 'completed',
                    'danger' => 'failed',
                ]),
        ]);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
