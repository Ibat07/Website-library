<?php

namespace App\Filament\User\Pages;

use App\Models\Category;
use App\Models\Transaction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Colors\Color;

class LoanBook extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.loan-book';

    public function table(Table $table): Table
    {
        $data = Auth::user()->id;
        $name = Auth::user()->name;

        return $table
            ->contentGrid([
                'md' => 3,
                'xl' => 4
            ])
            ->query(Transaction::query()->where('user_id', $data))
            ->columns([
                Split::make([
                    Tables\Columns\ImageColumn::make('books.gambar')
                        ->size(170)
                        ->label('Gambar')
                        ->disk('public'),
                    Tables\Columns\TextColumn::make('books.title')
                ]),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->color(Color::Green)
                    ->icon('')
                    ->label('Returned Book')
                    ->modalIcon('')
                    ->modalHeading( fn ($record): string => "Returned Book" )
                    ->modalDescription('Are you sure returned this book?')
                    ->successNotificationTitle('Book Returned!')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('books.categories', 'name')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')),
            ]);
    }
}
