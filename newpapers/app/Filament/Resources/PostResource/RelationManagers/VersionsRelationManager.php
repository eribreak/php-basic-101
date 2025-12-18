<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Phiên bản';

    protected static ?string $modelLabel = 'Phiên bản';

    protected static ?string $pluralModelLabel = 'Phiên bản';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('version_number')
                    ->label('Số phiên bản')
                    ->required()
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Tóm tắt')
                    ->rows(3)
                    ->disabled(),
                Forms\Components\RichEditor::make('content')
                    ->label('Nội dung')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                    ])
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version_number')
            ->columns([
                TextColumn::make('version_number')
                    ->label('Phiên bản')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->limit(50)
                    ->searchable(),
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
                TextColumn::make('creator.name')
                    ->label('Người tạo')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('view')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn ($record) => "Phiên bản #{$record->version_number}")
                    ->modalContent(function ($record) {
                        return view('filament.resources.post-resource.relation-managers.version-detail', [
                            'version' => $record,
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng'),
                Action::make('restore')
                    ->label('Khôi phục')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Khôi phục phiên bản')
                    ->modalDescription(fn ($record) => "Bạn có chắc muốn khôi phục phiên bản #{$record->version_number}? Nội dung hiện tại sẽ được lưu thành version mới.")
                    ->modalSubmitActionLabel('Khôi phục')
                    ->action(function ($record) {
                        $post = $this->getOwnerRecord();
                        $versionRepository = app(\App\Repositories\Contracts\PostVersionRepositoryInterface::class);

                        $currentVersionNumber = $versionRepository->getMaxVersionNumber($post);
                        $versionRepository->createVersion($post, $currentVersionNumber + 1);

                        \App\Models\Post::withoutEvents(function () use ($post, $record) {
                            $post->update([
                                'title' => $record->title,
                                'slug' => $record->slug,
                                'excerpt' => $record->excerpt,
                                'content' => $record->content,
                                'status' => $record->status,
                                'thumbnail' => $record->thumbnail,
                                'published_at' => $record->published_at,
                            ]);
                        });

                        Notification::make()
                            ->title('Đã khôi phục phiên bản thành công')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('version_number', 'desc');
    }
}
