<?php

namespace App\Services;

use DOMDocument;

class ConvertToHtml
{
    public static function convertEditorJSBlockToHTML($blocks) {
        $data = '';
        foreach ($blocks as $block) {
            if ($block->type === 'paragraph') {
                $data = $data.'<p>' . htmlspecialchars($block->data->text) . '</p>';
            } elseif ($block->type === 'header') {
                $data = $data.'<h' . $block->data->level . '>' . htmlspecialchars($block->data->text) . '</h' . $block->data->level . '>';
            } elseif ($block->type === 'list') {
                $listType = $block->data->style === 'ordered' ? 'ol' : 'ul';
                $items = array_map(function($item) {
                    return '<li>' . htmlspecialchars($item) . '</li>';
                }, $block->data->items);
                $data = $data.'<' . $listType . '>' . implode('', $items) . '</' . $listType . '>';
            } elseif ($block->type === 'quote') {
                $data = $data.'<blockquote>' . htmlspecialchars($block->data->text) . '</blockquote>';
            } elseif ($block->type === 'code') {
                $data = $data.'<pre><code>' . htmlspecialchars($block->data->code) . '</code></pre>';
            } elseif ($block->type === 'image') {
                $data = $data.'<img src="' . htmlspecialchars($block->data->file->url) . '" alt="' . htmlspecialchars($block->data->caption) . '" />';
            } else {
                // Handle other block types or unsupported types here
                $data = $data.'';
            }
        }
        return $data;
    }

    public static function convertHTMLToEditorJS($html) {
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $blocks = [];

        foreach ($dom->getElementsByTagName('*') as $element) {
            $block = [];

            switch ($element->tagName) {
                case 'p':
                    $block['type'] = 'paragraph';
                    $block['data']['text'] = $element->textContent;
                    break;
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                case 'h6':
                    $block['type'] = 'header';
                    $block['data']['text'] = $element->textContent;
                    $block['data']['level'] = intval(substr($element->tagName, 1));
                    break;
                case 'ul':
                    $block['type'] = 'list';
                    $block['data']['style'] = 'unordered';
                    $block['data']['items'] = [];
                    foreach ($element->getElementsByTagName('li') as $li) {
                        $block['data']['items'][] = $li->textContent;
                    }
                    break;
                case 'ol':
                    $block['type'] = 'list';
                    $block['data']['style'] = 'ordered';
                    $block['data']['items'] = [];
                    foreach ($element->getElementsByTagName('li') as $li) {
                        $block['data']['items'][] = $li->textContent;
                    }
                    break;
                case 'blockquote':
                    $block['type'] = 'quote';
                    $block['data']['text'] = $element->textContent;
                    break;
                case 'pre':
                    $block['type'] = 'code';
                    $block['data']['code'] = $element->getElementsByTagName('code')[0]->textContent;
                    break;
                case 'img':
                    $block['type'] = 'image';
                    $block['data']['file']['url'] = $element->getAttribute('src');
                    $block['data']['caption'] = $element->getAttribute('alt');
                    break;
            }
            $blocks[] = $block;
        }

        return [
            'time' => time(),
            'blocks' => $blocks,
            'version' =>  "2.29.0-rc.1"

        ];
    }

}
