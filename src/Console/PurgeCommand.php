<?php

namespace Samsin33\Passport\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Samsin33\Passport\AuthCode;
use Samsin33\Passport\RefreshToken;
use Samsin33\Passport\Token;

class PurgeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:purge
                            {--revoked : Only purge revoked tokens and authentication codes}
                            {--expired : Only purge expired tokens and authentication codes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge revoked and / or expired tokens and authentication codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = Carbon::now()->subDays(7);

        if (($this->option('revoked') && $this->option('expired')) ||
            (! $this->option('revoked') && ! $this->option('expired'))) {
            Token::with([])->where('revoked', 1)->orWhereDate('expires_at', '<', $expired)->delete();
            AuthCode::with([])->where('revoked', 1)->orWhereDate('expires_at', '<', $expired)->delete();
            RefreshToken::with([])->where('revoked', 1)->orWhereDate('expires_at', '<', $expired)->delete();

            $this->info('Purged revoked items and items expired for more than seven days.');
        } elseif ($this->option('revoked')) {
            Token::with([])->where('revoked', 1)->delete();
            AuthCode::with([])->where('revoked', 1)->delete();
            RefreshToken::with([])->where('revoked', 1)->delete();

            $this->info('Purged revoked items.');
        } elseif ($this->option('expired')) {
            Token::with([])->whereDate('expires_at', '<', $expired)->delete();
            AuthCode::with([])->whereDate('expires_at', '<', $expired)->delete();
            RefreshToken::with([])->whereDate('expires_at', '<', $expired)->delete();

            $this->info('Purged items expired for more than seven days.');
        }
    }
}
