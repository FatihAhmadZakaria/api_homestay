<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageableResource\Pages;
use App\Models\Imageable;
use App\Models\Properti;
use App\Models\Kendaraan;
use App\Models\Promo;
use App\Models\Objek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ImageableResource extends Resource
{
    protected static ?string $model = Imageable::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'imageables';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('img_path')->label('Image Path'),
                Tables\Columns\TextColumn::make('imageable_id')->label('Imageable ID'),
                Tables\Columns\TextColumn::make('imageable_type')->label('Imageable Type'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // File Upload untuk Gambar
                Forms\Components\FileUpload::make('img_path')
                    ->label('Unggah Gambar')
                    ->required()
                    ->afterStateUpdated(function ($state) {
                        // Hanya menyimpan nama file di img_path
                        return basename($state);
                    }),

                // Dropdown untuk memilih tipe entitas
                Forms\Components\Select::make('imageable_type')
                    ->label('Tipe Entitas')
                    ->options([
                        Properti::class => 'Properti',
                        Kendaraan::class => 'Kendaraan',
                        Objek::class => 'Objek Sekitar',
                        Promo::class => 'Promo',
                    ])
                    ->reactive()
                    ->required(),

                // Dropdown untuk memilih entitas berdasarkan tipe yang dipilih
                Forms\Components\Select::make('imageable_id')
                    ->label('Pilih Entitas')
                    ->required()
                    ->options(function (callable $get) {
                        $type = $get('imageable_type');
                        if ($type === Properti::class) {
                            return Properti::pluck('nama_properti', 'id_properti');
                        } elseif ($type === Kendaraan::class) {
                            return Kendaraan::pluck('nama_kendaraan', 'id_kendaraan');
                        } elseif ($type === Objek::class) {
                            return Objek::pluck('nama_objek', 'id_objek');
                        } elseif ($type === Promo::class) {
                            return Promo::pluck('nama_promo', 'id_promo');
                        }
                        return [];
                    })
                    ->hidden(fn (callable $get) => empty($get('imageable_type'))),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImageables::route('/'),
            'create' => Pages\CreateImageable::route('/create'),
            'edit' => Pages\EditImageable::route('/{record}/edit'),
        ];
    }
}
