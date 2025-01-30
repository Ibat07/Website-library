<?php

namespace App\Filament\User\Pages;

use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Split;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\Book as BookModel;
use Illuminate\Support\Facades\Auth;

class Book extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $model = BookModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.book';

    use InteractsWithForms;

    protected function getData(): ?Object
    {
        return BookModel::all();
    }

    public function table(Table $table): Table
    {
        $data = Auth::user()->id;
        $name = Auth::user()->name;

        return $table
            ->contentGrid([
                'md' => 3,
                'xl' => 4
            ])
            ->query(BookModel::query())
            ->columns([
                Split::make([
                    Tables\Columns\ImageColumn::make('gambar')
                        ->size(170)
                        ->label('Gambar')
                        ->disk('public'),
                    Tables\Columns\TextColumn::make('title')
                ]),
            ])
            ->actions([
                Tables\Actions\CreateAction::make()
                    ->button()
                    ->label('Borrow Book')
                    ->model(Transaction::class)
                    ->createAnother(false)
                    ->form(fn (BookModel $record) => [
                        Forms\Components\DatePicker::make('borrow_date')
                            ->maxDate(now())
                            ->required(),
                        Forms\Components\DatePicker::make('return_date')
                            ->required(),
                        Forms\Components\Hidden::make('user_id')
                            ->default($data)
                            ->required(),
                        Forms\Components\TextInput::make('borrowed_name')
                            ->label('Borrowed Name')
                            ->default($name)
                            ->disabled(),
                        Forms\Components\Hidden::make('books_id')
                            ->default($record->id)
                            ->required(),
                        Forms\Components\TextInput::make('book_title')
                            ->label('Book Title')
                            ->default($record->title)
                            ->disabled(),
                        Forms\Components\Hidden::make('status')
                            ->default(true)
                            ->required()
                    ])->action( function (array $data) {
                        $exist = Transaction::where('user_id', $data['user_id'])
                            ->where('books_id', $data['books_id'])
                            ->exists();

                        if ($exist) {
                            Notification::make()
                                ->danger()
                                ->title('Duplicate Entry')
                                ->body('You have already borrowed this book!')
                                ->send();

                            return;
                        }

                        Transaction::create([
                            'borrow_date' => $data['borrow_date'],
                            'return_date' => $data['return_date'],
                            'user_id' => $data['user_id'],
                            'books_id' => $data['books_id'],
                            'status' => $data['status'],
                        ]);

                        // Notify success
                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body('Book borrowed successfully!')
                            ->send();
                    }),
            ])->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('categories', 'name')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')),
            ]);
    }
}
