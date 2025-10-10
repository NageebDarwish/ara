<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\PageRepository;
use App\Models\{Page, FaqSection, FaqItem};
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{

protected $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.page.index');
    }

    public function getPagesData(Request $request)
    {
        $pages = $this->repository->getPagesForDataTable();

        return DataTables::of($pages)
            ->addIndexColumn()
            ->addColumn('actions', function ($page) {
                $actions = '<a href="' . route('admin.page.edit', $page->id) . '" class="btn btn-warning btn-sm" title="Edit Page"><i class="fa fa-edit"></i></a>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function edit($id)
    {
        $page = $this->repository->find($id);

        // Check if this is the FAQ page
        if ($page->slug === 'faq' || $page->name === 'FAQ') {
            return view('admin.modules.page.edit-faq', compact('page'));
        }

        return view('admin.modules.page.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slug' => [
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                'unique:pages,slug,'.$id
            ],
        ], [
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens without spaces.',
            'slug.unique' => 'This slug is already in use.'
        ]);

        $page = Page::findOrFail($id);

        // Check if this is FAQ page update
        if (($page->slug === 'faq' || $page->name === 'FAQ') && $request->has('faq_sections')) {
            return $this->updateFaq($request, $id);
        }

        $data = $request->all();
        $this->repository->update($id, $data);

        return redirect()->route('admin.page.index')->with('success', 'Updated successfully.');
    }

    public function updateFaq(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $page = Page::findOrFail($id);

            // Update page basic info
            $page->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
            ]);

            // Delete existing sections and items (cascade will handle items)
            FaqSection::where('page_id', $id)->delete();

            // Create new sections and items
            if ($request->has('faq_sections')) {
                foreach ($request->faq_sections as $sectionOrder => $sectionData) {
                    $section = FaqSection::create([
                        'page_id' => $id,
                        'title' => $sectionData['title'] ?? '',
                        'description' => $sectionData['description'] ?? '',
                        'order' => $sectionOrder,
                    ]);

                    // Create FAQ items for this section
                    if (isset($sectionData['items']) && is_array($sectionData['items'])) {
                        foreach ($sectionData['items'] as $itemOrder => $itemData) {
                            if (!empty($itemData['question']) && !empty($itemData['answer'])) {
                                FaqItem::create([
                                    'faq_section_id' => $section->id,
                                    'question' => $itemData['question'],
                                    'answer' => $itemData['answer'],
                                    'order' => $itemOrder,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.page.index')->with('success', 'FAQ updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update FAQ: ' . $e->getMessage());
        }
    }

}
