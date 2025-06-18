<?php

namespace App\Filament\Resources;

use App\Data\RegionData;
use App\Filament\Resources\SalesOrderResource\Pages;
use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Models\Region;
use App\Models\SalesOrder;
use App\Services\RegionQueryService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class SalesOrderResource extends Resource
{
    protected static ?string $model = SalesOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make("Sales Order General Information")
                    ->description("Meta & Customer Info")
                    ->schema([
                        TextEntry::make('trx_id')
                            ->label("TRX ID")
                            ->inlineLabel(),
                        TextEntry::make('status')
                            ->formatStateUsing(fn($state) => $state->label())
                            ->inlineLabel(),
                        TextEntry::make('due_date_at')
                            ->label("Due Date")
                            ->inlineLabel(),
                        TextEntry::make('customer_full_name')
                            ->label("Customer Name")
                            ->inlineLabel(),
                        TextEntry::make('customer_email')
                            ->label("Customer Email")
                            ->inlineLabel(),
                        TextEntry::make('customer_phone')
                            ->label("Customer Phone")
                            ->inlineLabel(),
                        TextEntry::make('address_line')
                            ->label("Shipping Address")
                            ->inlineLabel()
                            ->formatStateUsing(function ($state, SalesOrder $sales_order) {
                                // $region = RegionData::fromModel(
                                //     Region::query()->where('code', $sales_order->destination_code)->first()
                                // );
                                $region = app(RegionQueryService::class)->searchRegionByCode(
                                    $sales_order->destination_code
                                );
                                return "$state {$region->label}";
                            })
                    ]),
                Section::make("Shipping Details")
                    ->collapsed()
                    ->schema([
                        TextEntry::make('shipping_driver')
                            ->label("Vendor")
                            ->inlineLabel(),
                        TextEntry::make('shipping_courier')
                            ->inlineLabel(),
                        TextEntry::make('shipping_service')
                            ->inlineLabel(),
                        TextEntry::make('shipping_estimated_delivery')
                            ->inlineLabel(),
                        TextEntry::make('shipping_weight')
                            ->suffix(" Gram")
                            ->inlineLabel(),
                        TextEntry::make('shipping_receipt_number')
                            ->inlineLabel()
                    ]),
                RepeatableEntry::make('items')
                    ->schema([
                        TextEntry::make('name')
                            ->formatStateUsing(fn($state, Model $record) => "({$record->sku}) $state"),
                        TextEntry::make('quantity'),
                        TextEntry::make('price')
                            ->formatStateUsing(fn($state) => Number::currency($state)),
                        TextEntry::make('total')
                            ->formatStateUsing(fn($state) => Number::currency($state))
                    ])
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->columns(4),
                Section::make('Summaries')
                    ->schema([
                        TextEntry::make('payment_label')
                            ->inlineLabel(),
                        TextEntry::make('payment_paid_at')
                            ->label('Paid At')
                            ->inlineLabel(),
                        TextEntry::make('sub_total')
                            ->label('Sub Total')
                            ->formatStateUsing(fn($state) => Number::currency($state))
                            ->inlineLabel(),
                        TextEntry::make('shipping_total')
                            ->label('Shipping Total')
                            ->formatStateUsing(fn($state) => Number::currency($state))
                            ->inlineLabel(),
                        TextEntry::make('total')
                            ->label('Total')
                            ->formatStateUsing(fn($state) => Number::currency($state))
                            ->inlineLabel()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trx_id'),
                TextColumn::make('customer_full_name'),
                TextColumn::make('status')
                    ->formatStateUsing(fn($state) => $state->label()),
                TextColumn::make('total')
                    ->formatStateUsing(fn($state) => Number::currency($state))
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesOrders::route('/'),
            // 'create' => Pages\CreateSalesOrder::route('/create'),
            // 'edit' => Pages\EditSalesOrder::route('/{record}/edit'),
            'view' => Pages\ViewSalesOrder::route('/{record}')
        ];
    }
}
