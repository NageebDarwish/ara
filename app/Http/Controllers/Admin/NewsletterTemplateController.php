<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterTemplate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsletterTemplateController extends Controller
{
    public function index()
    {
        return view('admin.modules.newsletter-templates.index');
    }

    public function getTemplatesData()
    {
        $templates = NewsletterTemplate::orderBy('created_at', 'desc');

        return DataTables::of($templates)
            ->addIndexColumn()
            ->addColumn('status', function ($template) {
                return $template->is_active
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($template) {
                $actions = '<a href="' . route('admin.newsletter-templates.edit', $template->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                $actions .= ' <a href="' . route('admin.newsletter-templates.preview', $template->id) . '" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-eye"></i></a>';
                $actions .= ' <button class="btn btn-sm btn-' . ($template->is_active ? 'secondary' : 'success') . ' toggle-status-btn" data-id="' . $template->id . '"><i class="fa fa-power-off"></i></button>';
                return $actions;
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $template = NewsletterTemplate::findOrFail($id);
        return view('admin.modules.newsletter-templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'html_content' => 'required',
        ]);

        $template = NewsletterTemplate::findOrFail($id);
        $data = $request->only(['subject', 'html_content', 'description']);
        $data['is_active'] = $request->has('is_active');

        $template->update($data);

        return redirect()->route('admin.newsletter-templates.index')->with('success', 'Newsletter template updated successfully.');
    }

    public function preview($id)
    {
        $template = NewsletterTemplate::findOrFail($id);

        // Preview with sample data
        $sampleData = [
            'user_name' => 'John Doe',
            'video_title' => 'Sample Arabic Video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_description' => 'This is a sample video description for preview purposes.',
        ];

        $html = $template->render($sampleData);

        return response($html)->header('Content-Type', 'text/html');
    }

    public function toggleStatus(Request $request)
    {
        $template = NewsletterTemplate::findOrFail($request->id);
        $template->update(['is_active' => !$template->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active,
            'message' => 'Status updated successfully.'
        ]);
    }
}
