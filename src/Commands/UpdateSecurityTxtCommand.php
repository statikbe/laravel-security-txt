<?php

namespace Statik\LaravelSecurityTxt\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateSecurityTxtCommand extends Command
{
    protected $signature = 'security-txt:update
                            {--expires-days= : Override the configured expiration days}';

    protected $description = 'Fetch and update the security.txt file from the configured template URL';

    public function handle(): int
    {
        $templateUrl = config('security-txt.template_url');

        if (empty($templateUrl)) {
            $this->error('No template URL configured. Set SECURITY_TXT_TEMPLATE_URL or configure template_url in config/security-txt.php');

            return self::FAILURE;
        }

        $template = $this->fetchTemplate($templateUrl);

        if ($template === null) {
            return self::FAILURE;
        }

        $expiresDays = $this->option('expires-days')
            ? (int) $this->option('expires-days')
            : (int) config('security-txt.expires_days', 365);
        $content = $this->processTemplate($template, $expiresDays);

        $errors = $this->validateContent($content);

        if ($errors !== []) {
            foreach ($errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $outputPath = config('security-txt.output_path');
        File::put($outputPath, $content);

        $this->info("security.txt updated successfully at {$outputPath}");

        return self::SUCCESS;
    }

    private function fetchTemplate(string $url): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);

            if ($response->failed()) {
                $this->logAndError("Failed to fetch template from {$url}: HTTP {$response->status()}");

                return null;
            }

            return $response->body();
        } catch (Throwable $e) {
            $this->logAndError("Failed to fetch template from {$url}: {$e->getMessage()}");

            return null;
        }
    }

    private function processTemplate(string $template, int $expiresDays): string
    {
        $expires = now()->addDays($expiresDays)->toIso8601String();

        $placeholders = array_merge(
            ['EXPIRES' => $expires],
            config('security-txt.placeholders', [])
        );

        foreach ($placeholders as $key => $value) {
            $replacement = is_callable($value) ? $value() : $value;
            $template = str_replace("{{{$key}}}", (string) $replacement, $template);
        }

        return $template;
    }

    /**
     * @return array<string>
     */
    private function validateContent(string $content): array
    {
        $errors = [];

        if (! preg_match('/^Contact:\s*.+$/m', $content)) {
            $errors[] = 'RFC 9116 validation failed: Missing required "Contact" field';
        }

        if (! preg_match('/^Expires:\s*.+$/m', $content)) {
            $errors[] = 'RFC 9116 validation failed: Missing required "Expires" field';
        }

        return $errors;
    }

    private function logAndError(string $message): void
    {
        Log::error("[security-txt] {$message}");
        $this->error($message);
    }
}
