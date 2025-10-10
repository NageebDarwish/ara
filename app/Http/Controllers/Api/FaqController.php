<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Get FAQ data for the frontend
     */
    public function index()
    {
        $faqPage = Page::where('slug', 'faq')
            ->orWhere('name', 'FAQ')
            ->with(['faqSections.items'])
            ->first();

        if (!$faqPage) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ page not found'
            ], 404);
        }

        // Transform data to match frontend structure
        $accordionData = [];

        foreach ($faqPage->faqSections as $section) {
            foreach ($section->items as $index => $item) {
                $itemData = [
                    'header' => $item->question,
                    'content' => $item->answer,
                ];

                // Add heading for the first item in each section
                if ($index === 0) {
                    $itemData['heading'] = $section->title;
                    $itemData['bgColor'] = $this->getSectionColor($section->order);
                }

                $accordionData[] = $itemData;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'page' => [
                    'title' => $faqPage->title,
                    'description' => $faqPage->description,
                ],
                'accordionData' => $accordionData,
            ]
        ]);
    }

    /**
     * Get alternating background colors for sections
     */
    private function getSectionColor($order)
    {
        $colors = [
            '#FFD9BC0D',
            '#7AD9F10D',
            '#DBFFDF1A',
        ];

        return $colors[$order % count($colors)];
    }
}
