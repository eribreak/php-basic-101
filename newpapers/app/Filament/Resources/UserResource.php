<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Người dùng';

    protected static ?string $modelLabel = 'Người dùng';

    protected static ?string $pluralModelLabel = 'Người dùng';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin đăng nhập')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->minLength(8)
                            ->helperText('Để trống nếu không muốn thay đổi mật khẩu (khi chỉnh sửa)'),
                    ]),

                Section::make('Phân quyền')
                    ->schema([
                        Select::make('role')
                            ->label('Vai trò')
                            ->options([
                                'admin' => 'Admin',
                                'editor' => 'Editor',
                                'user' => 'User',
                            ])
                            ->required()
                            ->default('user')
                            ->helperText('Admin: toàn quyền. Editor: chỉ quản lý bài viết. User: chỉ đọc & bình luận.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Vai trò')
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'info' => 'editor',
                        'success' => 'user',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'editor' => 'Editor',
                        'user' => 'User',
                        default => $state,
                    }),

                TextColumn::make('posts_count')
                    ->label('Số bài viết')
                    ->counts('posts')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Vai trò')
                    ->options([
                        'admin' => 'Admin',
                        'editor' => 'Editor',
                        'user' => 'User',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
