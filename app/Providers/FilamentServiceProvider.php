<?php

namespace App\Providers;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Panel;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerPanels();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerDefaultFilamentColors();
        $this->registerPanelRenderHooks();

        $this->setConfigurations();
        $this->registerComponentMacros();
    }

    public function registerPanelRenderHooks(): void
    {
        FilamentView::registerRenderHook(
            name: PanelsRenderHook::PAGE_HEADER_ACTIONS_BEFORE,
            hook: fn (): View => \view('filament.published-date'),
            scopes: \config('filament.published_tagged_resources')
        );
    }

    public function setConfigurations(): void
    {
        Table::configureUsing(function (Table $table) {
            $table->actionsColumnLabel('Action')
                ->paginationPageOptions([10, 20, 50, 'all']);
        });

        // EditAction::configureUsing(function (EditAction $action) {
        //    $action->after(fn (EditAction $action) => $action->halt());
        // });
    }

    public function registerComponentMacros(): void
    {
        TextArea::macro('characterLimit', function ($limit = 3000, bool $hint = true) {
            $this
                ->live(debounce: 500)
                ->maxLength($limit)
                ->extraInputAttributes(['maxlength' => $limit])
                ->when(
                    $hint,
                    fn ($component) => $component->hint(function ($state, $component) {
                        $length = strlen($state);

                        return "$length / {$component->getMaxLength()} characters left";
                    })
                );

            return $this;
        });

        TextInput::macro('characterLimit', function ($limit = 300) {
            $this
                ->maxLength($limit)
                ->extraInputAttributes(['maxlength' => $limit]);

            return $this;
        });

        Repeater::macro('deletableItemsWhenExceeds', function (string|int|null $count = 1) {
            $this->deletable(function ($component, Get $get) use ($count) {
                return \count(Arr::get($get('./'), $component->getName(), [])) > $count;
            });

            return $this;
        });
    }

    private function registerPanels(): void
    {
        Panel::macro('guessUsingSubdomain', function () {
            /** @var Panel $this */
            $this->when(
                value: \config('filament.using_subdomain'),
                callback: fn (Panel $panel) => $panel->domain(\config('app.cms_url')),
                default: fn (Panel $panel) => $panel->path('admin')
            );

            return $this;
        });
    }

    private function registerDefaultFilamentColors(): void
    {
        FilamentColor::register([
            'primary'        => '#327892',
            'primary-1'      => '#6392A6',
            'primary-2'      => '#B0C8D2',
            'primary-3'      => '#ECF2F4',
            'secondary'      => '#373754',
            'tertiary'       => '#69697F',
            'background'     => '#FFFFFF',
            'surface'        => '#FFFFFF',
            'card'           => '#FFFFFF',
            'field-tag'      => '#F2F2F2',
            'stroke'         => '#E5E5E5',
            'success'        => '#458C03',
            'warning'        => '#FFA722',
            'error'          => '#F10021',
            'info'           => '#0093C9',
            'text-primary'   => '#050529',
            'text-secondary' => '#44445F',
            'text-tertiary'  => '#828294',
            'text-disabled'  => '#B4B4BF',
            'text-white'     => '#FFFFFF',
            'disabled'       => '#D7D7D8',
            'warning-modal'  => '#C47E09',
        ]);
    }
}
