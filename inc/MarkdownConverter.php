<?php
/**
 * A simple Markdown-to-HTML converter with improved support for fenced code blocks.
 *
 * Supported features:
 * - Fenced code blocks using triple backticks, optionally with a language specifier.
 * - Headings (lines starting with 1 to 6 '#' characters).
 * - Horizontal rules (a line with three or more hyphens).
 * - Inline code using backticks.
 * - Bold text with **double asterisks**.
 * - Italic text with *single asterisks*.
 * - Links of the form [text](url).
 * - Paragraphs (blocks of text separated by blank lines).
 *
 * This is a basic implementation and does not cover the full Markdown specification.
 */
class MarkdownConverter {

    /**
     * Converts a Markdown-formatted string to HTML.
     *
     * @param string $markdown The Markdown text.
     * @return string The converted HTML.
     */
    public function convert(string $markdown): string {
        // Normalize line endings.
        $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);

        // --- Step 1. Extract fenced code blocks ---
        // We'll replace code blocks with placeholders to avoid interference with later regex processing.
        $codeBlocks = [];
        // Updated regex: 
        // - Uses the "s" flag so that dot matches newlines.
        // - \s* allows any whitespace before the closing backticks.
        $markdown = preg_replace_callback(
            '/```(\w*)\s*\n(.*?)\s*```/s',
            function ($matches) use (&$codeBlocks) {
                $lang = trim($matches[1]);
                // Escape the code contents so that any HTML in the code is displayed as-is.
                $code = htmlspecialchars($matches[2], ENT_NOQUOTES, 'UTF-8');
                $class = $lang ? ' class="language-' . $lang . '"' : '';
                $placeholder = "%%CODEBLOCK" . count($codeBlocks) . "%%";
                $codeBlocks[$placeholder] = "<pre><code{$class}>{$code}</code></pre>";
                return $placeholder;
            },
            $markdown
        );

        // --- Step 2. Escape the rest of the content ---
        // This ensures that any HTML characters are safely displayed.
        $markdown = htmlspecialchars($markdown, ENT_NOQUOTES, 'UTF-8');

        // --- Step 3. Process inline elements ---

        // Inline code: `code`
        $markdown = preg_replace_callback(
            '/`([^`]+)`/',
            function ($matches) {
                // We do not re-escape here because the content is already escaped.
                return '<code>' . $matches[1] . '</code>';
            },
            $markdown
        );

        // Bold text: **bold**
        $markdown = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $markdown);

        // Italic text: *italic*
        $markdown = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $markdown);

        // Links: [text](url)
        $markdown = preg_replace(
            '/\[(.*?)\]\((.*?)\)/',
            '<a href="$2">$1</a>',
            $markdown
        );

        // --- Step 4. Process block-level elements ---

        // Headings: lines starting with '#' (1 to 6)
        $markdown = preg_replace_callback(
            '/^(#{1,6})\s*(.+)$/m',
            function ($matches) {
                $level = strlen($matches[1]);
                return "<h{$level}>" . trim($matches[2]) . "</h{$level}>";
            },
            $markdown
        );

        // Horizontal rules: a line with three or more hyphens.
        $markdown = preg_replace('/^[-]{3,}$/m', '<hr>', $markdown);

        // --- Step 5. Wrap paragraphs ---
        // Split the text into blocks separated by one or more blank lines.
        $blocks = preg_split('/\n\s*\n/', $markdown);
        foreach ($blocks as &$block) {
            // If the block already starts with a block-level element, leave it.
            if (!preg_match('/^(<h\d>|<ul>|<ol>|<blockquote>|<pre>|<hr>)/', trim($block))) {
                $block = '<p>' . trim($block) . '</p>';
            }
        }
        unset($block); // break reference
        $html = implode("\n\n", $blocks);

        // --- Step 6. Restore extracted code blocks ---
        if (!empty($codeBlocks)) {
            $html = str_replace(array_keys($codeBlocks), array_values($codeBlocks), $html);
        }

        return $html;
    }

    /**
     * Fetches a Markdown file from a URL and converts it to HTML.
     *
     * @param string $url The URL of the Markdown file.
     * @return string The converted HTML.
     * @throws Exception If the Markdown file cannot be retrieved.
     */
    public function convertUrlToHtml(string $url): string {
        $markdown = @file_get_contents($url);
        if ($markdown === false) {
            throw new Exception("Unable to read markdown file from URL: " . $url);
        }
        return $this->convert($markdown);
    }
}

