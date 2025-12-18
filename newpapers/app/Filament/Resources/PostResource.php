<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Bài viết';

    protected static ?string $modelLabel = 'Bài viết';

    protected static ?string $pluralModelLabel = 'Bài viết';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL-friendly title'),

                        FileUpload::make('thumbnail')
                            ->label('Ảnh thumbnail')
                            ->image()
                            ->directory('thumbnails')
                            ->disk('public')
                            ->visibility('public')
                            ->imagePreviewHeight('200')
                            ->maxSize(2048)
                            ->helperText('Tùy chọn, khuyến khích tỷ lệ 16:9'),

                        Textarea::make('excerpt')
                            ->label('Tóm tắt')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Mô tả ngắn về bài viết'),
                    ]),

                Section::make('Nội dung')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Nội dung')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Phân loại & Trạng thái')
                    ->schema([
                        Select::make('user_id')
                            ->label('Tác giả')
                            ->relationship('author', 'name')
                            ->required()
                            ->default(fn() => auth()->id())
                            ->searchable()
                            ->preload(),

                        Select::make('categories')
                            ->label('Danh mục')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Select::make('keywords')
                            ->label('Keyword')
                            ->relationship('keywords', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Tên')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: 'keywords', column: 'slug'),
                            ])
                            ->helperText('Chọn hoặc tạo nhanh keyword mới.'),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'draft' => 'Nháp',
                                'published' => 'Đã xuất bản',
                            ])
                            ->required()
                            ->default('draft'),

                        DateTimePicker::make('published_at')
                            ->label('Ngày xuất bản')
                            ->displayFormat('d/m/Y H:i')
                            ->helperText('Chỉ hiển thị khi status = "Đã xuất bản"'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->disk('public')
                    ->square()
                    ->height(48),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('author.name')
                    ->label('Tác giả')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        default => $state,
                    }),

                TextColumn::make('categories.name')
                    ->label('Danh mục')
                    ->badge()
                    ->separator(','),

                TextColumn::make('keywords.name')
                    ->label('Keyword')
                    ->badge()
                    ->separator(',')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('views_count')
                    ->label('Lượt xem')
                    ->counts('views')
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Tác giả')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()?->isAdmin() === true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isAdmin() === true),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->isAdmin()) {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            PostResource\RelationManagers\VersionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
