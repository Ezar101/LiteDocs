<?php

declare(strict_types=1);

namespace LiteDocs\Event;

final class BuildEvents
{
    /**
     * Triggered at the very beginning, after the configuration has been loaded.
     * Ideal for a plugin to modify the configuration on the fly.
     */
    public const string ON_STARTUP = 'build.startup';

    /**
     * Triggered before parsing a Markdown file.
     * Allows you to modify the raw Markdown (e.g., replace variables).
     */
    public const string BEFORE_PARSE = 'build.before_parse';

    /**
     * Triggered after conversion to HTML.
     * Allows you to modify the generated HTML (e.g., add anchors, highlight code).
     */
    public const string AFTER_PARSE = 'build.after_parse';

    /**
     * Triggered at the very end, before leaving.
     * To clean up temporary files or log successes.
     */
    public const string ON_SHUTDOWN = 'build.shutdown';
}
